<?php

namespace App\Http\Controllers;

use App\Models\c;
use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Type\Integer;

class IntegrationController extends Controller
{
    public function integration_create(Request $request)
    {
        if($request->type == 'Twilio')
        {
            //Validate Request
            $this->validate($request, [
                'sid' => ['required', 'string', 'max:255'],
                'token' => ['required', 'string', 'max:255'],
                'from' => ['required', 'string', 'max:255'],
            ]);

            Integration::create([
                'user_id' => Auth::user()->id,
                'sid' => $request->sid,
                'token' => $request->token,
                'from' => $request->from,
                'type' => $request->type
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'Twilio Integration Created Successfully!'
            ]); 
        }
        if($request->type == 'InfoBip')
        {
            //Validate Request
            $this->validate($request, [
                'api_key' => ['required', 'string', 'max:255']
            ]);

            Integration::create([
                'user_id' => Auth::user()->id,
                'api_key' => $request->api_key,
                'type' => $request->type
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'InfoBip Integration Created Successfully!'
            ]); 
        }
        if($request->type == 'NigeriaBulkSms')
        {
            //Validate Request
            $this->validate($request, [
                'username' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
                'sender' => ['required', 'string', 'max:255']
            ]);

            Integration::create([
                'user_id' => Auth::user()->id,
                'username' => $request->username,
                'password' => $request->password,
                'sender' => $request->sender,
                'type' => $request->type
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'NigeriaBulkSms Integration Created Successfully!'
            ]); 
        }
        if($request->type == 'Multitexter')
        {
            //Validate Request
            $this->validate($request, [
                'username' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255']
            ]);

            Integration::create([
                'user_id' => Auth::user()->id,
                'username' => $request->username,
                'password' => $request->password,
                'type' => $request->type
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'Multitexter Integration Created Successfully!'
            ]); 
        }
        return back()->with([
            'type' => 'danger',
            'message' => 'Integration Not Found.'
        ]); 
    }

    public function integration_update($id, Request $request)
    {
        $idFinder = Crypt::decrypt($id);

        $integration = Integration::findorfail($idFinder);

        if($integration->type == 'Twilio')
        {
            //Validate Request
            $this->validate($request, [
                'sid' => ['required', 'string', 'max:255'],
                'token' => ['required', 'string', 'max:255'],
                'from' => ['required', 'string', 'max:255'],
            ]);

            $integration->update([
                'sid' => $request->sid,
                'token' => $request->token,
                'from' => $request->from,
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'Twilio Integration Updated Successfully!'
            ]); 
        }
        if($integration->type == 'InfoBip')
        {
            //Validate Request
            $this->validate($request, [
                'api_key' => ['required', 'string', 'max:255']
            ]);

            $integration->update([
                'api_key' => $request->api_key
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'InfoBip Integration Updated Successfully!'
            ]); 
        }
        if($integration->type == 'NigeriaBulkSms')
        {
            //Validate Request
            $this->validate($request, [
                'username' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
                'sender' => ['required', 'string', 'max:255']
            ]);

            $integration->update([
                'username' => $request->username,
                'password' => $request->password,
                'sender' => $request->sender
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'NigeriaBulkSms Integration Updated Successfully!'
            ]); 
        }
        if($integration->type == 'Multitexter')
        {
            //Validate Request
            $this->validate($request, [
                'username' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255']
            ]);

            $integration->update([
                'username' => $request->username,
                'password' => $request->password
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'Multitexter Integration Updated Successfully!'
            ]); 
        }
        return back()->with([
            'type' => 'danger',
            'message' => "Integration doesn't exit."
        ]); 
    }

    public function integration_enable($id)
    {
        $idFinder = Crypt::decrypt($id);

        $integration = Integration::findorfail($idFinder);

        $integration->update([
            'status' => 'Active'
        ]);

        return back()->with([
            'type' => 'success',
            'message' => $integration->type.' Integration Enabled Successfully!'
        ]); 
    }

    public function integration_disable($id)
    {
        $idFinder = Crypt::decrypt($id);

        $integration = Integration::findorfail($idFinder);

        $integration->update([
            'status' => 'Inactive'
        ]);

        return back()->with([
            'type' => 'success',
            'message' => $integration->type.' Integration Disabled Successfully!'
        ]); 
    }

    public function integration_delete($id, Request $request)
    {
        //Validate Request
        $this->validate($request, [
            'delete_field' => ['required', 'string', 'max:255']
        ]);

        if($request->delete_field == "DELETE")
        {
            $idFinder = Crypt::decrypt($id);

            Integration::findorfail($idFinder)->delete();

            return back()->with([
                'type' => 'success',
                'message' => 'Integration Deleted Successfully!'
            ]); 
        } 

        return back()->with([
            'type' => 'danger',
            'message' => "Field doesn't match, Try Again!"
        ]); 
    }
}