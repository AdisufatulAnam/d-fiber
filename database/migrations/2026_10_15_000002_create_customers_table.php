<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('qr_code')->unique();
            $table->timestamps();

            $table->index('qr_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
