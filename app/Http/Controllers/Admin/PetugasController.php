<?php
/**
 * PBI-17 — Manajemen Petugas Teknis
 * Admin dapat membuat akun petugas, assign ke zona, dan mengatur status ketersediaan.
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Petugas, ZonaWilayah};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $query = Petugas::with(['user', 'zona'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'petugas');
            });

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_tersedia', $request->status);
        }

        // Filter zona
        if ($request->filled('zona')) {
            $query->where('zona_id', $request->zona);
        }

        $petugas = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Petugas::count(),
            'tersedia' => Petugas::where('status_tersedia', 'tersedia')->count(),
            'sibuk' => Petugas::where('status_tersedia', 'sibuk')->count(),
            'tidak_aktif' => Petugas::where('status_tersedia', 'tidak_aktif')->count(),
        ];

        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();

        return view('admin.petugas.index', compact('petugas', 'stats', 'zonas'));
    }

    public function create()
    {
        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        return view('admin.petugas.form', compact('zonas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required|string|min:8|confirmed',
            'no_telepon'           => 'nullable|string|max:20',
            'nip'                  => 'required|string|unique:petugas,nip',
            'status_tersedia'      => 'required|in:tersedia,sibuk,tidak_aktif',
            'zona_id'              => 'required|exists:zona_wilayah,id',
        ], [
            'email.unique'          => 'Email sudah digunakan.',
            'nip.unique'            => 'NIP sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat user dengan role petugas
            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'petugas',
                'no_telepon' => $request->no_telepon,
                'is_active'  => true,
            ]);

            // 2. Buat record petugas
            Petugas::create([
                'user_id'              => $user->id,
                'nip'                  => $request->nip,
                'status_tersedia'      => $request->status_tersedia,
                'zona_id'              => $request->zona_id,
            ]);
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(Petugas $petugas)
    {
        $petugas->load(['user', 'zona']);
        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        return view('admin.petugas.form', compact('petugas', 'zonas'));
    }

    public function update(Request $request, Petugas $petugas)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $petugas->user_id,
            'no_telepon'          => 'nullable|string|max:20',
            'nip'                 => 'required|string|unique:petugas,nip,' . $petugas->id,
            'status_tersedia'     => 'required|in:tersedia,sibuk,tidak_aktif',
            'zona_id'             => 'required|exists:zona_wilayah,id',
            'password'            => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, $petugas) {
            $userData = [
                'name'       => $request->name,
                'email'      => $request->email,
                'no_telepon' => $request->no_telepon,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $petugas->user->update($userData);
            $petugas->update([
                'nip'                 => $request->nip,
                'status_tersedia'     => $request->status_tersedia,
                'zona_id'             => $request->zona_id,
            ]);
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(Petugas $petugas)
    {
        // Cegah hapus petugas yang punya tugas aktif
        $adalahAktif = $petugas->assignments()
            ->whereIn('status_assignment', ['ditugaskan', 'sedang_diproses'])
            ->exists();

        if ($adalahAktif) {
            return back()->withErrors(['error' => 'Petugas tidak dapat dihapus karena masih memiliki tugas aktif.']);
        }

        DB::transaction(function () use ($petugas) {
            $petugas->user->update(['is_active' => false]);
            $petugas->update(['status_tersedia' => 'tidak_aktif', 'zona_id' => null]);
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil dinonaktifkan.');
    }
}
