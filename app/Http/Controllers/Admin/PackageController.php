<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PackageController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Package::withCount('customers')->orderBy('name');

        $packages = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Packages/Index', [
            'admin' => $this->adminPayload($request),
            'packages' => $packages,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Package::create($validated);

        return back()->with('success', 'Paket berhasil ditambahkan.');
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $package->update($validated);

        return back()->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        // Lepas relasi dari customers sebelum hapus
        $package->customers()->update(['package_id' => null]);
        $package->delete();

        return back()->with('success', 'Paket berhasil dihapus.');
    }

    private function adminPayload(Request $request): array
    {
        $admin = $request->user();
        return [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
        ];
    }
}
