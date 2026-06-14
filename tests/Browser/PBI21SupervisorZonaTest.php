<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI21SupervisorZonaTest extends DuskTestCase
{
    /**
     * PBI-21: Supervisor Pengelolaan Zona
     */
    public function test_pbi_21_supervisor_zona()
    {
        $supervisor = User::where('role', 'supervisor')->first() ?? User::factory()->create(['role' => 'supervisor', 'password' => bcrypt('password')]);

        $this->browse(function (Browser $browser) use ($supervisor) {
            $browser->loginAs($supervisor)
                    ->visit('/supervisor/zona')
                    ->assertSee('Zona Wilayah')
                    ->assertSee('Mode Lihat Saja');
        });
    }
}
