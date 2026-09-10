<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('voice_id')->nullable()->constrained('voices')->nullOnDelete();
            $table->string('title');
            $table->string('original_file')->nullable();
            $table->string('processed_file');
            $table->float('duration')->default(0.0); // in seconds
            $table->integer('file_size')->default(0); // in bytes
            $table->float('pitch_shift')->default(0.0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordings');
    }
};
