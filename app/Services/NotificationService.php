<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Post;

class NotificationService
{
    /**
     * Create a job application notification for the student
     */
    public function createJobApplicationNotification($application)
    {
        try {
            // Get the job/post
            $job = Post::find($application->offer_id);
            if (!$job) {
                return false;
            }

            // Get the student who posted the job
            $student = $job->student;
            if (!$student) {
                return false;
            }

            // Get the teacher who applied
            $teacher = Teacher::find($application->teacher_id);
            if (!$teacher) {
                return false;
            }

            // Create notification data
            $notificationData = [
                'application_id' => $application->application_id,
                'job_id' => $job->offer_id,
                'teacher_id' => $teacher->teacher_id,
                'teacher_name' => $teacher->name,
                'job_title' => $job->subject . ' - ' . $job->class_level,
                'application_message' => $application->message,
                'job_location' => $job->location,
                'job_salary' => $job->salary,
            ];

            // Create the notification
            return Notification::create([
                'user_id' => $student->user_id,
                'type' => 'job_application',
                'title' => 'New Teacher Application',
                'message' => "{$teacher->name} has applied for your {$job->subject} tutoring job. Click to view their application message.",
                'data' => $notificationData,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            // If notifications table doesn't exist or other error, log it but don't break the application
            \Log::info('Notification creation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount($userId)
    {
        try {
            return Notification::forUser($userId)->unread()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get recent notifications for a user
     */
    public function getRecentNotifications($userId, $limit = 10)
    {
        try {
            return Notification::forUser($userId)
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        try {
            return Notification::forUser($userId)
                ->unread()
                ->update(['is_read' => true]);
        } catch (\Exception $e) {
            return false;
        }
    }
}