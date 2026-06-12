<x-app-masyarakat-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="mx-auto w-full max-w-5xl space-y-8">

        {{-- HERO SECTION --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#022448] via-[#0d3b6e] to-[#1a5fa8] text-white shadow-2xl">

            {{-- Decorative blobs --}}
            <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400/10 rounded-full translate-y-1/2 -translate-x-1/4"></div>
            <div class="absolute top-1/2 right-24 w-16 h-16 bg-white/5 rounded-full"></div>

            <div class="relative z-10 px-8 py-14 md:px-16 text-center">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium text-blue-100 mb-6 border border-white/20">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    Selamat datang, {{ auth()->user()->name }}
                </div>

                {{-- Headline --}}
                <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4" style="font-family: Manrope, sans-serif;">
                    Layanan Pengaduan<br>
                    <span class="text-blue-200">Masyarakat</span>
                </h1>
                <p class="text-blue-200 text-base md:text-lg max-w-lg mx-auto mb-8 leading-relaxed">
                    Sampaikan aspirasi Anda dengan mudah dan transparan. Kami memastikan setiap suara didengar dan ditindaklanjuti.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('masyarakat.pengaduan.create') }}"
                       class="inline-flex items-center gap-2 bg-white text-[#022448] font-bold px-7 py-3.5 rounded-xl hover:bg-blue-50 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Sampaikan Pengaduan
                    </a>
                    <a href="{{ route('masyarakat.pengaduan.riwayat') }}"
                       class="inline-flex items-center gap-2 bg-white/15 text-white font-semibold px-7 py-3.5 rounded-xl hover:bg-white/25 transition-all duration-200 border border-white/20 text-sm backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H2a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2 1 1 0 000 2h2a1 1 0 110 2H4z" clip-rule="evenodd"/>
                        </svg>
                        Lihat Riwayat
                    </a>
                </div>
            </div>

            {{-- Stats Bar di bagian bawah hero --}}
            <div class="relative z-10 border-t border-white/10 grid grid-cols-3 divide-x divide-white/10">
                <div class="py-5 text-center">
                    <p class="text-2xl md:text-3xl font-black text-white" style="font-family: Manrope, sans-serif;">{{ $totalPengaduan }}</p>
                    <p class="text-xs text-blue-300 mt-1 uppercase tracking-wide font-medium">Total Laporan</p>
                </div>
                <div class="py-5 text-center">
                    <p class="text-2xl md:text-3xl font-black text-emerald-300" style="font-family: Manrope, sans-serif;">{{ $totalSelesai }}</p>
                    <p class="text-xs text-blue-300 mt-1 uppercase tracking-wide font-medium">Selesai</p>
                </div>
                <div class="py-5 text-center">
                    <p class="text-2xl md:text-3xl font-black text-amber-300" style="font-family: Manrope, sans-serif;">{{ $unreadNotif }}</p>
                    <p class="text-xs text-blue-300 mt-1 uppercase tracking-wide font-medium">Notifikasi Baru</p>
                </div>
            </div>
        </div>

        {{-- Pengaduan Terakhir --}}
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-5 bg-[#022448] rounded-full"></div>
                    <h2 class="font-bold text-gray-800" style="font-family: Manrope, sans-serif;">Pengaduan Terakhir</h2>
                </div>
                <a href="{{ route('masyarakat.pengaduan.riwayat') }}" class="text-xs text-[#2563EB] hover:text-blue-700 font-medium">Lihat semua →</a>
            </div>
            <div>
                @forelse ($pengaduanTerakhir as $p)
                <a href="{{ route('masyarakat.pengaduan.riwayat.show', $p->nomor_tiket) }}"
                   class="flex items-center justify-between px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer group last:border-b-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#022448]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ $p->nomor_tiket }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                {{ $p->kategori?->nama_kategori ?? '-' }}
                                · {{ $p->tanggal_pengajuan->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <x-badge-status :status="$p->status" />
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
                @empty
                <div class="py-14 text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#022448]/40" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H2a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2 1 1 0 000 2h2a1 1 0 110 2H4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Belum ada pengaduan</p>
                    <p class="text-xs text-gray-400 mb-4">Anda belum pernah membuat pengaduan.</p>
                    <a href="{{ route('masyarakat.pengaduan.create') }}"
                       class="inline-flex items-center gap-2 bg-[#022448] text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-[#0d3b6e] transition-colors text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Buat Pengaduan Pertama
                    </a>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-masyarakat-layout>
