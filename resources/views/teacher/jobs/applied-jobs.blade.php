<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Applied Jobs - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jobs.css') }}">

</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">TuitionFinder</a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('teacher.jobs.my') }}" class="active">Jobs</a></li>
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
        <div class="page-header">
            <h1>Jobs</h1>
            <p>Manage your teaching opportunities</p>
        </div>

        <div class="job-tabs">
            <a href="{{ route('teacher.jobs.my') }}" class="job-tab active">Applied Jobs</a>
            <a href="{{ route('teacher.jobs.all') }}" class="job-tab">All Jobs</a>
            <a href="{{ route('teacher.jobs.online') }}" class="job-tab">Online Jobs</a>
        </div>

        @forelse($applications as $application)
            @php
                $job = $application->tuitionOffer;
            @endphp
            
            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">{{ $job->subject }} - {{ $job->class_level }}</h3>
                        <span class="job-subject">{{ $job->type }}</span>
                    </div>
                    <span class="status-badge status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
                </div>
                <div class="job-meta">
                    <i class="fas fa-map-marker-alt"></i> {{ $job->location }} • 
                    <i class="fas fa-clock"></i> {{ $job->preferred_type }} • 
                    <i class="fas fa-dollar-sign"></i> ৳{{ number_format($job->salary, 2) }}/hr •
                    <i class="fas fa-phone"></i> {{ $job->formatted_phone }}
                </div>
                <div class="job-description">{{ $job->description }}</div>
                
                <div class="application-details">
                    <h4><i class="fas fa-paper-plane"></i> Your Application</h4>
                    <div class="application-message">"{{ $application->message }}"</div>
                    <div class="application-date">Applied on {{ $application->created_at->format('M d, Y \a\t h:i A') }}</div>
                </div>
            </div>
        @empty
            <div class="no-jobs">
                <i class="fas fa-briefcase" style="font-size: 48px; margin-bottom: 16px; color: #d1d5db;"></i>
                <h3>No applications yet</h3>
                <p>You haven't applied for any jobs yet. <a href="{{ route('teacher.jobs.all') }}" style="color: #2563eb; text-decoration: none;">Browse available jobs</a> to start applying.</p>
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