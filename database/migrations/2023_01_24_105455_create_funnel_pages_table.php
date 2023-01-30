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
        Schema::create('funnel_pages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('folder_id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('file_location')->nullable();
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
        Schema::dropIfExists('funnel_pages');
    }
};