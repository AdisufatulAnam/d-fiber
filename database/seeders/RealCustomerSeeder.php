<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class RealCustomerSeeder extends Seeder
{
    /**
     * Data pelanggan nyata: [nama, ip_address].
     */
    private array $data = [
        ['mbah dukun', '192.168.77.2'],
        ['imam prasetyo', '192.168.77.3'],
        ['wahyu', '192.168.77.4'],
        ['darto', '192.168.77.5'],
        ['sarman', '192.168.77.6'],
        ['joko', '192.168.77.7'],
        ['wiji', '192.168.77.8'],
        ['sudiyo', '192.168.77.9'],
        ['triyanto', '192.168.77.10'],
        ['kembar', '192.168.77.11'],
        ['sularman', '192.168.77.12'],
        ['sardiyono', '192.168.77.13'],
        ['sarjono', '192.168.77.14'],
        ['siswanto', '192.168.77.15'],
        ['fanni', '192.168.77.16'],
        ['tukijo', '192.168.77.17'],
        ['suradi', '192.168.77.18'],
        ['triyuwono', '192.168.77.19'],
        ['pipit', '192.168.77.20'],
        ['teplo', '192.168.77.21'],
        ['wardiyanto', '192.168.77.22'],
        ['bobby', '192.168.77.23'],
        ['kurnia', '192.168.77.24'],
        ['octavia', '192.168.77.25'],
        ['rujik', '192.168.77.26'],
        ['siti', '192.168.77.27'],
        ['giar', '192.168.77.28'],
        ['mendud', '192.168.77.29'],
        ['giman', '192.168.77.30'],
        ['herman', '192.168.77.31'],
        ['giyanto', '192.168.77.32'],
        ['aris', '192.168.77.33'],
        ['dukuh', '192.168.77.34'],
        ['musbani', '192.168.77.35'],
        ['andri', '192.168.77.36'],
        ['kodet', '192.168.77.37'],
        ['wahyudi', '192.168.77.38'],
        ['supri', '192.168.77.39'],
        ['toni', '192.168.77.40'],
        ['sutarno', '192.168.77.41'],
        ['ari', '192.168.77.42'],
        ['sugeng', '192.168.77.43'],
        ['kahar', '192.168.77.44'],
        ['riris', '192.168.77.45'],
        ['gimin', '192.168.77.46'],
        ['diva', '192.168.77.47'],
        ['eko', '192.168.77.48'],
        ['farida', '192.168.77.49'],
        ['retno', '192.168.77.50'],
        ['angga', '192.168.77.51'],
        ['takim', '192.168.77.52'],
        ['nuri', '192.168.77.53'],
        ['jamingan', '192.168.77.54'],
        ['yoga', '192.168.77.55'],
        ['partini', '192.168.77.56'],
        ['puput', '192.168.77.57'],
        ['putri', '192.168.77.58'],
        ['isa', '192.168.77.59'],
        ['azis ab karya', '192.168.77.60'],
        ['rizki ardi', '192.168.77.61'],
        ['apri', '192.168.77.62'],
        ['sigit', '192.168.77.63'],
        ['dika', '192.168.77.64'],
        ['amri', '192.168.77.65'],
        ['mogrong', '192.168.77.66'],
        ['dorex', '192.168.77.67'],
        ['fitri', '192.168.77.68'],
        ['eko rio', '192.168.77.69'],
        ['rowi', '192.168.77.70'],
        ['bekti', '192.168.77.71'],
    ];

    public function run(): void
    {
        $existing = Customer::orderBy('id')->get();

        foreach ($this->data as $i => [$name, $ip]) {
            if (isset($existing[$i])) {
                $existing[$i]->update(['name' => $name, 'ip_address' => $ip]);
            } else {
                Customer::create([
                    'name' => $name,
                    'ip_address' => $ip,
                    'qr_code' => Customer::generateQrCode(),
                ]);
            }
        }

        $this->command->info('Pelanggan diperbarui/ditambahkan: ' . count($this->data));
    }
}
