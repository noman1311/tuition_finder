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
    
        $teachers = $query->paginate(12);
    
        return view('search', compact('teachers'));
    }
}
