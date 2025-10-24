<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Post Requirements - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/offers.css') }}">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">TuitionFinder</a>
            
            <nav>
                <ul class="nav-menu">
                    @auth
                        <li><a href="{{ route('my.posts') }}">MY Posts</a></li>
                        <li><a href="{{ route('requirements.create') }}" class="active">Post Requirements</a></li>
                        <li>
                            <a href="{{ route('notifications.index') }}">
                                Notifications
                                @if(Auth::user()->unread_notifications_count > 0)
                                    <span style="background: #ef4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; margin-left: 4px;">
                                        {{ Auth::user()->unread_notifications_count }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endauth
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" id="userMenuTrigger">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->username,0,1)) }}</div>
                    <span>{{ Auth::user()->username }}</span>
                </div>
                <div class="dropdown" id="userDropdown">
                    @if(Auth::user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}">Teacher Dashboard</a>
                        <a href="{{ route('switch.to.student') }}">Switch to Student View</a>
                        <a href="{{ route('teacher.profile.edit') }}">Edit Profile</a>
                    @else
                        <a href="{{ route('student.profile.edit') }}">Edit Profile</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="wrap">
        <h1>Request a tutor</h1>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('requirements.store') }}">
            @csrf

            <div class="row">
                <label>Location</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="City, Area" required>
            </div>

            <div class="row">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
            </div>

            <div class="row">
                <label>Details of your requirement</label>
                <div>
                    <textarea name="description" placeholder="Please don't share any contact details here" required>{{ old('description') }}</textarea>
                    <div class="muted">Please don't share any contact details (phone, email, website etc) here</div>
                </div>
            </div>

            <div class="row">
                <label>Subjects</label>
                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Enter subjects" required>
            </div>

            <div class="row">
                <label>Your level</label>
                <input type="text" name="class_level" value="{{ old('class_level') }}" placeholder="Grade / Class / Level" required>
            </div>

            <div class="row">
                <label>I want</label>
                <select name="type" required>
                    <option value="">-- Select --</option>
                    <option value="assignment_help" {{ old('type')==='assignment_help'?'selected':'' }}>assignment_help</option>
                    <option value="tutoring" {{ old('type')==='tutoring'?'selected':'' }}>tutoring</option>
                </select>
            </div>

            <div class="row">
                <label>Meeting options</label>
                <select name="preferred_type" required>
                    <option value="online" {{ old('preferred_type')==='online'?'selected':'' }}>online</option>
                    <option value="offline" {{ old('preferred_type')==='offline'?'selected':'' }}>offline</option>
                    <option value="both" {{ old('preferred_type')==='both'?'selected':'' }}>both</option>
                </select>
            </div>

            <div class="row">
                <label>Salary (per hour)</label>
                <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" placeholder="e.g., 500" required>
            </div>

            <div class="row" style="grid-template-columns: 1fr;">
                <button type="submit" class="btn">Continue</button>
            </div>
        </form>
    </div>

    <script>
        // User menu dropdown
        const trigger = document.getElementById('userMenuTrigger');
        const dropdown = document.getElementById('userDropdown');
        if (trigger && dropdown) {
            trigger.addEventListener('click', function(e){
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', function(){
                dropdown.style.display = 'none';
            });
        }
    </script>
</body>
</html>


