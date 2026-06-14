<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\KategoriPengaduan;
use App\Models\ZonaWilayah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI01AdministrasiPelangganTest extends DuskTestCase
{
    /**
     * PBI-01: Administrasi Pelanggan
     */
    public function test_pbi_01_administrasi_pelanggan()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);
        $zona = ZonaWilayah::first() ?? ZonaWilayah::create([
            'nama_zona' => 'Zona Alpha',
            'kode_zona' => 'ZA-01',
            'is_active' => true,
        ]);
        $kategori = KategoriPengaduan::first() ?? KategoriPengaduan::create([
            'nama_kategori' => 'Pipa Bocor',
            'kode_kategori' => 'PB-01',
            'sla_jam' => 24,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $zona, $kategori) {
            $browser->loginAs($admin)
                    ->visitRoute('admin.pelanggan.create')
                    ->assertSee('Tambah Pelanggan Baru')
                    ->type('nama_pelanggan', 'John Doe ' . rand(100, 999))
                    ->type('nomor_sambungan', 'SIGAP-' . rand(10000000, 99999999))
                    ->select('zona_id', $zona->id)
                    ->type('alamat', 'Jl. Merdeka No 10')
                    ->type('no_telepon', '081234567890')
                    ->select('kategori_id', $kategori->id)
                    ->type('deskripsi', 'Air tidak mengalir sejak pagi hari ini tolong segera diperbaiki.')
                    ->attach('foto_bukti', __DIR__ . '/screenshots/test_dummy.jpg')
                    ->press('Simpan Data')
                    ->pause(1000)
                    ->acceptDialog()
                    ->assertPathIs('/admin/pelanggan')
                    ->waitForText('berhasil', 5);
        });
    }
}
