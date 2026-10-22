<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Fiber 10 Mbps',
                'speed' => '10 Mbps',
                'price' => 150000,
                'description' => 'Paket rumahan untuk kebutuhan dasar: browsing, streaming SD, video call.',
            ],
            [
                'name' => 'Fiber 20 Mbps',
                'speed' => '20 Mbps',
                'price' => 200000,
                'description' => 'Cocok untuk keluarga kecil, streaming HD, WFH ringan.',
            ],
            [
                'name' => 'Fiber 50 Mbps',
                'speed' => '50 Mbps',
                'price' => 300000,
                'description' => 'Untuk keluarga besar atau usaha kecil, multi-device streaming.',
            ],
            [
                'name' => 'Fiber 100 Mbps',
                'speed' => '100 Mbps',
                'price' => 500000,
                'description' => 'Paket bisnis, gaming, upload besar, multiple heavy users.',
            ],
            [
                'name' => 'Business 200 Mbps',
                'speed' => '200 Mbps',
                'price' => 1000000,
                'description' => 'Paket dedicated untuk perkantoran dan UMKM besar.',
            ],
        ];

        foreach ($packages as $data) {
            Package::create($data);
        }
    }
}
