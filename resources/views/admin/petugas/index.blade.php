<x-app-admin-layout>
    <x-slot name="title">Manajemen Petugas</x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Manajemen Petugas Teknis</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data petugas teknis, status ketersediaan, dan histori penugasan.</p>
        </div>
        <a href="{{ route('admin.petugas.create') }}" class="inline-flex items-center gap-2 bg-[#022448] text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1e3a5f] transition-all duration-200 shadow-sm hover:shadow">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Tambah Petugas
        </a>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <span class="material-symbols-outlined">badge</span>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Petugas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>
        <!-- Tersedia -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Tersedia</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['tersedia'] }}</p>
            </div>
        </div>
        <!-- Sibuk -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                <span class="material-symbols-outlined">more_horiz</span>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Sibuk</p>
                <p class="text-2xl font-bold text-orange-500">{{ $stats['sibuk'] }}</p>
            </div>
        </div>
        <!-- Tidak Aktif -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                <span class="material-symbols-outlined">cancel</span>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Tidak Aktif</p>
                <p class="text-2xl font-bold text-gray-500">{{ $stats['tidak_aktif'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.petugas.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-5">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cari Petugas</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, atau NIP..." class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="sibuk" {{ request('status') == 'sibuk' ? 'selected' : '' }}>Sibuk</option>
                    <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Zona</label>
                <select name="zona" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    <option value="">Semua Zona</option>
                    @foreach($zonas as $z)
                        <option value="{{ $z->id }}" {{ request('zona') == $z->id ? 'selected' : '' }}>{{ $z->nama_zona }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <button type="submit" class="w-full bg-[#022448] text-white rounded-xl py-2.5 text-sm font-semibold hover:bg-[#1e3a5f] transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Petugas</th>
                        <th class="px-6 py-4">NIP</th>
                        <th class="px-6 py-4">Zona</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($petugas as $index => $p)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $petugas->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($p->user->foto_profil)
                                        <img src="{{ Storage::url($p->user->foto_profil) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                            {{ substr($p->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $p->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $p->user->email }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $p->user->no_telepon ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">
                                    # {{ $p->nip }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-purple-600">
                                    <span class="material-symbols-outlined text-[18px]">map</span>
                                    <span class="font-medium text-xs">{{ $p->zona?->nama_zona ?? 'Belum ada zona' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($p->status_tersedia === 'tersedia')
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                        <span class="text-green-700 font-medium text-xs">Tersedia</span>
                                    @elseif($p->status_tersedia === 'sibuk')
                                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                        <span class="text-orange-700 font-medium text-xs">Sibuk</span>
                                    @else
                                        <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                        <span class="text-gray-600 font-medium text-xs">Tidak Aktif</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </button>
                                    <a href="{{ route('admin.petugas.edit', $p) }}" class="p-1.5 text-orange-500 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.petugas.destroy', $p) }}" onsubmit="return confirm('Nonaktifkan petugas ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Nonaktifkan">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <span class="material-symbols-outlined text-4xl mb-3 text-gray-300">group_off</span>
                                    <p class="text-base font-medium text-gray-900">Belum ada data petugas</p>
                                    <p class="text-sm mt-1">Silakan tambahkan petugas baru atau ubah filter pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($petugas->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $petugas->links() }}
            </div>
        @endif
    </div>
</x-app-admin-layout>
