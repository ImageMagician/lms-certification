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
        if(!Schema::hasTable('module_quizzes')) {
            Schema::create('module_quizzes', function (Blueprint $table) {
                $table->id();
                $table->integer('module_id')->unsigned();
                $table->integer('q_id')->unsigned();
                $table->text('question');
                $table->text('answer_array');
                $table->string('answer_correct', 2);
                $table->double('video_snippet_start',8,4)->nullable();
                $table->double('video_snippet_end',8,4)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_quizzes');
    }
};
