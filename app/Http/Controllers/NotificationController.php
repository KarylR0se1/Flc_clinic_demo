<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Mark all notifications as read
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
   public function fetchUnread()
{
    $notifications = auth()->user()->unreadNotifications()->latest()->get();
    return response()->json([
        'count' => $notifications->count(),
        'notifications' => $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'],
                'appointment_id' => $notification->data['appointment_id'],
            ];
        }),
    ]);
}
 //
}
