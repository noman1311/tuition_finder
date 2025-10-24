<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Application;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->username !== 'admin') {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_posts' => Post::count(),
            'total_applications' => Application::count(),
            'open_posts' => Post::where('status', 'open')->count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
        ];

        $recent_posts = Post::with('student.user')->latest()->take(5)->get();
        $recent_applications = Application::with(['teacher', 'tuitionOffer'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_applications'));
    }

    public function posts(Request $request)
    {
        $query = Post::with('student.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.posts', compact('posts'));
    }

    public function updatePostStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,closed,in_progress,completed'
        ]);

        $post = Post::findOrFail($id);
        $post->update(['status' => $request->status]);

        return back()->with('success', 'Post status updated successfully.');
    }

    public function applications(Request $request)
    {
        $query = Application::with(['teacher', 'tuitionOffer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('teacher', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('tuitionOffer', function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.applications', compact('applications'));
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,withdrawn'
        ]);

        $application = Application::findOrFail($id);
        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application status updated successfully.');
    }

    public function students(Request $request)
    {
        $query = Student::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('email', 'like', "%{$search}%")
                           ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.students', compact('students'));
    }

    public function teachers(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('subject_expertise', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('email', 'like', "%{$search}%")
                           ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $teachers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.teachers', compact('teachers'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users', compact('users'));
    }
}