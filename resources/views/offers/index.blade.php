<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Posts - TuitionFinder</title>
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
                        <li><a href="{{ route('my.posts') }}" class="active">MY Posts</a></li>
                        <li><a href="{{ route('requirements.create') }}">Post Requirements</a></li>
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

    <div class="wrap posts-list">
        <h1>My Posts</h1>
        @forelse ($offers as $offer)
            <div class="card">
                <div class="row">
                    <div>
                        <div class="tag">{{ $offer->status }}</div>
                        <h3 style="margin:8px 0 6px;">{{ $offer->subject }} — {{ $offer->class_level }}</h3>
                        <div class="muted">{{ $offer->location }} • {{ $offer->preferred_type }} • {{ $offer->type }}</div>
                    </div>
                    <div style="font-weight:700; color:#065f46;">৳ {{ number_format($offer->salary,2) }}/hr</div>
                </div>
                <p style="margin-top:10px; color:#374151;">{{ $offer->description }}</p>
            </div>
        @empty
            <p class="muted">No posts yet. <a href="{{ route('requirements.create') }}">Create one</a>.</p>
        @endforelse

        <div style="margin-top:16px;">{{ $offers->links() }}</div>
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


