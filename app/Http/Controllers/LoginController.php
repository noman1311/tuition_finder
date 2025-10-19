<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Manually attempt using username column
        $user = User::where('username', $credentials['username'])->first();
        if ($user && $credentials['password'] === $user->password) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            
            // Check if user has completed their profile
            if ($user->role === 'student' && !$user->student) {
                return redirect()->route('student.profile');
            } elseif ($user->role === 'teacher' && !$user->teacher) {
                return redirect()->route('teacher.profile');
            }
            
            // Redirect to appropriate dashboard
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }
            
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'username' => 'Invalid credentials.',
        ])->onlyInput('username');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:student/parent,teacher'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user = User::create([
            'username' => $data['email'], // Use email as username
            'email' => $data['email'],
            'password' => $data['password'], // Plain text as requested
            'role' => $data['role'],
            'phone' => $data['phone'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Redirect to profile completion based on role
        if ($data['role'] === 'student/parent') {
            return redirect()->route('student.profile');
        } else {
            return redirect()->route('teacher.profile');
        }
    }

    public function showStudentProfileForm()
    {
        return view('auth.student-profile');
    }

    public function completeStudentProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:male,female'],
            'class_level' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:100'],
        ]);

        Student::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'gender' => $data['gender'],
            'class_level' => $data['class_level'],
            'location' => $data['location'],
        ]);

        return redirect()->route('home')->with('success', 'Profile completed successfully!');
    }

    public function showTeacherProfileForm()
    {
        return view('auth.teacher-profile');
    }

    public function completeTeacherProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:male,female'],
            'subject_expertise' => ['required', 'string'],
            'experience' => ['required', 'integer', 'min:0'],
            'location' => ['required', 'string', 'max:100'],
            'preferred_type' => ['required', 'in:online,offline,both'],
            'description' => ['nullable', 'string'],
        ]);

        // Convert subject expertise string to array
        // The model's cast will automatically handle JSON encoding
        $data['subject_expertise'] = array_map('trim', explode(',', $data['subject_expertise']));

        Teacher::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'gender' => $data['gender'],
            'subject_expertise' => $data['subject_expertise'],
            'experience' => $data['experience'],
            'location' => $data['location'],
            'preferred_type' => $data['preferred_type'],
            'description' => $data['description'] ?? '',
        ]);

        return redirect()->route('home')->with('success', 'Profile completed successfully!');
    }

    public function editStudentProfile()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        return view('auth.student-profile-edit', compact('student'));
    }

    public function updateStudentProfile(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:male,female'],
            'class_level' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:100'],
        ]);

        $student->update($data);
        return redirect()->route('home')->with('success', 'Profile updated successfully!');
    }

    public function editTeacherProfile()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        return view('auth.teacher-profile-edit', compact('teacher'));
    }

    public function updateTeacherProfile(Request $request)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:male,female'],
            'subject_expertise' => ['required', 'string'],
            'experience' => ['required', 'integer', 'min:0'],
            'location' => ['required', 'string', 'max:100'],
            'preferred_type' => ['required', 'in:online,offline,both'],
            'description' => ['nullable', 'string'],
        ]);

        // Convert subject expertise string to array
        // The model's cast will automatically handle JSON encoding
        $data['subject_expertise'] = array_map('trim', explode(',', $data['subject_expertise']));

        $teacher->update($data);
        return redirect()->route('teacher.dashboard')->with('success', 'Profile updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}