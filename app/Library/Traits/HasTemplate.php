<?php

namespace App\Library\Traits;

use App\Models\Template;
use Exception;
use App\Library\ExtendedSwiftMessage;
use App\Models\Setting;
use App\Library\StringHelper;
use League\Pipeline\PipelineBuilder;
use App\Library\HtmlHandler\ParseRss;
use App\Library\HtmlHandler\ReplaceBareLineFeed;
use App\Library\HtmlHandler\AppendHtml;
use App\Library\HtmlHandler\TransformTag;
use App\Library\HtmlHandler\InjectTrackingPixel;
use App\Library\HtmlHandler\MakeInlineCss;
use App\Library\HtmlHandler\TransformUrl;
use App\Library\HtmlHandler\TransformWidgets;
use App\Library\HtmlHandler\AddDoctype;
use App\Library\HtmlHandler\RemoveTitleTag;
use App\Library\Lockable;
use File;
use Cache;

trait HasTemplate
{
    /**
     * Campaign has one template.
     */
    public function template()
    {
        return $this->belongsTo('App\Models\Template');
    }

    /**
     * Get template.
     */
    public function setTemplate($template)
    {
        $campaignTemplate = $template->copy([
            'name' => trans('messages.campaign.template_name', ['name' => $this->name]),
            'customer_id' => $this->customer_id,
        ]);

        // remove exist template
        if ($this->template) {
            $this->template->deleteAndCleanup();
        }

        $this->template_id = $campaignTemplate->id;
        $this->save();
        $this->refresh();
        $this->updatePlainFromHtml();
        $this->updateLinks();
    }

    /**
     * Upload a template.
     */
    public function uploadTemplate($request)
    {
        $template = Template::uploadTemplate($request);
        $this->setTemplate($template);
    }

    /**
     * Check if email has template.
     */
    public function hasTemplate()
    {
        return $this->template()->exists();
    }

    /**
     * Get thumb.
     */
    public function getThumbUrl()
    {
        if ($this->template) {
            return $this->template->getThumbUrl();
        } else {
            return url('images/placeholder.jpg');
        }
    }

    /**
     * Remove email template.
     */
    public function removeTemplate()
    {
        $this->template->deleteAndCleanup();
    }

    /**
     * Update campaign plain text.
     */
    public function updatePlainFromHtml()
    {
        if (!$this->plain) {
            $this->plain = preg_replace('/\s+/', ' ', preg_replace('/\r\n/', ' ', strip_tags($this->getTemplateContent())));
            $this->save();
        }
    }

    /**
     * Set template content.
     */
    public function setTemplateContent($content, $callback = null)
    {
        if (!$this->template) {
            throw new Exception('Cannot set content: campaign/email does not have template!');
        }

        $template = $this->template;
        $template->content = $content;
        $template->save();
        if (!is_null($callback)) {
            $callback($this);
        }
    }

    /**
     * Get template content.
     */
    public function getTemplateContent()
    {
        if (!$this->template) {
            throw new Exception('Cannot get content: campaign/email does not have template!');
        }

        return $this->template->content;
    }

    /**
     * Build Email Custom Headers.
     *
     * @return Hash list of custom headers
     */
    public function getCustomHeaders($subscriber, $server)
    {
        $msgId = StringHelper::generateMessageId(StringHelper::getDomainFromEmail($this->from_email));

        if ($this->isStdClassSubscriber($subscriber)) {
            $unsubscribeUrl = null;
        } else {
            $unsubscribeUrl = $subscriber->generateUnsubscribeUrl($msgId);
            if ($this->trackingDomain) {
                $unsubscribeUrl = $this->trackingDomain->buildTrackingUrl($unsubscribeUrl);
            }
        }

        $headers = array(
            'X-App-Campaign-Id' => $this->uid,
            'X-App-Subscriber-Id' => $subscriber->uid,
            'X-App-Customer-Id' => $this->customer->uid,
            'X-App-Message-Id' => $msgId,
            'X-App-Sending-Server-Id' => $server->uid,
            'Precedence' => 'bulk',
        );

        if ($unsubscribeUrl) {
            $headers['List-Unsubscribe'] = "<{$unsubscribeUrl}>";
        }

        return $headers;
    }

    /**
     * Check if the given variable is a subscriber object (for actually sending a email)
     * Or a stdClass subscriber (for sending test email).
     *
     * @param object $object
     */
    public function isStdClassSubscriber($object)
    {
        return get_class($object) == 'stdClass';
    }

