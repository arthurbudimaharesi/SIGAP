@php
    $role = auth()->user()?->role;
    $menus = match($role) {
        'admin' => [
            ['label' => 'Dashboard',  'route' => 'admin.dashboard',     'icon' => 'home'],
            ['label' => 'Pengaduan',  'route' => 'admin.pengaduan.index', 'icon' => 'search'],
            ['label' => 'User',       'route' => 'admin.user.index',     'icon' => 'group'],
            ['label' => 'Petugas',    'route' => 'admin.petugas.index',  'icon' => 'engineering'],
            ['label' => 'Pelanggan',  'route' => 'admin.pelanggan.index','icon' => 'house'],
            ['label' => 'Kategori',   'route' => 'admin.kategori.index', 'icon' => 'label'],
            ['label' => 'Zona',       'route' => 'admin.zona.index',     'icon' => 'map'],
            ['label' => 'Konfigurasi SLA', 'route' => 'admin.sla.index', 'icon' => 'timer'],
            ['label' => 'Kinerja',    'route' => 'admin.kinerja.index',  'icon' => 'bar_chart'],
        ],
        'supervisor' => [
            ['label' => 'Dashboard',  'route' => 'supervisor.dashboard',       'icon' => 'home'],
            ['label' => 'Verifikasi', 'route' => 'supervisor.verifikasi.index','icon' => 'check_circle'],
            ['label' => 'Filter Pengaduan','route' => 'supervisor.filter.index','icon'=> 'search'],
            ['label' => 'Laporan',    'route' => 'supervisor.laporan.index',   'icon' => 'description'],
        ],
        'petugas' => [
            ['label' => 'Dashboard',  'route' => 'petugas.dashboard',   'icon' => 'home'],
            ['label' => 'Tugas Aktif','route' => 'petugas.tugas.index', 'icon' => 'build'],
            ['label' => 'Profil',     'route' => 'petugas.profil.edit', 'icon' => 'person'],
        ],
        'masyarakat' => [
            ['label' => 'Dashboard',    'route' => 'masyarakat.dashboard',      'icon' => 'home'],
            ['label' => 'Buat Pengaduan','route' => 'masyarakat.pengaduan.create','icon'=> 'list_alt'],
            ['label' => 'Riwayat',      'route' => 'masyarakat.pengaduan.riwayat',  'icon' => 'history'],
            ['label' => 'Notifikasi',   'route' => 'masyarakat.notifikasi.index','icon'=> 'notifications'],
        ],
        default => [],
    };
@endphp

<aside class="w-56 bg-gray-900 min-h-screen pt-16 flex-shrink-0 fixed left-0 top-0 bottom-0 z-40">
    <div class="py-4">
        @foreach ($menus as $menu)
            @php
                $isActive = request()->routeIs(rtrim($menu['route'], '.index') . '*');
            @endphp
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-3 px-5 py-3 text-sm transition
                       {{ $isActive
                           ? 'bg-blue-700 text-white font-semibold border-r-4 border-blue-400'
                           : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <span class="material-symbols-outlined text-base">{{ $menu['icon'] }}</span>
                <span>{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </div>
</aside>
