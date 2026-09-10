<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('target_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "imo HD", "WhatsApp", etc.
            $table->string('badge')->nullable(); // e.g. "HD", "PRO"
            $table->string('icon'); // icon path or svg
            $table->string('category')->default('messenger'); // messenger, gaming, calling
            $table->string('package_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->text('guide_info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_apps');
    }
};
