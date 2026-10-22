<?php

use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);

    foreach ([['Budi Santoso', true], ['Siti Aminah', true], ['Eko Prasetyo', false]] as [$name, $active]) {
        Customer::create([
            'name' => $name,
            'is_active' => $active,
            'qr_code' => Customer::generateQrCode(),
        ]);
    }
});

it('menampilkan semua pelanggan tanpa filter', function () {
    actingAs($this->admin);

    get('/admin/customers')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Customers/Index')
            ->has('customers.data', 3));
});

it('memfilter pelanggan berdasarkan search', function () {
    actingAs($this->admin);

    get('/admin/customers?search=Budi')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('customers.data', 1)
            ->where('customers.data.0.name', 'Budi Santoso')
            ->where('filters.search', 'Budi'));
});

it('memfilter pelanggan berdasarkan status aktif', function () {
    actingAs($this->admin);

    get('/admin/customers?status=1')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('customers.data', 2)
            ->where('filters.status', '1'));
});

it('memfilter pelanggan berdasarkan status nonaktif', function () {
    actingAs($this->admin);

    get('/admin/customers?status=0')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('customers.data', 1)
            ->where('customers.data.0.name', 'Eko Prasetyo'));
});
