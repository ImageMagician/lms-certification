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
        if(!Schema::hasTable('user_activities')) {
            Schema::create('user_activities', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unsigned();
                $table->integer('module_01_video')->nullable();
                $table->float('module_01')->nullable();
                $table->integer('module_02_video')->nullable();
                $table->float('module_02')->nullable();
                $table->integer('module_03_video')->nullable();
                $table->float('module_03')->nullable();
                $table->integer('module_04_video')->nullable();
                $table->float('module_04')->nullable();
                $table->integer('module_05_video')->nullable();
                $table->float('module_05')->nullable();
                $table->integer('module_06_video')->nullable();
                $table->float('module_06')->nullable();
                $table->integer('module_07_video')->nullable();
                $table->float('module_07')->nullable();
                $table->integer('module_08_video')->nullable();
                $table->float('module_08')->nullable();
                $table->integer('module_09_video')->nullable();
                $table->float('module_09')->nullable();
                $table->integer('module_10_video')->nullable();
                $table->float('module_10')->nullable();
                $table->integer('module_11_video')->nullable();
                $table->float('module_11')->nullable();
                $table->integer('module_12_video')->nullable();
                $table->float('module_12')->nullable();
                $table->tinyInteger('training_done')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
