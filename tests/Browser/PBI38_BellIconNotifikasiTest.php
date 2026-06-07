<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI38_BellIconNotifikasiTest extends DuskTestCase
{
    /**
     * Testing keberadaan dan fungsionalitas Bell Icon Notifikasi (Frontend).
     *
     * @return void
     */
    public function test_bell_icon_and_notification_badge()
    {
        $this->browse(function (Browser $browser) {
            // Kita asumsikan ada user admin
            $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);

            $browser->loginAs($admin)
                    ->visit('/admin/dashboard')
                    ->pause(1000)
                    // Pastikan berada di halaman panel admin
                    ->assertSee('Panel Admin')
                    
                    // 1. Memastikan Bell Icon muncul di Navbar
                    // Icon menggunakan SVG, tapi kita bisa pastikan elemen x-data terkait ada
                    ->assertPresent('button[x-show="unreadCount > 0"]', 'Badge notifikasi tidak ditemukan')
                    
                    // 2. Memastikan Badge Merah (Unread Count) Muncul dan menampilkan angka (default 5 dari Alpine.js)
                    ->assertSeeIn('button[x-show="unreadCount > 0"]', '5')
                    
                    // 3. Klik Bell Icon untuk membuka Dropdown Notifikasi
                    ->click('button[x-show="unreadCount > 0"]') // Atau klik area button loncengnya
                    ->pause(500)
                    
                    // 4. Memastikan isi dropdown Notifikasi muncul
                    ->assertSee('Notifikasi')
                    ->assertSee('Pengaduan menunggu verifikasi')
                    ->assertSee('Lihat Semua Notifikasi');
                    
            // 5. Test close ketika klik di luar (body)
            $browser->click('body')
                    ->pause(500)
                    ->assertDontSee('Lihat Semua Notifikasi'); // Dropdown tertutup
        });
    }
}
