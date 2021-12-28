<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index()
    {
        $user = Auth::user();
        if (!$user instanceof \App\Models\User) {
            abort(401);
        }

        $items = Notification::where('user_id', $user->id)
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('priority', 'desc')
            ->paginate(10);

        return view('member.notification.index', compact('items'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification $notification
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Notification $notification)
    {
        if ($notification->status == 0) {
            $notification->update(['status' => 1]);
        }

        return view('member.notification.show', compact('notification'));
    }

    /**
     * Mark all notification as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof \App\Models\User) {
            abort(401);
        }

        $query = Notification::where(['user_id' => $user->id, 'status' => 0]);
        if ($query->count() > 0) {
            $query->update(['status' => 1]);
            return redirect()->back()->with('update', 'Data Anda telah berhasil disimpan.');
        } else {
            return redirect()->back()->with('warning', 'Semua notifikasi sudah pernah dibaca.');
        }
    }
}
