<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Post;
use App\Models\Application;

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
        $applications = Application::where('teacher_id', $teacher->teacher_id)
            ->with('tuitionOffer')
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('teacher.jobs.my-jobs', compact('applications'));
    }

    public function onlineJobs(Request $request)
    {
        $query = Post::where('status', 'open')
            ->where('preferred_type', 'online');

        // Apply filters
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        $jobs = $query->orderByDesc('created_at')->paginate(10);
        
        return view('teacher.jobs.online', compact('jobs'));
    }

    public function allJobs(Request $request)
    {
        $query = Post::where('status', 'open');

        // Apply filters
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        $jobs = $query->orderByDesc('created_at')->paginate(10);
        
        return view('teacher.jobs.all', compact('jobs'));
    }

    public function applyJob(Request $request, $offerId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        // Check if already applied
        $existingApplication = Application::where('offer_id', $offerId)
            ->where('teacher_id', $teacher->teacher_id)
            ->first();

        if ($existingApplication) {
            return back()->withErrors(['message' => 'You have already applied for this job.']);
        }

        Application::create([
            'offer_id' => $offerId,
            'teacher_id' => $teacher->teacher_id,
            'message' => $request->message,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
}
