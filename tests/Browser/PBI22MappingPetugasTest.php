<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\ZonaWilayah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI22MappingPetugasTest extends DuskTestCase
{
    /**
     * PBI-22: Mapping Petugas ke Zona
     */
    public function test_pbi_22_mapping_petugas()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);
        
        // Pastikan ada zona
        $zona = ZonaWilayah::first() ?? ZonaWilayah::create([
            'nama_zona' => 'Zona Mapping',
            'kode_zona' => 'ZM-01',
            'is_active' => true,
        ]);
        
        // Pastikan ada petugas yang belum di-assign (nip tidak null)
        $petugas = \App\Models\Petugas::whereNull('zona_id')->first();
        if (!$petugas) {
            $user = User::factory()->create(['role' => 'petugas', 'password' => bcrypt('password')]);
            $petugas = \App\Models\Petugas::create(['user_id' => $user->id, 'nip' => 'NIP' . rand(1000,9999)]);
        }

        $this->browse(function (Browser $browser) use ($admin, $zona, $petugas) {
            $browser->loginAs($admin)
                    ->visitRoute('admin.zona.show', $zona->id)
                    ->assertSee('Detail Zona Wilayah')
                    ->check('petugas_id[]', $petugas->id)
                    ->press('Petakan Petugas Terpilih')
                    ->assertPathIs('/admin/zona/' . $zona->id)
                    ->waitForText('berhasil dipetakan', 5);
        });
    }
}
