<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->dateTime('module_01_video')->nullable();
            $table->dateTime('module_01')->nullable();
            $table->dateTime('module_02_video')->nullable();
            $table->dateTime('module_02')->nullable();
            $table->dateTime('module_03_video')->nullable();
            $table->dateTime('module_03')->nullable();
            $table->dateTime('module_04_video')->nullable();
            $table->dateTime('module_04')->nullable();
            $table->dateTime('module_05_video')->nullable();
            $table->dateTime('module_05')->nullable();
            $table->dateTime('module_06_video')->nullable();
            $table->dateTime('module_06')->nullable();
            $table->dateTime('module_07_video')->nullable();
            $table->dateTime('module_07')->nullable();
            $table->dateTime('module_08_video')->nullable();
            $table->dateTime('module_08')->nullable();
            $table->dateTime('module_09_video')->nullable();
            $table->dateTime('module_09')->nullable();
            $table->dateTime('module_10_video')->nullable();
            $table->dateTime('module_10')->nullable();
            $table->dateTime('module_11_video')->nullable();
            $table->dateTime('module_11')->nullable();
            $table->dateTime('module_12_video')->nullable();
            $table->dateTime('module_12')->nullable();
            $table->tinyInteger('training_done')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
