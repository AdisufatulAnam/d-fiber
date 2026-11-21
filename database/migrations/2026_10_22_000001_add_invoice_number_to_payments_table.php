<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('invoice_number', 20)->nullable()->after('id');
        });

        $this->backfill();
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('invoice_number');
        });
    }

    private function backfill(): void
    {
        $confirmed = DB::table('payments')
            ->where('status', 'confirmed')
            ->orderBy('created_at')
            ->get();

        $perDay = [];

        foreach ($confirmed as $payment) {
            $date = substr($payment->created_at, 0, 10);
            $timestamp = strtotime($date);
            $ymd = date('ymd', $timestamp === false ? time() : $timestamp);
            $perDay[$date] = ($perDay[$date] ?? 0) + 1;
            $seq = sprintf('%05d', $perDay[$date]);

            DB::table('payments')
                ->where('id', $payment->id)
                ->update(['invoice_number' => 'PAY'.$ymd.$seq]);
        }
    }
};
