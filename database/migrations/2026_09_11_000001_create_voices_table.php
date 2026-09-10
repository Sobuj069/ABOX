<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "AI Girl2", "AI Male4"
            $table->string('gender')->default('female'); // male, female, neutral
            $table->string('category')->default('natural'); // natural, anime, game, deep, funny
            $table->string('avatar'); // image url or file path
            $table->string('preview_audio')->nullable(); // sample preview mp3/wav
            $table->boolean('is_vip')->default(false); // VIP only or Free
            $table->float('pitch_shift')->default(0.0); // pitch shift semitones (-12 to +12)
            $table->float('speed')->default(1.0); // playback speed / tempo
            $table->float('reverb')->default(0.0); // room reverb effect
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voices');
    }
};
