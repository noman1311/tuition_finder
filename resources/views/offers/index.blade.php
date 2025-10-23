<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Posts - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .header { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0 20px; }
        .header-container { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; height: 70px; }
        .logo { font-size: 24px; font-weight: 700; color: #2563eb; text-decoration: none; }
        .nav-menu { display: flex; list-style: none; gap: 30px; align-items: center; }
        .nav-menu a { text-decoration: none; color: #4b5563; font-weight: 500; transition: color 0.3s; }
        .nav-menu a:hover, .nav-menu a.active { color: #2563eb; }
        .user-menu { display: flex; align-items: center; gap: 15px; position: relative; }
        .user-profile { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 6px; background: #f1f5f9; cursor: pointer; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .dropdown { display: none; position: absolute; right: 0; top: 56px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 20px rgba(0,0,0,.08); min-width: 180px; z-index: 1000; }
        .dropdown a, .dropdown button { display: block; width: 100%; padding: 10px 12px; color: #111827; text-decoration: none; background: none; border: none; text-align: left; cursor: pointer; }
        .dropdown a:hover, .dropdown button:hover { background: #f8fafc; }
        .wrap { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        h1 { margin: 0 0 16px; font-size: 26px; color: #111827; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,.06); padding: 18px 20px; margin-bottom: 14px; }
        .row { display: flex; justify-content: space-between; gap: 12px; }
        .tag { background:#dbeafe; color:#1d4ed8; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .muted { color:#6b7280; font-size: 13px; }
        @media (max-width: 768px) {
            .nav-menu { display: none; }
        }
    </style>
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

    <div class="wrap">
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


