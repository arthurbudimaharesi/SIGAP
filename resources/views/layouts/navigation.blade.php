<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    @php
        $profileRoute = match (Auth::user()->role) {
            'admin' => route('admin.profil.edit'),
            'supervisor' => route('supervisor.profil.edit'),
            'petugas' => route('petugas.profil.edit'),
            default => route('masyarakat.profil.edit'),
        };
    @endphp
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">

                <!-- Bell Notification (Hanya untuk Masyarakat) -->
                @if(Auth::user()->role === 'masyarakat')
                <div x-data="{
                        open: false,
                        unread: 0,
                        items: [],
                        init() {
                            this.fetchCount();
                            setInterval(() => this.fetchCount(), 30000);
                        },
                        fetchCount() {
                            fetch('/notifikasi/count')
                                .then(r => r.json())
                                .then(data => {
                                    this.unread = data.unread_count;
                                    this.items = data.notifications;
                                });
                        },
                        truncate(text, len) {
                            return text && text.length > len ? text.substring(0, len) + '...' : text;
                        }
                    }" class="relative" @click.away="open = false">
                    <button @click="open = !open"
                            class="relative p-2 rounded-lg text-gray-500 hover:text-[#022448] hover:bg-gray-100 transition-colors focus:outline-none"
                            aria-label="Notifikasi">
                        <!-- Bell Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Badge -->
                        <span x-show="unread > 0"
                              x-text="unread > 99 ? '99+' : unread"
                              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 leading-none"
                              x-cloak></span>
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display:none;"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden top-full">

                        <!-- Header Dropdown -->
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <span class="text-sm font-semibold text-gray-800">Notifikasi</span>
                            <span x-show="unread > 0"
                                  x-text="unread + ' belum dibaca'"
                                  class="text-xs text-red-500 font-medium"></span>
                        </div>

                        <!-- List Notifikasi -->
                        <div class="max-h-80 overflow-y-auto">
                            <template x-if="items.length === 0">
                                <div class="py-8 text-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <p class="text-xs">Tidak ada notifikasi baru</p>
                                </div>
                            </template>
                            <template x-for="notif in items" :key="notif.id">
                                <a :href="`/masyarakat/notifikasi/${notif.id}/read`"
                                   class="flex items-start gap-3 px-4 py-3 hover:bg-blue-50 border-b border-gray-50 transition-colors cursor-pointer group">
                                    <!-- Icon Status -->
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full" x-show="!notif.is_read"></div>
                                        <div class="w-2 h-2 bg-gray-200 rounded-full" x-show="notif.is_read"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 group-hover:text-[#022448]" x-text="notif.judul"></p>
                                        <p class="text-xs text-gray-500 mt-0.5 leading-relaxed" x-text="truncate(notif.pesan, 80)"></p>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <!-- Footer Dropdown -->
                        <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50">
                            <a href="{{ route('masyarakat.notifikasi.index') }}"
                               @click="open = false"
                               class="block text-center text-xs font-semibold text-[#022448] hover:text-blue-700 transition-colors">
                                Lihat Semua Notifikasi →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="$profileRoute">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" data-confirm="Yakin ingin logout dari akun ini?">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').requestSubmit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="$profileRoute">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" data-confirm="Yakin ingin logout dari akun ini?">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').requestSubmit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
