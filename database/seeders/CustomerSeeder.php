<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Payment::query()->delete();
        Customer::query()->delete();

        $packages = Package::pluck('id')->toArray();
        if (empty($packages)) {
            $this->call(PackageSeeder::class);
            $packages = Package::pluck('id')->toArray();
        }

        $customers = [
            ['name' => 'Budi Santoso',      'phone' => '081234567801', 'address' => 'Jl. Merdeka No. 10',        'qr_code' => 'DFB-A1B2C3D4'],
            ['name' => 'Siti Aminah',        'phone' => '081234567802', 'address' => 'Jl. Sudirman No. 25',       'qr_code' => 'DFB-E5F6G7H8'],
            ['name' => 'Andi Wijaya',        'phone' => '081234567803', 'address' => 'Jl. Gatot Subroto No. 5',   'qr_code' => 'DFB-I9J0K1L2'],
            ['name' => 'Dewi Lestari',       'phone' => '081234567804', 'address' => 'Jl. Ahmad Yani No. 18',     'qr_code' => 'DFB-M3N4O5P6'],
            ['name' => 'Rudi Hartono',       'phone' => '081234567805', 'address' => 'Jl. Diponegoro No. 42',     'qr_code' => 'DFB-Q7R8S9T0'],
            ['name' => 'Rina Marlina',       'phone' => '081234567806', 'address' => 'Jl. Kenanga No. 3',         'qr_code' => 'DFB-U1V2W3X4'],
            ['name' => 'Joko Susilo',        'phone' => '081234567807', 'address' => 'Jl. Melati No. 15',         'qr_code' => 'DFB-Y5Z6A7B8'],
            ['name' => 'Maya Putri',         'phone' => '081234567808', 'address' => 'Jl. Anggrek No. 7',         'qr_code' => 'DFB-C9D0E1F2'],
            ['name' => 'Hendra Gunawan',     'phone' => '081234567809', 'address' => 'Jl. Cendana No. 21',        'qr_code' => 'DFB-G3H4I5J6'],
            ['name' => 'Fitri Handayani',     'phone' => '081234567810', 'address' => 'Jl. Flamboyan No. 9',       'qr_code' => 'DFB-K7L8M9N0'],
            ['name' => 'Agus Prasetyo',      'phone' => '081234567811', 'address' => 'Jl. Mawar No. 30',          'qr_code' => 'DFB-O1P2Q3R4'],
            ['name' => 'Lina Kartika',       'phone' => '081234567812', 'address' => 'Jl. Dahlia No. 12',         'qr_code' => 'DFB-S5T6U7V8'],
            ['name' => 'Toko Berkah Jaya',   'phone' => '081234567813', 'address' => 'Jl. Pasar Baru Blok C No. 5','qr_code' => 'DFB-W9X0Y1Z2'],
            ['name' => 'CV Maju Mundur',     'phone' => '081234567814', 'address' => 'Jl. Industri No. 88',       'qr_code' => 'DFB-A3B4C5D6'],
            ['name' => 'Warung Enak Selera', 'phone' => '081234567815', 'address' => 'Jl. Kuliner No. 3',         'qr_code' => 'DFB-E7F8G9H0'],
        ];

        foreach ($customers as $data) {
            $data['package_id'] = $packages[array_rand($packages)];
            $customer = Customer::create($data);
            $package = Package::find($data['package_id']);

            // Buat tagihan 6 bulan terakhir sesuai harga paket
            $startMonth = now()->subMonthsNoOverflow(5)->startOfMonth();

            for ($i = 0; $i < 6; $i++) {
                $billingMonth = $startMonth->copy()->addMonths($i)->format('Y-m');
                $status = $i < 5 ? 'confirmed' : (rand(0, 10) > 7 ? 'pending' : 'confirmed');
                $method = rand(0, 10) > 4 ? 'qrcode' : 'manual';

                Payment::create([
                    'customer_id'    => $customer->id,
                    'customer_name'  => $customer->name,
                    'billing_month'  => $billingMonth,
                    'amount'         => $package->price,
                    'payment_method' => $method,
                    'reference'      => $method === 'qrcode' ? 'QR-' . strtoupper(bin2hex(random_bytes(3))) : null,
                    'status'         => $status,
                    'notes'          => "Tagihan {$package->name}",
                ]);
            }
        }
    }
}
