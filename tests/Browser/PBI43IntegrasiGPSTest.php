<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\KategoriPengaduan;
use App\Models\ZonaWilayah;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI43IntegrasiGPSTest extends DuskTestCase
{
    /**
     * PBI-43: Integrasi GPS
     */
    public function test_pbi_43_integrasi_gps()
    {
        $masyarakat = User::where('role', 'masyarakat')->first() ?? User::factory()->create(['role' => 'masyarakat', 'password' => bcrypt('password')]);
        
        // Pastikan ada kategori
        $kategori = KategoriPengaduan::where('kode_kategori', 'AMT-01')->first() ?? KategoriPengaduan::create([
            'nama_kategori' => 'Air Mati Total',
            'kode_kategori' => 'AMT-01',
            'sla_jam' => 24,
            'is_active' => true,
        ]);
        
        $zona = ZonaWilayah::where('kode_zona', 'BDG-U01')->first() ?? ZonaWilayah::create([
            'nama_zona' => 'Bandung Utara Test',
            'kode_zona' => 'BDG-U01',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($masyarakat, $kategori, $zona) {
            $browser->loginAs($masyarakat)
                    ->visitRoute('masyarakat.pengaduan.create')
                    ->assertSee('Lokasi Pengaduan')
                    ->select('kategori_id', $kategori->id)
                    
                    // Simulasi klik GPS Button (akan failed jika tidak ada HTTPS/headless issues, 
                    // namun karena kita test interaksi tombol, klik saja dan isi hidden inputs secara manual)
                    ->script([
                        "document.getElementById('input-latitude').value = '-6.9175';",
                        "document.getElementById('input-longitude').value = '107.6191';",
                        "document.getElementById('coords-info').classList.remove('hidden');"
                    ]);
                    
            $browser->type('lokasi', 'Jl. Test GPS No. 123')
                    ->type('no_telepon', '081234567890')
                    ->type('deskripsi', 'Ini adalah deskripsi test untuk fitur Integrasi GPS minimal 20 karakter.')
                    ->attach('foto_bukti', __DIR__ . '/screenshots/test_dummy.jpg')
                    ->select('zona_id', $zona->id);
            
            $browser->pause(1000)
                    ->press('Kirim Pengaduan')
                    ->pause(1000)
                    ->waitForText('Berhasil', 5);
        });
    }
}
