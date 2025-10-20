<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Dashboard - TuitionFinder</title>
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
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 300px; gap: 30px; }
        .main-content { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .sidebar { display: flex; flex-direction: column; gap: 20px; }
        .card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card h3 { margin-bottom: 10px; color: #1f2937; }
        .card p { color: #6b7280; }
        .coins { font-size: 24px; font-weight: 700; color: #059669; }
        .welcome { text-align: center; color: #6b7280; }
        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
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
                    <li><a href="{{ route('teacher.dashboard') }}" class="active">Dashboard</a></li>
                    <li><a href="{{ route('teacher.jobs.my') }}">Jobs</a></li>
                    <li><a href="{{ route('wallets') }}">Wallet</a></li>
                    <li><a href="{{ route('teacher.profile.edit') }}">Edit Profile</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" id="userMenuTrigger">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->username,0,1)) }}</div>
                    <span>{{ Auth::user()->username }}</span>
                </div>
                <div class="dropdown" id="userDropdown">
                    <a href="{{ route('switch.to.student') }}">Switch to Student View</a>
                    <a href="{{ route('teacher.profile.edit') }}">Edit Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
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
