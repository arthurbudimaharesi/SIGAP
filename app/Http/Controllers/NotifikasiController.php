<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
            ->when($request->filter === 'unread', fn($q) => $q->where('is_read', false))
            ->when($request->filter === 'read', fn($q) => $q->where('is_read', true))
            ->orderByDesc('created_at')
            ->paginate(15);
            
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function count()
    {
        // Untuk AJAX polling — tidak perlu middleware khusus, sudah di group auth
        $unreadCount = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)->count();
            
        $notifications = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
            
        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', auth()->id())->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function markRead($id)
    {
        $notif = Notifikasi::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $notif->update(['is_read' => true]);
        
        // Redirect ke pengaduan terkait berdasarkan role pengguna
        if ($notif->pengaduan_id) {
            $pengaduan = Pengaduan::find($notif->pengaduan_id);
            if ($pengaduan) {
                $role = auth()->user()->role;
                $route = match($role) {
                    'masyarakat' => 'masyarakat.pengaduan.riwayat.show',
                    'supervisor' => 'supervisor.pengaduan.show',
                    'petugas'    => 'petugas.pengaduan.show',
                    default      => 'masyarakat.pengaduan.riwayat.show',
                };
                if (\Illuminate\Support\Facades\Route::has($route)) {
                    return redirect()->route($route, $pengaduan->nomor_tiket);
                }
            }
        }
        
        return redirect()->route('masyarakat.notifikasi.index');
    }
}
