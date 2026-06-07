<x-app-admin-layout>
    <x-slot name="title">Manajemen User</x-slot>

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#022448] text-3xl">manage_accounts</span>
            Manajemen User
        </h1>
        <a href="{{ route('admin.user.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
            <span class="material-symbols-outlined text-lg">person_add</span>
            Tambah User
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Nama</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Email</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Role</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Status</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($users as $user)
                @php
                    $roleColors = ['admin'=>'purple','supervisor'=>'indigo','petugas'=>'green','masyarakat'=>'blue'];
                    $rc = $roleColors[$user->role] ?? 'gray';
                @endphp
                <tr class="hover:bg-gray-50 transition {{ !$user->is_active ? 'opacity-50' : '' }}">
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="bg-{{ $rc }}-100 text-{{ $rc }}-700 text-xs px-2 py-0.5 rounded-full font-medium capitalize">{{ $user->role }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if ($user->is_active)
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Aktif</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-1">
                            {{-- Reset Password --}}
                            <form method="POST" action="{{ route('admin.user.reset-password', $user) }}" style="display:inline;">
                                @csrf
                                <button type="button" onclick="if(confirm('Reset password ke \'password\'?')){this.closest('form').submit();}" class="p-2 text-[#022448] hover:bg-blue-50 rounded-lg transition-colors cursor-pointer" title="Reset Password">
                                    <span class="material-symbols-outlined text-xl" style="pointer-events:none;">lock_reset</span>
                                </button>
                            </form>
                            {{-- Edit --}}
                            <a href="{{ route('admin.user.edit', $user) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            {{-- Nonaktifkan --}}
                            @if ($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.user.destroy', $user) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" onclick="if(confirm('Nonaktifkan user ini?')){this.closest('form').submit();}" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Nonaktifkan">
                                    <span class="material-symbols-outlined text-xl" style="pointer-events:none;">person_off</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-gray-400">Belum ada user</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">{{ $users->links() }}</div>
    </div>
</x-app-admin-layout>
