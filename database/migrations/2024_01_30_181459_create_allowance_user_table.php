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
        Schema::create('allowance_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allowance_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->integer('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_user');
    }
};