    /**
     * Prepare the email content using Swift Mailer.
     *
     * @input object subscriber
     * @input object sending server
     *
     * @return MIME text message
     */
    public function prepareEmail($subscriber, $server = null, $fromCache = false, $expiresInSeconds = 600)
    {
        // build the message
        $customHeaders = $this->getCustomHeaders($subscriber, $this);
        $msgId = $customHeaders['X-App-Message-Id'];

        $message = new ExtendedSwiftMessage();
        $message->setId($msgId);

        if (is_null($this->type) || $this->type == self::TYPE_REGULAR) {
            $message->setContentType('text/html; charset=utf-8');
        } else {
            $message->setContentType('text/plain; charset=utf-8');
        }

        foreach ($customHeaders as $key => $value) {
            $message->getHeaders()->addTextHeader($key, $value);
        }

        // @TODO for AWS, setting returnPath requires verified domain or email address
        if (!is_null($server) && $server->allowCustomReturnPath()) {
            $returnPath = $server->getVerp($subscriber->email);
            if ($returnPath) {
                $message->setReturnPath($returnPath);
            }
        }
        $message->setSubject($this->getSubject($subscriber, $msgId));
        $message->setFrom(array($this->from_email => $this->from_name));
        $message->setTo($subscriber->email);

        if (!empty(Setting::get('campaign.bcc'))) {
            $addresses = array_filter(preg_split('/\s*,\s*/', Setting::get('campaign.bcc')));
            $message->setBcc($addresses);
        }

        if (!empty(Setting::get('campaign.cc'))) {
            $addresses = array_filter(preg_split('/\s*,\s*/', Setting::get('campaign.cc')));
            $message->setCc($addresses);
        }

        $message->setReplyTo($this->reply_to);
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->addPart($this->getPlainContent($subscriber, $msgId, $server), 'text/plain');
        if (is_null($this->type) || $this->type == self::TYPE_REGULAR) {
            $message->addPart($this->getHtmlContent($subscriber, $msgId, $server, $fromCache, $expiresInSeconds), 'text/html');
        }

        if ($this->sign_dkim) {
            $message = $this->sign($message);
        }

        if ($this->attachments) {
            // Email model
            foreach ($this->attachments as $file) {
                $attachment = \Swift_Attachment::fromPath($file->file);
                $message->attach($attachment);
                // This is used by certain delivery services like ElasticEmail
                $message->extAttachments[] = [ 'path' => $file->file, 'type' => $attachment->getContentType()];
            }
        } else {
            // Campaign model
            //@todo attach function used for any attachment of Campaign
            $path_campaign = $this->getAttachmentPath();
            if (is_dir($path_campaign)) {
                $files = File::allFiles($path_campaign);
                foreach ($files as $file) {
                    $attachment = \Swift_Attachment::fromPath((string) $file);
                    $message->attach($attachment);
                    // This is used by certain delivery services like ElasticEmail
                    $message->extAttachments[] = [ 'path' => (string) $file, 'type' => $attachment->getContentType()];
                }
            }
        }

        return array($message, $msgId);
    }

