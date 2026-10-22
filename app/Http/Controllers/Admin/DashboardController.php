<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Halaman dashboard admin operasional.
     */
    public function index(Request $request): Response
    {
        $admin = $request->user();

        return Inertia::render('Admin/Dashboard', [
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
            ],
            'stats' => [
                [
                    'label' => 'Pelanggan Aktif',
                    'value' => '512',
                    'hint' => '+18 bulan ini',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Pendapatan Bulan Ini',
                    'value' => 'Rp67,4jt',
                    'hint' => '76% target',
                    'tone' => 'cyan',
                ],
                [
                    'label' => 'Tiket Support',
                    'value' => '7',
                    'hint' => '3 perlu tindak lanjut',
                    'tone' => 'amber',
                ],
                [
                    'label' => 'Node Jaringan',
                    'value' => '12/12',
                    'hint' => 'Semua normal',
                    'tone' => 'emerald',
                ],
            ],
            'activities' => [
                [
                    'time' => '10 menit lalu',
                    'text' => 'Pelanggan baru di RT 03 / RW 07 — Kalitengah, Piyungan.',
                ],
                [
                    'time' => '1 jam lalu',
                    'text' => 'Pembayaran paket DFiber Family (Rp150.000) diterima.',
                ],
                [
                    'time' => '3 jam lalu',
                    'text' => 'Node Piyungan-02 selesai reboot, jaringan normal kembali.',
                ],
                [
                    'time' => 'Kemarin',
                    'text' => 'PSB (pemasangan baru) Rp150.000 untuk 2 lokasi selesai.',
                ],
            ],
        ]);
    }
}
