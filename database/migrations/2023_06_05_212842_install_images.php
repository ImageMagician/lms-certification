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
        if(!Schema::hasTable('install_images')){
            Schema::create('install_images', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->tinyInteger('module_id');
                $table->string('image_cat', 10);
                $table->string('image_title');
                $table->string('image_ext', 10);
                $table->string('image_path');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('install_images');
    }
};
