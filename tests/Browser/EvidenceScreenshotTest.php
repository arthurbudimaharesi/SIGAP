<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EvidenceScreenshotTest extends DuskTestCase
{
    public function test_capture_evidence()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);
        $supervisor = User::where('role', 'supervisor')->first() ?? User::factory()->create(['role' => 'supervisor', 'password' => bcrypt('password')]);
        $masyarakat = User::where('role', 'masyarakat')->first() ?? User::factory()->create(['role' => 'masyarakat', 'password' => bcrypt('password')]);

        $this->browse(function (Browser $browser) use ($admin, $supervisor, $masyarakat) {
            
            // Login Page & Dashboard
            $browser->visit('/login')
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->press('Masuk')
                    ->acceptDialog()
                    ->pause(1500)
                    ->assertPathIs('/admin/dashboard')
                    ->screenshot('SS_Login');

            // PBI-01: Administrasi Pelanggan
            $browser->loginAs($admin)
                    ->visitRoute('admin.pelanggan.create')
                    ->pause(1500)
                    ->screenshot('SS_PBI-01_Pelanggan_Index');
            
            // PBI-02: Kategori Pengaduan
            $browser->visitRoute('admin.kategori.create')
                    ->pause(1500)
                    ->screenshot('SS_PBI-02_Kategori_Index');

            // PBI-03, 20: Zona Wilayah (Form Create)
            $browser->visitRoute('admin.zona.create')
                    ->pause(1500)
                    ->screenshot('SS_PBI-03_PBI-20_Zona_Index');

            // PBI-22: Mapping Petugas (Index view)
            $browser->visitRoute('admin.zona.index')
                    ->pause(1500)
                    ->screenshot('SS_PBI-22_Mapping_Petugas');

            $browser->logout();

            // PBI-21: Pengelolaan Zona oleh Supervisor
            $browser->loginAs($supervisor)
                    ->visit('/supervisor/zona')
                    ->pause(1500)
                    ->screenshot('SS_PBI-21_Supervisor_Zona');

            $browser->logout();

            // PBI-23: Validasi Wilayah Otomatis
            $browser->loginAs($masyarakat)
                    ->visitRoute('masyarakat.pengaduan.create')
                    ->pause(1500)
                    ->screenshot('SS_PBI-23_Validasi_Wilayah');

            // PBI-43: Integrasi GPS (Scroll to map)
            $browser->script("document.getElementById('map').scrollIntoView({behavior: 'instant', block: 'center'});");
            $browser->pause(1000)
                    ->click('#btn-gps')
                    ->pause(2000)
                    ->screenshot('SS_PBI-43_Integrasi_GPS');
        });
    }
}
