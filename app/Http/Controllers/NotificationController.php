<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    // Halaman daftar semua notifikasi
    public function index()
    {
        if (!Schema::hasTable('notifications')) {
            $notifications = collect();
            return view('notifications.index', compact(
                'notifications'
            ))->with([
                'totalNotifs'     => 0,
                'unreadNotifs'    => 0,
                'deadlineCount'   => 0,
                'habitCount'      => 0,
                'achievementCount'=> 0,
            ]);
        }

        $notifications = auth()->user()
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(20);

        // Tandai semua sebagai dibaca saat halaman dibuka
        auth()->user()->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        $totalNotifs = auth()->user()->notifications()->count();
        $unreadNotifs = auth()->user()->notifications()->unread()->count();
        $deadlineCount = auth()->user()->notifications()->where('type','deadline_reminder')->count();
        $habitCount = auth()->user()->notifications()->where('type','habit_reminder')->count();
        $achievementCount = auth()->user()->notifications()->where('type','achievement')->count();

        return view('notifications.index', compact(
            'notifications',
            'totalNotifs',
            'unreadNotifs',
            'deadlineCount',
            'habitCount',
            'achievementCount'
        ));
    }

    // Mark one as read (AJAX atau redirect)
    public function markRead(Notification $notification)
    {
        if (!Schema::hasTable('notifications')) {
            return response()->json(['ok' => false, 'message' => 'Notifikasi tidak tersedia.'], 404);
        }

        if ($notification->user_id !== auth()->id()) abort(403);
        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return $notification->link
            ? redirect($notification->link)
            : back();
    }

    // Mark semua sebagai dibaca
    public function markAllRead()
    {
        if (Schema::hasTable('notifications')) {
            auth()->user()->notifications()->unread()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    // Hapus notifikasi
    public function destroy(Notification $notification)
    {
        if (!Schema::hasTable('notifications')) {
            return back()->with('error', 'Notifikasi tidak tersedia.');
        }

        if ($notification->user_id !== auth()->id()) abort(403);
        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    // API: ambil notifikasi unread (untuk bell badge realtime)
    public function unreadCount()
    {
        if (!Schema::hasTable('notifications')) {
            return response()->json(['count' => 0, 'items' => []]);
        }

        $count = auth()->user()->notifications()->unread()->count();
        $items = auth()->user()->notifications()
            ->unread()
            ->orderByDesc('created_at')
            ->take(5)
            ->get(['notifications_id', 'title', 'message', 'icon', 'link', 'created_at']);

        return response()->json(['count' => $count, 'items' => $items]);
    }
}
