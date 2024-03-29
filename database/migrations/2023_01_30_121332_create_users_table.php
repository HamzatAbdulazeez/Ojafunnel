<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->comment('');
            $table->bigIncrements('id');
            $table->string('user_type')->nullable();
            $table->string('affiliate_link')->nullable();
            $table->string('code')->nullable();
            $table->string('photo')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username', 100)->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('referral_link')->nullable();
            $table->string('ref_bonus')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('plan')->nullable();
            $table->unsignedInteger('customer_id')->nullable()->index('mailusers_customer_id_foreign');
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('wallet')->default('0.00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
