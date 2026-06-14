<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI0320ZonaWilayahTest extends DuskTestCase
{
    /**
     * PBI-03/20: Zona Wilayah CRUD
     */
    public function test_pbi_03_20_zona_wilayah_crud()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);

        $this->browse(function (Browser $browser) use ($admin) {
            $kodeZona = 'ZNA-' . rand(1000, 9999);
            $geoBoundary = '{"type":"Polygon","coordinates":[[[107.61,-6.91],[107.62,-6.91],[107.62,-6.92],[107.61,-6.92],[107.61,-6.91]]]}';

            // Create
            $browser->loginAs($admin)
                    ->visitRoute('admin.zona.create')
                    ->assertSee('Tambah Zona Wilayah')
                    ->type('nama_zona', 'Zona Test ' . $kodeZona)
                    ->type('kode_zona', $kodeZona)
                    ->type('deskripsi', 'Deskripsi zona test')
                    ->script("document.getElementById('geo_boundary').value = '{$geoBoundary}';");
                    
            $browser->press('Simpan Zona')
                    ->pause(1000)
                    ->assertPathIs('/admin/zona')
                    ->waitForText('berhasil', 5);
        });
    }
}
