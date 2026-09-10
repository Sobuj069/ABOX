<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_app_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('target_app_id')->constrained('target_apps')->cascadeOnDelete();
            $table->foreignId('selected_voice_id')->nullable()->constrained('voices')->nullOnDelete();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'target_app_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_app_settings');
    }
};
