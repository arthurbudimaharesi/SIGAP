<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

for ($i = 0; $i < 5; $i++) {
    $pengaduan = \App\Models\Pengaduan::create([
        'user_id' => 3,
        'kategori_id' => 1,
        'zona_id' => 1,
        'nomor_tiket' => 'TKT-DUMMY-' . rand(1000, 9999) . $i,
        'judul' => 'Dummy Overload ' . $i,
        'deskripsi' => 'Dummy',
        'lokasi' => 'Dummy',
        'status' => 'diproses',
    ]);
    
    \App\Models\Assignment::create([
        'pengaduan_id' => $pengaduan->id,
        'petugas_id' => 14,
        'supervisor_id' => 2,
        'jadwal_penanganan' => now(),
        'instruksi' => 'Dummy load',
        'status_assignment' => 'ditugaskan'
    ]);
}

for ($i = 0; $i < 3; $i++) {
    $pengaduan = \App\Models\Pengaduan::create([
        'user_id' => 3,
        'kategori_id' => 1,
        'zona_id' => 1,
        'nomor_tiket' => 'TKT-DUMMY-X' . rand(1000, 9999) . $i,
        'judul' => 'Dummy Sedang ' . $i,
        'deskripsi' => 'Dummy',
        'lokasi' => 'Dummy',
        'status' => 'diproses',
    ]);
    
    \App\Models\Assignment::create([
        'pengaduan_id' => $pengaduan->id,
        'petugas_id' => 8,
        'supervisor_id' => 2,
        'jadwal_penanganan' => now(),
        'instruksi' => 'Dummy load',
        'status_assignment' => 'ditugaskan'
    ]);
}

echo "Dummy assignments created!\n";
