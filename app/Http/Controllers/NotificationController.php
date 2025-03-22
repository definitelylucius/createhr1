<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    // Fetch unread notifications
    public function fetchUnread()
    {
        $user = Auth::user();
        return response()->json($user->unreadNotifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'New Notification',
                'url' => route('admin.dashboard') // Change this based on your actual route
            ];
        }));
    }

    // Mark all notifications as read
    public function markAsRead()
    {
        Auth::user()->unreadNotifications->each->markAsRead();
        return response()->json(['message' => 'Notifications marked as read']);
    }

    public function clearAll()
{
    $user = Auth::user();
    if ($user->notifications()->exists()) {
        $user->notifications()->delete();
        return response()->json(['message' => 'All notifications cleared']);
    }
    return response()->json(['message' => 'No notifications to clear'], 404);
}

}

