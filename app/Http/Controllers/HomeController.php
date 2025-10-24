<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // Topics from teachers.subject_expertise (stored as JSON text)
    $topics = \App\Models\Teacher::whereNotNull('subject_expertise')
    ->get()
    ->pluck('subject_expertise')   // array via cast
    ->flatten()
    ->filter()
    ->map(fn($t) => trim((string)$t))
    ->unique()
    ->values()
    ->take(20);

// Recent tuition offers from tuition_offers
$recentPosts = \App\Models\Post::where('status', 'open')
    ->orderByDesc('created_at')
    ->take(6)
    ->get();

return view('home', compact('topics', 'recentPosts'));
    }

    public function search(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Teacher::query();
    
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
    
        if ($request->filled('subject')) {
            // subject_expertise stored as JSON text; use LIKE for compatibility
            $query->where('subject_expertise', 'like', '%' . $request->subject . '%');
        }
    
        $teachers = $query->orderBy('created_at', 'desc')->paginate(12);
    
        return view('search', compact('teachers'));
    }

    public function contactTeacher(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,teacher_id',
            'message' => 'required|string|max:1000'
        ]);

        $teacher = \App\Models\Teacher::findOrFail($request->teacher_id);
        $student = auth()->user();

        // Create notification for the teacher
        \App\Models\Notification::create([
            'user_id' => $teacher->user_id,
            'type' => 'contact_request',
            'title' => 'New Contact Request',
            'message' => "Student {$student->username} wants to contact you",
            'data' => [
                'student_id' => $student->user_id,
                'student_name' => $student->username,
                'student_phone' => $student->phone,
                'message' => $request->message,
                'contact_cost' => 10 // Cost in coins to reveal contact info
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact request sent successfully!'
        ]);
    }
}
