<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications - TuitionFinder</title>
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
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { font-size: 28px; color: #1f2937; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.3s; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .notification-item { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.3s; }
        .notification-item.unread { border-left: 4px solid #2563eb; background: #f8fafc; }
        .notification-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px; }
        .notification-title { font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 4px; }
        .notification-time { font-size: 12px; color: #6b7280; }
        .notification-message { color: #374151; line-height: 1.6; margin-bottom: 12px; }
        .notification-details { background: #f8fafc; border-radius: 8px; padding: 12px; margin-top: 12px; }
        .notification-details h4 { font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 8px; }
        .notification-details p { font-size: 14px; color: #6b7280; margin-bottom: 4px; }
        .application-message { background: #eff6ff; border-left: 3px solid #2563eb; padding: 12px; margin-top: 8px; font-style: italic; }
        .unread-badge { background: #ef4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; }
        .no-notifications { text-align: center; padding: 60px 20px; color: #6b7280; }
        .pagination { display: flex; justify-content: center; margin-top: 30px; }
        .pagination a { padding: 8px 16px; margin: 0 4px; background: white; color: #2563eb; text-decoration: none; border-radius: 6px; border: 1px solid #e2e8f0; }
        .pagination a:hover { background: #2563eb; color: white; }
        .pagination .active { background: #2563eb; color: white; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        @media (max-width: 768px) {
            .nav-menu { display: none; }
            .page-header { flex-direction: column; gap: 15px; align-items: flex-start; }
            .notification-header { flex-direction: column; gap: 8px; }
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
                        <li><a href="{{ route('my.posts') }}">MY Posts</a></li>
                        <li><a href="{{ route('requirements.create') }}">Post Requirements</a></li>
                        <li><a href="{{ route('notifications.index') }}" class="active">Notifications</a></li>
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

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="page-header">
            <h1>Notifications @if($unreadCount > 0)<span class="unread-badge">{{ $unreadCount }}</span>@endif</h1>
            @if($unreadCount > 0)
                <a href="{{ route('notifications.mark-all-read') }}" class="btn btn-primary">Mark All as Read</a>
            @endif
        </div>

        @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->is_read ? '' : 'unread' }}" 
                 onclick="markAsRead({{ $notification->id }})" style="cursor: pointer;">
                <div class="notification-header">
                    <div>
                        <div class="notification-title">
                            {{ $notification->title }}
                            @if(!$notification->is_read)
                                <span class="unread-badge">New</span>
                            @endif
                        </div>
                        <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                
                <div class="notification-message">{{ $notification->message }}</div>
                
                @if($notification->type === 'job_application' && $notification->data)
                    <div class="notification-details">
                        <h4>Job Details:</h4>
                        <p><strong>Position:</strong> {{ $notification->data['job_title'] ?? 'N/A' }}</p>
                        <p><strong>Teacher:</strong> {{ $notification->data['teacher_name'] ?? 'N/A' }}</p>
                        <p><strong>Location:</strong> {{ $notification->data['job_location'] ?? 'N/A' }}</p>
                        <p><strong>Salary:</strong> ৳{{ number_format($notification->data['job_salary'] ?? 0, 2) }}/hr</p>
                        
                        @if(isset($notification->data['application_message']))
                            <div class="application-message">
                                <strong>Teacher's Message:</strong><br>
                                "{{ $notification->data['application_message'] }}"
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="no-notifications">
                <i class="fas fa-bell-slash" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                <h3>No notifications yet</h3>
                <p>You'll see notifications here when teachers apply for your job posts.</p>
            </div>
        @endforelse

        @if($notifications->hasPages())
            <div class="pagination">
                {{ $notifications->links() }}
            </div>
        @endif
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

        // Mark notification as read
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove unread styling
                    const notificationElement = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                    if (notificationElement) {
                        notificationElement.classList.remove('unread');
                        const badge = notificationElement.querySelector('.unread-badge');
                        if (badge) {
                            badge.remove();
                        }
                    }
                    
                    // Update header badge
                    updateUnreadCount();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Update unread count in header
        function updateUnreadCount() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const headerBadge = document.querySelector('.page-header .unread-badge');
                    if (data.count > 0) {
                        if (headerBadge) {
                            headerBadge.textContent = data.count;
                        }
                    } else {
                        if (headerBadge) {
                            headerBadge.remove();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>