    /**
     * Get tagged Subject.
     *
     * @return string
     */
    public function getSubject($subscriber, $msgId)
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new ReplaceBareLineFeed());
        $pipeline->add(new TransformTag($this, $subscriber, $msgId));
        return $pipeline->build()->process($this->subject);
    }

    /**
     * Check if email footer enabled.
     *
     * @return string
     * @deprecated this is a very poorly designed function with dependencies session!
     * @todo so, we are adding if/else to facilitate testing only
     */
    public function footerEnabled()
    {
        if (is_null($this->customer)) {
            return;
        }

        return ($this->customer->getCurrentSubscription()->plan->getOption('email_footer_enabled') == 'yes') ? true : false;
    }

    /**
     * Get HTML footer.
     *
     * @return string
     * @deprecated this is a very poorly designed function with dependencies session!
     * @todo so, we are adding if/else to facilitate testing only
     */
    public function getHtmlFooter()
    {
        if (is_null($this->customer)) {
            return;
        }

        return $this->customer->getCurrentSubscription()->plan->getOption('html_footer');
    }

    /**
     * Find sending domain from email.
     *
     * @return mixed
     */
    public function findSendingDomain($email)
    {
        $domainName = substr(strrchr($email, '@'), 1);

        if ($domainName == false) {
            return;
        }

        $domain = $this->customer->sendingDomains()->where('name', $domainName)->first();

        return $domain;
    }

    /**
     * Sign the message with DKIM.
     *
     * @return mixed
     */
    public function sign($message)
    {
        $sendingDomain = $this->findSendingDomain($this->from_email);

        if (is_null($sendingDomain)) {
            return $message;
        }

        $privateKey = $sendingDomain->dkim_private;
        $domainName = $sendingDomain->name;
        $selector = $sendingDomain->getDkimSelectorParts()[0];
        $signer = new \Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
        $signer->ignoreHeader('Return-Path');
        $message->attachSigner($signer);

        return $message;
    }

    public function getCachedHtmlId()
    {
        return "{$this->uid}-html";
    }

    public function clearCache()
    {
        Cache::forget($this->getCachedHtmlId());
    }

    /**
     * Build Email HTML content.
     *
     * @return string
     */
    public function getHtmlContent($subscriber = null, $msgId = null, $server = null, $fromCache = false, $expiresInSeconds = 600)
    {
        $baseHtml = $this->getBaseHtmlContent($fromCache, $expiresInSeconds);

        // Bind subscriber/message/server information to email content
        $pipeline = new PipelineBuilder();
        $pipeline->add(new TransformTag($this, $subscriber, $msgId, $server));
        $pipeline->add(new InjectTrackingPixel($msgId));
        $pipeline->add(new TransformUrl($this->template, $msgId, $this->trackingDomain));
        $pipeline->add(new TransformWidgets($this->template));

        // Actually push HTML to pipeline for processing
        $html = $pipeline->build()->process($baseHtml);

        // Return subscriber's bound html
        return $html;
    }

    // Return the HTML content which has been processed through base handlers (pipeline)
    // Which is not associated with any subscriber/message/server
    public function getBaseHtmlContent($fromCache = false, $expiresInSeconds = 600)
    {
        if (!$this->template) {
            throw new Exception('No template available');
        }

        $cacheId = $this->getCachedHtmlId();
        $updateCacheFlag = $fromCache && !Cache::has($cacheId);
        $html = null;

        if (!$fromCache || $updateCacheFlag) {
            $pipeline = new PipelineBuilder();
            $pipeline->add(new AddDoctype());
            $pipeline->add(new RemoveTitleTag());
            $pipeline->add(new ReplaceBareLineFeed());
            $pipeline->add(new AppendHtml($this->getHtmlFooter()));
            $pipeline->add(new ParseRss());
            $pipeline->add(new MakeInlineCss($this->template->findCssFiles()));
            // $pipeline->add(new TransformTag($this, $subscriber, $msgId, $server));
            // $pipeline->add(new InjectTrackingPixel($msgId));
            // $pipeline->add(new TransformUrl($this->template, $msgId, $this->trackingDomain));
            // $html = $this->wooTransform($html);
            $html = $pipeline->build()->process($this->getTemplateContent());
        }

        if ($updateCacheFlag) {
            $lockfile = storage_path('locks/campaign-cache-'.$this->uid);
            $lock = new Lockable($lockfile);

            $lock->getExclusiveLock(function ($f) use ($cacheId, $html, $expiresInSeconds) {
                Cache::put($cacheId, $html, $expiresInSeconds);
            }, $timeoutSeconds = 3, $timeoutCallback = function () {
                // echo "Quit me mememem";
                // just quit, do not throw exception
            });
        }

        // It is important to return $html in priority here, as cache update may not work!
        return $html ?: Cache::get($cacheId);
    }

    /**
     * Build Email HTML content.
     *
     * @return string
     */
    public function getPlainContent($subscriber, $msgId, $server = null)
    {
        $plain = $this->plain.$this->getPlainTextFooter();
        $pipeline = new PipelineBuilder();
        $pipeline->add(new ReplaceBareLineFeed());
        $pipeline->add(new TransformTag($this, $subscriber, $msgId, $server));
        $plain = $pipeline->build()->process($plain);

        return $plain;
    }

    /**
     * Get PLAIN TEXT footer.
     *
     * @return string
     * @deprecated this is a very poorly designed function with dependencies session!
     * @todo so, we are adding if/else to facilitate testing only
     */
    public function getPlainTextFooter()
    {
        if (is_null($this->customer)) {
            return;
        }

        return $this->customer->getCurrentSubscription()->plan->getOption('plain_text_footer');
    }

    /**
     * Create a stdClass subscriber (for sending a campaign test email)
     * The campaign sending functions take a subscriber object as input
     * However, a test email address is not yet a subscriber object, so we have to build a fake stdClass object
     * which can be used as a real subscriber.
     *
     * @param array $subscriber
     */
    public function createStdClassSubscriber($subscriber)
    {
        // default attributes that are required
        $jsonObj = [
            'uid' => uniqid(),
        ];

        // append the customer specified attributes and build a stdClass object
        $stdObj = json_decode(json_encode(array_merge($jsonObj, $subscriber)));

        return $stdObj;
    }
}
