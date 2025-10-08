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
        if (!Schema::hasTable('modules')) {
            Schema::create('modules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('version');
                $table->foreign('version')->references('id')->on('versions');
                $table->string('section');
                $table->string('title');
                $table->text('description');
                $table->string('video')->nullable();
                $table->string('video_poster')->nullable();
                $table->float('passing_percentage')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
