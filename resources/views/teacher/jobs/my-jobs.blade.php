<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Jobs - TuitionFinder</title>
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
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { font-size: 28px; color: #1f2937; margin-bottom: 8px; }
        .page-header p { color: #6b7280; }
        .job-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .job-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px; }
        .job-title { font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 4px; }
        .job-subject { background: #dbeafe; color: #2563eb; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-accepted { background: #d1fae5; color: #059669; }
        .status-rejected { background: #fee2e2; color: #dc2626; }
        .job-meta { color: #6b7280; font-size: 14px; margin-bottom: 12px; }
        .job-description { color: #374151; line-height: 1.6; }
        .no-jobs { text-align: center; padding: 60px 20px; color: #6b7280; }
        .pagination { display: flex; justify-content: center; margin-top: 30px; }
        .pagination a { padding: 8px 16px; margin: 0 4px; background: white; color: #2563eb; text-decoration: none; border-radius: 6px; border: 1px solid #e2e8f0; }
        .pagination a:hover { background: #2563eb; color: white; }
        .pagination .active { background: #2563eb; color: white; }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">TuitionFinder</a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('teacher.jobs.my') }}" class="active">Jobs</a></li>
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
        <div class="page-header">
            <h1>My Job Applications</h1>
            <p>Track your applications and their status</p>
        </div>

        @forelse($applications as $application)
            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">{{ $application->tuitionOffer->subject }} - {{ $application->tuitionOffer->class_level }}</h3>
                        <span class="job-subject">{{ $application->tuitionOffer->type }}</span>
                    </div>
                    <span class="status-badge status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
                </div>
                <div class="job-meta">
                    <i class="fas fa-map-marker-alt"></i> {{ $application->tuitionOffer->location }} • 
                    <i class="fas fa-clock"></i> {{ $application->tuitionOffer->preferred_type }} • 
                    <i class="fas fa-dollar-sign"></i> ৳{{ number_format($application->tuitionOffer->salary, 2) }}/hr
                </div>
                <div class="job-description">{{ $application->tuitionOffer->description }}</div>
                @if($application->message)
                    <div style="margin-top: 12px; padding: 12px; background: #f8fafc; border-radius: 8px;">
                        <strong>Your Application:</strong> {{ $application->message }}
                    </div>
                @endif
            </div>
        @empty
            <div class="no-jobs">
                <i class="fas fa-briefcase" style="font-size: 48px; margin-bottom: 16px; color: #d1d5db;"></i>
                <h3>No applications yet</h3>
                <p>Start applying to jobs to see them here.</p>
                <a href="{{ route('teacher.jobs.all') }}" style="display: inline-block; margin-top: 16px; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px;">
                    Browse All Jobs
                </a>
            </div>
        @endforelse

        <div class="pagination">
            {{ $applications->links() }}
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
