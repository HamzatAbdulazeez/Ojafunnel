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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('sender')->nullable();
            $table->string('sid')->nullable();
            $table->string('token')->nullable();
            $table->string('from')->nullable();
            $table->string('api_key')->nullable();
            $table->string('type')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->index();
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
        Schema::dropIfExists('integrations');
    }
};
