<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Dashboard - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">TuitionFinder</a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('teacher.dashboard') }}" class="active">Dashboard</a></li>
                    <li><a href="{{ route('teacher.jobs.my') }}">Jobs</a></li>
                    <li><a href="{{ route('wallet.index') }}">Wallet</a></li>
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
                    <li><a href="{{ route('teacher.profile.edit') }}">Edit Profile</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" id="userMenuTrigger">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->username,0,1)) }}</div>
                    <span>{{ Auth::user()->username }}</span>
                </div>
                <div class="dropdown" id="userDropdown">
                    <div style="padding:8px 12px; background:#f8fafc; border-bottom:1px solid #e5e7eb; font-size:12px; color:#6b7280; font-weight:600;">DASHBOARD</div>
                    <a href="{{ route('teacher.dashboard') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Teacher Dashboard</a>
                    <a href="{{ route('switch.to.student') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Switch to Student View</a>
                    <div style="border-top:1px solid #e5e7eb; margin:4px 0;"></div>
                    <a href="{{ route('teacher.profile.edit') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Edit profile</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="width:100%; text-align:left; padding:10px 12px; background:none; border:none; cursor:pointer; color:#111827;">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <div class="welcome">
                <h1>Welcome back, {{ $teacher->name }}!</h1>
                <p>Manage your teaching opportunities and profile from here.</p>
            </div>
        </div>

        <div class="sidebar">
            <div class="card">
                <h3><i class="fas fa-user"></i> My Profile</h3>
                <p>View and edit your teaching profile</p>
                <a href="{{ route('teacher.profile.edit') }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">View Profile →</a>
            </div>

            <div class="card">
                <h3><i class="fas fa-wallet"></i> Wallet</h3>
                <div class="coins">{{ $teacher->coins }} coins</div>
                <p>Your current coin balance</p>
            </div>
        </div>
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
