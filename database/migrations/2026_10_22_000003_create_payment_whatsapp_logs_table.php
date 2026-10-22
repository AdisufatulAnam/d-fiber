<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('phone');
            $table->string('status')->default('pending')->index(); // pending, sent, failed
            $table->unsignedInteger('attempts')->default(0);
            $table->text('message')->nullable();
            $table->string('invoice_file')->nullable();
            $table->text('error')->nullable();
            $table->text('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_whatsapp_logs');
    }
};
