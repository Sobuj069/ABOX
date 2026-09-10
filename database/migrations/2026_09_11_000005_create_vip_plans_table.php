<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vip_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Weekly VIP", "Monthly VIP", "Lifetime VIP"
            $table->decimal('price', 8, 2);
            $table->integer('duration_days')->default(7); // 0 for lifetime
            $table->integer('credits_bonus')->default(100);
            $table->string('badge')->nullable(); // "POPULAR", "BEST VALUE"
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vip_plans');
    }
};
