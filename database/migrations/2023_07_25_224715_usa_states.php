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
        if (!Schema::hasTable('usa_states')) {
            Schema::create('usa_states', function (Blueprint $table) {
                $table->id();
                $table->string('abbrev');
                $table->string('name');
                $table->string('rep');
                $table->text('map_path');
                $table->text('map_text_x');
                $table->text('map_text_y');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usa_states');
    }
};
