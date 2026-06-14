<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI02KategoriPengaduanTest extends DuskTestCase
{
    /**
     * PBI-02: Kategori Pengaduan
     */
    public function test_pbi_02_kategori_pengaduan()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);

        $this->browse(function (Browser $browser) use ($admin) {
            $kodeUnik = 'KTG-' . rand(100, 999);
            
            $browser->loginAs($admin)
                    ->visitRoute('admin.kategori.create')
                    ->assertSee('Tambah Kategori Pengaduan')
                    ->type('nama_kategori', 'Kategori Test ' . $kodeUnik)
                    ->type('kode_kategori', $kodeUnik)
                    ->type('sla_jam', '48')
                    ->type('deskripsi', 'Ini adalah deskripsi kategori test dari Dusk')
                    ->press('Simpan Kategori')
                    ->pause(1000)
                    ->assertPathIs('/admin/kategori')
                    ->waitForText('berhasil', 5);
        });
    }
}
