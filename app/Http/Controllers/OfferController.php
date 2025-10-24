<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; // tuition_offers model
use App\Models\Student;
use App\Models\User;

class OfferController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $student = Student::where('user_id', $userId)->first();
        
        // If no student profile exists, create one with default values
        if (!$student) {
            $user = Auth::user();
            $student = Student::create([
                'user_id' => Auth::id(),
                'name' => $user->username,
                'gender' => 'male', // Default value
                'class_level' => 'Not specified',
                'location' => 'Not specified',
            ]);
        }
        
        $offers = Post::where('student_id', $student->student_id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('offers.index', compact('offers'));
    }

    public function create()
    {
        return view('offers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location' => ['required','string','max:100'],
            'phone' => ['required','string','max:20'],
            'description' => ['required','string'],
            'subject' => ['required','string','max:100'],
            'class_level' => ['required','string','max:50'],
            'type' => ['required','in:assignment_help,tutoring'],
            'preferred_type' => ['required','in:online,offline,both'],
            'salary' => ['required','numeric'],
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', Auth::id())->first();
        
        // If user is a teacher or doesn't have a student profile, create a temporary student record
        if (!$student) {
            // For teachers or users without student profiles, create a minimal student record
            $student = Student::create([
                'user_id' => Auth::id(),
                'name' => $user->username, // Use the username as name
                'gender' => 'male', // Default value, can be updated later
                'class_level' => $data['class_level'],
                'location' => $data['location'],
            ]);
        }

        Post::create([
            'student_id' => $student->student_id,
            'subject' => $data['subject'],
            'class_level' => $data['class_level'],
            'location' => $data['location'],
            'salary' => $data['salary'],
            'status' => 'open',
            'description' => $data['description'],
            'phone' => $data['phone'],
            'type' => $data['type'],
            'preferred_type' => $data['preferred_type'],
        ]);

        return redirect()->route('my.posts')->with('success', 'Requirement posted successfully.');
    }
}


