{{-- PBI-12 Daftar Notifikasi --}}
@php
    $layout = 'app-layout';
    if (auth()->check()) {
        switch (auth()->user()->role) {
            case 'admin':
                $layout = 'app-admin-layout';
                break;
            case 'supervisor':
                $layout = 'app-supervisor-layout';
                break;
            case 'petugas':
                $layout = 'app-petugas-layout';
                break;
            case 'masyarakat':
                $layout = 'app-masyarakat-layout';
                break;
        }
    }
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="title">Notifikasi</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#022448] text-3xl">notifications</span>
            Notifikasi
        </h1>
        @if ($notifikasis->where('is_read', false)->count() > 0)
        <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
            @csrf
            <button type="submit" class="text-sm text-blue-600 hover:underline font-medium">Tandai semua dibaca</button>
        </form>
        @endif
    </div>

    <div class="space-y-3">
        @forelse ($notifikasis as $notif)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-start gap-4 transition-all hover:shadow-md
            {{ !$notif->is_read ? 'border-l-4 border-l-blue-500 bg-blue-50/30' : 'opacity-70' }}">
            <div class="mt-1">
                <span class="material-symbols-outlined text-2xl {{ !$notif->is_read ? 'text-blue-500' : 'text-gray-400' }}">notifications</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $notif->judul }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $notif->pesan }}</p>
                        @if ($notif->pengaduan)
                            @php
                                $route = 'masyarakat.pengaduan.riwayat.show';
                                if(auth()->user()->role === 'admin') $route = 'admin.pengaduan.index';
                                elseif(auth()->user()->role === 'supervisor') $route = 'supervisor.pengaduan.show';
                                elseif(auth()->user()->role === 'petugas') $route = 'petugas.tugas.show';
                            @endphp
                            <a href="{{ route($route, $notif->pengaduan) }}"
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline mt-2 inline-block">Lihat Pengaduan &rarr;</a>
                        @endif
                    </div>
                    <div class="text-left sm:text-right flex-shrink-0 flex flex-col sm:items-end gap-1">
                        <p class="text-xs text-gray-500 font-medium">{{ $notif->created_at->diffForHumans() }}</p>
                        @if (!$notif->is_read)
                        <form method="POST" action="{{ route('notifikasi.baca', $notif->id) }}">
                            @csrf
                            <button type="submit" class="text-xs px-3 py-1 mt-1 bg-white border border-blue-200 text-blue-600 rounded hover:bg-blue-50 transition-colors">Tandai dibaca</button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400 flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[14px]">done_all</span> Dibaca
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-3">notifications_off</span>
            <p class="text-gray-500 font-medium">Belum ada notifikasi</p>
        </div>
        @endforelse
    </div>

    @if ($notifikasis->hasPages())
    <div class="mt-6">{{ $notifikasis->links() }}</div>
    @endif
</x-dynamic-component>
