<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; // tuition_offers model
use App\Models\Student;

class OfferController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $student = Student::where('user_id', $userId)->first();
        $offers = Post::when($student, function ($q) use ($student) {
            $q->where('student_id', $student->student_id);
        }, function ($q) { $q->whereRaw('1=0'); })
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

        $student = Student::where('user_id', Auth::id())->first();
        if (!$student) {
            return back()->withErrors(['msg' => 'Create a student profile first.'])->withInput();
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


