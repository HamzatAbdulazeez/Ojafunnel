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
        Schema::create('job_monitors', function (Blueprint $table) {
            $table->comment('');
            $table->bigIncrements('id');
            $table->char('uid', 36);
            $table->string('subject_name');
            $table->bigInteger('subject_id');
            $table->string('batch_id')->nullable();
            $table->bigInteger('job_id')->nullable();
            $table->string('job_type')->nullable();
            $table->mediumText('error')->nullable();
            $table->mediumText('data')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_monitors');
    }
};
