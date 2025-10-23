<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Display notifications for the authenticated user
     */
    public function index()
    {
        try {
            $notifications = Notification::forUser(Auth::id())
                ->orderByDesc('created_at')
                ->paginate(15);

            $unreadCount = $this->notificationService->getUnreadCount(Auth::id());

            return view('notifications.index', compact('notifications', 'unreadCount'));
        } catch (\Exception $e) {
            // If notifications table doesn't exist, show empty state
            $notifications = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            $unreadCount = 0;
            
            return view('notifications.index', compact('notifications', 'unreadCount'))
                ->with('error', 'Notifications system is not yet set up. Please run database migrations.');
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(Auth::id());

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount()
    {
        $count = $this->notificationService->getUnreadCount(Auth::id());

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown)
     */
    public function getRecent()
    {
        $notifications = $this->notificationService->getRecentNotifications(Auth::id(), 5);

        return response()->json(['notifications' => $notifications]);
    }
}