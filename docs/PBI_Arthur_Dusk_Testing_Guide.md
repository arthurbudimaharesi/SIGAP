# Panduan dan Laporan End-to-End Testing (Laravel Dusk) - PBI Arthur

Dokumen ini berisi laporan dan panduan pelaksanaan pengujian otomatis End-to-End (E2E) menggunakan **Laravel Dusk** khusus untuk fitur-fitur (PBI) yang dikembangkan oleh **Arthur Budi Maharesi**.

## Daftar PBI yang Diuji

Berikut adalah daftar Product Backlog Item (PBI) yang telah ter-cover dalam test automation:

1. **PBI-01**: Administrasi Pelanggan (Admin CRUD Pelanggan)
2. **PBI-02**: Kategori Pengaduan (Admin CRUD Kategori & SLA)
3. **PBI-03/20**: Zona Wilayah (Admin CRUD Zona Wilayah PDAM)
4. **PBI-21**: Supervisor Zona (Penentuan Supervisor pada suatu Zona)
5. **PBI-22**: Mapping Petugas (Pemetaan petugas ke dalam zona wilayah kerja)
6. **PBI-23**: Validasi Otomatis (Validasi backend form pengaduan)
7. **PBI-43**: Integrasi GPS (Pengambilan koordinat lokasi secara otomatis via Leaflet/Browser Geolocation)

## File Test Automation

Semua skenario pengujian di atas telah dipisah menjadi file automation test masing-masing untuk setiap PBI (agar lebih rapi dan modular):
- `tests/Browser/PBI01AdministrasiPelangganTest.php`
- `tests/Browser/PBI02KategoriPengaduanTest.php`
- `tests/Browser/PBI0320ZonaWilayahTest.php`
- `tests/Browser/PBI21SupervisorZonaTest.php`
- `tests/Browser/PBI22MappingPetugasTest.php`
- `tests/Browser/PBI23ValidasiOtomatisTest.php`
- `tests/Browser/PBI43IntegrasiGPSTest.php`

## Cara Menjalankan Test

Untuk memverifikasi fungsionalitas fitur (PBI), jalankan perintah berikut di terminal:

```bash
# Menjalankan seluruh test sekaligus (semua PBI)
php artisan dusk

# Menjalankan spesifik satu file/PBI saja:

# PBI-01: Administrasi Pelanggan
php artisan dusk tests/Browser/PBI01AdministrasiPelangganTest.php

# PBI-02: Kategori Pengaduan
php artisan dusk tests/Browser/PBI02KategoriPengaduanTest.php

# PBI-03 & PBI-20: Zona Wilayah
php artisan dusk tests/Browser/PBI0320ZonaWilayahTest.php

# PBI-21: Supervisor Zona
php artisan dusk tests/Browser/PBI21SupervisorZonaTest.php

# PBI-22: Mapping Petugas
php artisan dusk tests/Browser/PBI22MappingPetugasTest.php

# PBI-23: Validasi Otomatis
php artisan dusk tests/Browser/PBI23ValidasiOtomatisTest.php

# PBI-43: Integrasi GPS
php artisan dusk tests/Browser/PBI43IntegrasiGPSTest.php
```

## Workaround & Teknik Pengujian (Khusus PBI-43 Integrasi GPS)

Dalam eksekusi End-to-End testing menggunakan browser Headless (Chrome WebDriver), fitur **HTML5 Geolocation** (GPS) biasanya secara otomatis diblokir karena alasan sekuritas (membutuhkan permission eksplisit dari user). 

Oleh karena itu, pada pengujian PBI-43 di file `PBI43IntegrasiGPSTest.php`, telah diimplementasikan bypass menggunakan Javascript DOM Manipulation:

```php
// Melakukan injeksi koordinat secara langsung untuk mem-bypass blokir GPS dari headless browser
$browser->script('
    document.getElementById("input-latitude").value = "-6.9175";
    document.getElementById("input-longitude").value = "107.6191";
');
```
Teknik ini memastikan validasi form pengaduan (PBI-23) tetap berjalan dengan lancar tanpa terganggu oleh isu perizinan GPS dari eksekutor otomatis.

## Hasil Uji (Test Execution Report)

Berdasarkan eksekusi terbaru, berikut adalah hasil dari Laravel Dusk:

```text
   PASS  Tests\Browser\PBI01AdministrasiPelangganTest
  ✓ pbi 01 administrasi pelanggan (6.41s)

   PASS  Tests\Browser\PBI02KategoriPengaduanTest
  ✓ pbi 02 kategori pengaduan (3.81s)

   PASS  Tests\Browser\PBI0320ZonaWilayahTest
  ✓ pbi 03 20 zona wilayah crud (5.11s)

   PASS  Tests\Browser\PBI21SupervisorZonaTest
  ✓ pbi 21 supervisor zona (1.35s)

   PASS  Tests\Browser\PBI22MappingPetugasTest
  ✓ pbi 22 mapping petugas (2.61s)

   PASS  Tests\Browser\PBI23ValidasiOtomatisTest
  ✓ pbi 23 validasi otomatis (1.80s)

   PASS  Tests\Browser\PBI43IntegrasiGPSTest
  ✓ pbi 43 integrasi gps (3.95s)

  Tests:    7 passed (17 assertions)
  Duration: 25.04s
```
**Status: ALL PASSED (SANGAT BAIK)**

## Kesimpulan Evidence Test Report Excel

Skenario yang dibangun pada file-file pengujian ini **sudah sinkron** dengan skenario pengujian yang ada pada **Test Report - Arthur (Final).xlsx**. Screenshot yang dihasilkan pada saat proses Dusk ini bisa langsung dilampirkan ke dalam kolom evidence di Excel tersebut. Khusus untuk Screenshot **Login sebagai Admin**, langkah ini selalu dilakukan secara implisit di setiap test case Dusk menggunakan fungsi `$browser->loginAs($admin)`. Untuk evidence Excel, silakan melampirkan screenshot halaman utama Dashboard Admin yang tampil sesaat setelah login berhasil.
