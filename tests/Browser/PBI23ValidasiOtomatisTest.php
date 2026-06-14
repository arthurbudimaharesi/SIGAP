<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\KategoriPengaduan;
use App\Models\ZonaWilayah;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI23ValidasiOtomatisTest extends DuskTestCase
{
    /**
     * PBI-23: Validasi Otomatis
     */
    public function test_pbi_23_validasi_otomatis()
    {
        $masyarakat = User::where('role', 'masyarakat')->first() ?? User::factory()->create(['role' => 'masyarakat', 'password' => bcrypt('password')]);
        
        $this->browse(function (Browser $browser) use ($masyarakat) {
            $browser->loginAs($masyarakat)
                    ->visitRoute('masyarakat.pengaduan.create')
                    ->assertSee('Lokasi Pengaduan')
                    
                    // Langsung coba submit tanpa isi form (Test Validasi Otomatis Mandatori)
                    ->press('Kirim Pengaduan')
                    ->pause(1000)
                    
                    // Pastikan tetap berada di halaman form pengaduan
                    ->assertPathIs('/masyarakat/pengaduan/create');
                    
                    // Catatan: Jika ada HTML5 'required' attribute, form bahkan tidak akan tersubmit dan tidak ada session error khusus.
                    // Namun kita sudah memvalidasi bahwa ia tidak berpindah halaman (gagal disubmit).
        });
    }
}
