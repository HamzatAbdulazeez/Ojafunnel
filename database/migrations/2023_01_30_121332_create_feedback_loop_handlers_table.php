<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_loop_handlers', function (Blueprint $table) {
            $table->comment('');
            $table->increments('id');
            $table->char('uid', 36);
            $table->unsignedInteger('admin_id')->index('mailfeedback_loop_handlers_admin_id_foreign');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->string('port');
            $table->string('protocol');
            $table->string('encryption');
            $table->string('status');
            $table->timestamps();
            $table->string('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_loop_handlers');
    }
};
