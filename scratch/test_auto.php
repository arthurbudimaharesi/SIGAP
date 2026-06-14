<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = app(App\Services\AssignmentService::class);
// Get a random pengaduan in menunggu_verifikasi status or just create one
$p = App\Models\Pengaduan::where('status', 'menunggu_verifikasi')->first();
if (!$p) {
    echo "Tidak ada pengaduan menunggu_verifikasi\n";
    exit;
}

$supervisor = App\Models\User::where('role', 'supervisor')->first();


echo "Testing autoAssign for Pengaduan #" . $p->nomor_tiket . "\n";
try {
    $assigned = $s->autoAssign($p, $supervisor);
    if ($assigned) {
        echo 'Sukses assign ke ' . $assigned->petugas->user->name . " (Total Aktif: " . $assigned->petugas->assignmentsAktif()->count() . ")\n";
    } else {
        echo 'Gagal assign, tidak ada petugas tersedia di zona ' . $p->zona_id . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
