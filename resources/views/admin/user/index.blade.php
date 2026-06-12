<x-app-admin-layout>
    <x-slot name="title">Manajemen User</x-slot>

    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data akun pengguna, role, dan hak akses ke sistem.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#022448] to-[#0A3D73] text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">person_add</span>
            Tambah User
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-gray-500">
                <span class="material-symbols-outlined text-sm">groups</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Total User</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-purple-600">
                <span class="material-symbols-outlined text-sm">shield_person</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Admin</span>
            </div>
            <p class="text-3xl font-bold text-purple-700">{{ $stats['admin'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-blue-600">
                <span class="material-symbols-outlined text-sm">manage_accounts</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Supervisor</span>
            </div>
            <p class="text-3xl font-bold text-blue-700">{{ $stats['supervisor'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-sky-600">
                <span class="material-symbols-outlined text-sm">engineering</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Petugas</span>
            </div>
            <p class="text-3xl font-bold text-sky-700">{{ $stats['petugas'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-emerald-600">
                <span class="material-symbols-outlined text-sm">person</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Masyarakat</span>
            </div>
            <p class="text-3xl font-bold text-emerald-700">{{ $stats['masyarakat'] }}</p>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="p-4 flex flex-col lg:flex-row gap-4 flex-wrap"
              x-data="{
                  selectedRole: '{{ request('role') }}',
                  zonaId: '{{ request('zona_id') }}',
                  showZona() { return this.selectedRole === 'petugas' || this.selectedRole === ''; },
                  onRoleChange() { if (!this.showZona()) { this.zonaId = ''; } }
              }">
            {{-- Search --}}
            <div class="flex-1 min-w-56">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cari User</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-lg">search</span>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all"
                           placeholder="Nama, email, atau username...">
                </div>
            </div>

            {{-- Role --}}
            <div class="lg:w-44">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Role</label>
                <select name="role" x-model="selectedRole" @change="onRoleChange()"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all bg-white">
                    <option value="">Semua Role</option>
                    @foreach(['admin', 'supervisor', 'petugas', 'masyarakat'] as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Zona — hanya muncul saat Role = Petugas atau Semua --}}
            <div class="lg:w-44" x-show="showZona()" x-transition>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    <span class="material-symbols-outlined text-xs align-middle">location_on</span>
                    Zona Lokasi
                </label>
                <select name="zona_id" x-model="zonaId"
                        :disabled="!showZona()"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all bg-white disabled:opacity-50">
                    <option value="">Semua Zona</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nama_zona }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="lg:w-44">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                <select name="is_active"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all bg-white">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="h-[42px] px-6 bg-[#022448] text-white rounded-xl text-sm font-semibold hover:bg-[#0A3D73] transition-colors shadow-sm inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role', 'zona_id', 'is_active']))
                    <a href="{{ route('admin.users.index') }}"
                       class="h-[42px] px-4 flex items-center gap-1 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-semibold transition-colors">
                        <span class="material-symbols-outlined text-sm">close</span>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Main Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Role</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Zona</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $index => $user)
                        @php
                            $roleConfig = [
                                'admin'      => ['bg-purple-100 text-purple-700', 'shield_person'],
                                'supervisor' => ['bg-blue-100 text-blue-700', 'manage_accounts'],
                                'petugas'    => ['bg-sky-100 text-sky-700', 'engineering'],
                                'masyarakat' => ['bg-emerald-100 text-emerald-700', 'person'],
                            ];
                            $rc = $roleConfig[$user->role] ?? ['bg-gray-100 text-gray-700', 'person'];
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150 {{ !$user->is_active ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $users->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->foto_profil)
                                        <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0 bg-white" alt="Foto">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0 border border-gray-200">
                                            <span class="material-symbols-outlined text-gray-400 text-xl">person</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md font-mono font-medium border border-gray-200">{{ $user->username ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $rc[0] }} rounded-full text-xs font-bold capitalize">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $rc[1] }}</span>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-xs font-medium">
                                @if ($user->role === 'petugas' && $user->petugas?->zona)
                                    <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">
                                        <span class="material-symbols-outlined text-gray-400 text-[14px]">location_on</span>
                                        {{ $user->petugas->zona->nama_zona }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-bold border border-red-100">
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">cancel</span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs font-medium">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="w-8 h-8 flex items-center justify-center text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    {{-- Tombol buka modal reset password --}}
                                    <button type="button"
                                            @click="$dispatch('open-reset-modal', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', url: '{{ route('admin.users.reset-password', $user) }}' })"
                                            class="w-8 h-8 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors cursor-pointer"
                                            title="Reset Password">
                                        <span class="material-symbols-outlined text-lg">lock_reset</span>
                                    </button>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline-block">
                                            @csrf
                                            @if ($user->is_active)
                                                <button type="button"
                                                        onclick="if(confirm('Nonaktifkan user ini? User tidak akan bisa login ke sistem.')) { this.closest('form').submit(); }"
                                                        class="w-8 h-8 flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                                                        title="Nonaktifkan">
                                                    <span class="material-symbols-outlined text-lg" style="pointer-events:none;">person_off</span>
                                                </button>
                                            @else
                                                <button type="button"
                                                        onclick="if(confirm('Aktifkan kembali user ini?')) { this.closest('form').submit(); }"
                                                        class="w-8 h-8 flex items-center justify-center text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors cursor-pointer"
                                                        title="Aktifkan">
                                                    <span class="material-symbols-outlined text-lg" style="pointer-events:none;">check_circle</span>
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="w-8 h-8 flex items-center justify-center text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed"
                                              title="Tidak dapat mengubah status akun sendiri">
                                            <span class="material-symbols-outlined text-lg">person_off</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-gray-300 text-3xl">groups</span>
                                    </div>
                                    <p class="text-gray-600 font-bold">Belum ada data user</p>
                                    @if (request()->anyFilled(['search', 'role', 'is_active']))
                                        <p class="text-gray-400 text-sm mt-1">Coba sesuaikan filter pencarian.</p>
                                        <a href="{{ route('admin.users.index') }}" class="mt-3 inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">Reset Filter</a>
                                    @else
                                        <p class="text-gray-400 text-sm mt-1 mb-4">Klik tombol di bawah untuk mendaftarkan akun baru.</p>
                                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-lg">person_add</span>
                                            Tambah User
                                        </a>
                                    @endif
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

    {{-- Summary --}}
    <p class="mt-3 text-xs font-semibold text-gray-400 text-right">
        Menampilkan {{ $users->count() }} dari {{ $users->total() }} pengguna
    </p>

    {{-- Modal Reset Password (shared, dipakai untuk semua user) --}}
    <div x-data="{
            show: false,
            userId: null,
            userName: '',
            formUrl: '',
            password: '',
            passwordConfirm: '',
            showPass: false,
            get isMatch() { return this.password === this.passwordConfirm; },
            get isValid() { return this.password.length >= 6 && this.isMatch; }
        }"
         @open-reset-modal.window="show = true; userId = $event.detail.id; userName = $event.detail.name; formUrl = $event.detail.url; password = ''; passwordConfirm = ''; showPass = false;"
         x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>

        {{-- Modal Card --}}
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.stop>

            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl">lock_reset</span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base">Reset Password</h3>
                        <p class="text-blue-100 text-xs" x-text="userName"></p>
                    </div>
                </div>
                <button @click="show = false" class="text-white/70 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Body --}}
            <form :action="formUrl" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <p class="text-sm text-gray-500">Masukkan password baru untuk user ini. Minimal 6 karakter.</p>

                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'"
                                   name="password"
                                   x-model="password"
                                   class="w-full pr-10 pl-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                                   placeholder="Min. 6 karakter"
                                   autocomplete="new-password"
                                   required minlength="6">
                            <button type="button" @click="showPass = !showPass"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <span class="material-symbols-outlined text-lg" x-text="showPass ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Password</label>
                        <input :type="showPass ? 'text' : 'password'"
                               name="password_confirmation"
                               x-model="passwordConfirm"
                               :class="passwordConfirm && !isMatch ? 'border-red-400 focus:border-red-400 focus:ring-red-400/20' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500/20'"
                               class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none transition-all focus:ring-2"
                               placeholder="Ulangi password"
                               autocomplete="new-password"
                               required>
                        <p x-show="passwordConfirm && !isMatch" class="text-xs text-red-500 mt-1">
                            ⚠ Password tidak cocok
                        </p>
                        <p x-show="passwordConfirm && isMatch && password.length >= 6" class="text-xs text-emerald-600 mt-1">
                            ✓ Password cocok
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 pb-6 flex items-center justify-end gap-3">
                    <button type="button" @click="show = false"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            :disabled="!isValid"
                            :class="isValid ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            class="px-5 py-2 text-sm font-semibold rounded-xl transition-colors inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">lock_reset</span>
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-admin-layout>
