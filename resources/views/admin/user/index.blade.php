<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data akun pengguna, role, dan hak akses ke sistem.</p>
        </div>
        <a href="{{ route('admin.user.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">person_add</span>
            Tambah User
        </a>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm shadow-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm shadow-sm">
        <span class="material-symbols-outlined text-red-500 flex-shrink-0">error</span>
        {{ $errors->first() }}
    </div>
@endif

{{-- Summary Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-gray-700 text-xl">groups</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total User</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-xl">shield_person</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Admin</p>
            <p class="text-xl font-bold text-purple-700">{{ $stats['admin'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-indigo-600 text-xl">supervisor_account</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Supervisor</p>
            <p class="text-xl font-bold text-indigo-700">{{ $stats['supervisor'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 text-xl">engineering</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Petugas</p>
            <p class="text-xl font-bold text-blue-700">{{ $stats['petugas'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-gray-500 text-xl">person</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Masyarakat</p>
            <p class="text-xl font-bold text-gray-600">{{ $stats['masyarakat'] }}</p>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.user.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari User</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama atau email..."
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448]">
        </div>
        <div class="min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
            <select name="role" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448]">
                <option value="">Semua Role</option>
                <option value="admin"      {{ request('role') === 'admin'      ? 'selected' : '' }}>Admin</option>
                <option value="supervisor" {{ request('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                <option value="petugas"    {{ request('role') === 'petugas'    ? 'selected' : '' }}>Petugas</option>
                <option value="masyarakat" {{ request('role') === 'masyarakat' ? 'selected' : '' }}>Masyarakat</option>
            </select>
        </div>
        <div class="min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448]">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#022448] text-white text-sm font-semibold rounded-lg hover:bg-[#033466] transition">
                <span class="material-symbols-outlined text-base">filter_list</span>
                Filter
            </button>
            @if(request()->hasAny(['search','role','status']))
                <a href="{{ route('admin.user.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined text-base">close</span>
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Data Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
        <p class="text-xs text-gray-500">
            Menampilkan <strong class="text-gray-700">{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</strong>
            dari <strong class="text-gray-700">{{ $users->total() }}</strong> pengguna
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Pengguna</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Role</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($users as $index => $user)
                    @php
                        $roleColors = [
                            'admin' => ['bg-purple-50 text-purple-700', 'shield_person'],
                            'supervisor' => ['bg-indigo-50 text-indigo-700', 'supervisor_account'],
                            'petugas' => ['bg-blue-50 text-blue-700', 'engineering'],
                            'masyarakat' => ['bg-gray-100 text-gray-600', 'groups']
                        ];
                        $rc = $roleColors[$user->role] ?? ['bg-gray-100 text-gray-600', 'person'];
                    @endphp
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150 {{ !$user->is_active ? 'opacity-75' : '' }}">
                        <td class="px-6 py-4 text-gray-500 font-medium">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 flex-shrink-0" alt="Foto">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-[#022448]/10 flex items-center justify-center flex-shrink-0 border border-transparent">
                                        <span class="material-symbols-outlined text-[#022448] text-base">person</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $rc[0] }} rounded-full text-xs font-semibold capitalize">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $rc[1] }}</span>
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">cancel</span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.user.edit', $user) }}"
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.user.reset-password', $user) }}" style="display:inline;">
                                    @csrf
                                    <button type="button"
                                            onclick="if(confirm('Reset password user ini ke \'password\'?')) { this.closest('form').submit(); }"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer"
                                            title="Reset Password">
                                        <span class="material-symbols-outlined text-xl" style="pointer-events:none;">lock_reset</span>
                                    </button>
                                </form>
                                @if ($user->id !== auth()->id() && $user->is_active)
                                    <form method="POST" action="{{ route('admin.user.destroy', $user) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="if(confirm('Nonaktifkan user ini? User tidak akan bisa login ke sistem.')) { this.closest('form').submit(); }"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                                title="Nonaktifkan">
                                            <span class="material-symbols-outlined text-xl" style="pointer-events:none;">person_off</span>
                                        </button>
                                    </form>
                                @elseif ($user->id === auth()->id())
                                    <span class="p-2 text-gray-300 cursor-not-allowed" title="Anda tidak dapat menonaktifkan akun sendiri">
                                        <span class="material-symbols-outlined text-xl">person_off</span>
                                    </span>
                                @else
                                    <span class="p-2 text-gray-300 cursor-not-allowed" title="Sudah nonaktif">
                                        <span class="material-symbols-outlined text-xl">person_off</span>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-gray-300 text-3xl">groups</span>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada data user</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah User" untuk mendaftarkan akun baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $users->links() }}
        </div>
    @endif
</div>
</x-app-admin-layout>
