<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    /**
     * Reveal contact information by paying coins
     */
    public function revealContact(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|exists:notifications,id'
            ]);

            $notification = Notification::where('id', $request->notification_id)
                ->where('user_id', Auth::id())
                ->where('type', 'contact_request')
                ->firstOrFail();

            $data = $notification->data;
            $cost = $data['contact_cost'] ?? 10;

            // Get teacher record to check coins
            $teacher = \App\Models\Teacher::where('user_id', Auth::id())->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found.'
                ]);
            }

            if ($teacher->coins < $cost) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient coins. You need {$cost} coins but only have {$teacher->coins}."
                ]);
            }

            // Deduct coins
            $teacher->decrement('coins', $cost);

            // Record transaction
            $student_name = $data['student_name'] ?? 'Unknown';
            \App\Models\Transaction::create([
                'user_id' => Auth::id(),
                'amount' => -$cost,
                'type' => 'bkash', // Using existing enum, could be 'system' if we add it
                'description' => "Contact information reveal for student: {$student_name}",
                'transaction_date' => now()->toDateString()
            ]);

            // Get student contact information
            $student_id = $data['student_id'] ?? null;
            $phone_from_notification = $data['student_phone'] ?? null;
            
            if (!$student_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID not found in notification data.'
                ]);
            }

            // Try to get fresh phone number from database
            $student_user = \App\Models\User::find($student_id);
            $phone_number = null;
            
            if ($student_user && $student_user->phone) {
                $phone_number = $student_user->phone;
            } elseif ($phone_from_notification) {
                // Fallback to phone stored in notification
                $phone_number = $phone_from_notification;
            } else {
                $phone_number = 'Phone number not available';
            }


            
            return response()->json([
                'success' => true,
                'contact_info' => [
                    'phone' => $phone_number
                ],
                'message' => 'Contact information revealed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ]);
        }
    }
}