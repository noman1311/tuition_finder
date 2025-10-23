<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Post;
use App\Models\Application;
use App\Services\NotificationService;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'teacher') {
                return redirect()->route('home')->withErrors(['error' => 'Access denied. Only teachers can access this area.']);
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        return view('teacher.dashboard', compact('teacher'));
    }

    public function myJobs()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        // Get all open jobs with unique offer_id to prevent duplicates
        $jobs = Post::where('status', 'open')
            ->distinct('offer_id')
            ->with(['applications' => function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->teacher_id);
            }])
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('teacher.jobs.my-jobs', compact('jobs', 'teacher'));
    }

    public function onlineJobs(Request $request)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        $query = Post::where('status', 'open')
            ->where('preferred_type', 'online')
            ->distinct('offer_id')
            ->with(['applications' => function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->teacher_id);
            }]);

        // Apply filters
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        $jobs = $query->orderByDesc('created_at')->paginate(10);
        
        return view('teacher.jobs.online', compact('jobs', 'teacher'));
    }

    public function allJobs(Request $request)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        $query = Post::where('status', 'open')
            ->distinct('offer_id')
            ->with(['applications' => function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->teacher_id);
            }]);

        // Apply filters
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        $jobs = $query->orderByDesc('created_at')->paginate(10);
        
        return view('teacher.jobs.all', compact('jobs', 'teacher'));
    }

    public function applyJob(Request $request, $offerId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        // Check if the job exists and is still open
        $job = Post::where('offer_id', $offerId)->where('status', 'open')->first();
        if (!$job) {
            return back()->withErrors(['message' => 'This job is no longer available.']);
        }

        // Check if already applied (double-check to prevent duplicates)
        $existingApplication = Application::where('offer_id', $offerId)
            ->where('teacher_id', $teacher->teacher_id)
            ->first();

        if ($existingApplication) {
            return back()->withErrors(['message' => 'You have already applied for this job.']);
        }

        // Check if teacher has enough coins
        $applicationCost = config('tuition.application_cost', 10);
        if ($teacher->coins < $applicationCost) {
            return back()->withErrors(['message' => "Can't apply - Not enough coins. You need {$applicationCost} coins to apply for this job."]);
        }

        try {
            // Deduct coins from teacher's account
            $teacher->decrement('coins', $applicationCost);

            // Create application
            $application = Application::create([
                'offer_id' => $offerId,
                'teacher_id' => $teacher->teacher_id,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            // Create notification for the student
            $notificationService = new NotificationService();
            $notificationService->createJobApplicationNotification($application);

            return back()->with('success', "Application submitted successfully! {$applicationCost} coins deducted from your wallet. You can now see the full contact details. The student has been notified of your application.");
        } catch (\Exception $e) {
            // If application creation fails, refund the coins
            $teacher->increment('coins', $applicationCost);
            return back()->withErrors(['message' => 'Failed to submit application. Please try again.']);
        }
    }
}
