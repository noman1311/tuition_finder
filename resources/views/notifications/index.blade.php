<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifications - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">

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
            <div style="display: flex; gap: 15px; align-items: center;">
                @if(Auth::user()->role === 'teacher')
                    @php
                        $teacher = \App\Models\Teacher::where('user_id', Auth::id())->first();
                    @endphp
                    @if($teacher)
                        <div style="background: #f59e0b; color: white; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 14px;">
                            <i class="fas fa-coins"></i> {{ $teacher->coins }} coins
                        </div>
                    @endif
                @endif
                @if($unreadCount > 0)
                    <a href="{{ route('notifications.mark-all-read') }}" class="btn btn-primary">Mark All as Read</a>
                @endif
            </div>
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
                @elseif($notification->type === 'contact_request' && $notification->data)
                    <div class="notification-details">
                        <h4>Contact Request Details:</h4>
                        <p><strong>Student:</strong> {{ $notification->data['student_name'] ?? 'N/A' }}</p>
                        <p><strong>Cost to reveal contact:</strong> <i class="fas fa-coins"></i> {{ $notification->data['contact_cost'] ?? 10 }} coins</p>
                        
                        @if(isset($notification->data['message']))
                            <div class="application-message">
                                <strong>Student's Message:</strong><br>
                                "{{ $notification->data['message'] }}"
                            </div>
                        @endif
                        
                        <div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                            <button onclick="revealContact({{ $notification->id }}, {{ $notification->data['contact_cost'] ?? 10 }})" 
                                    class="btn btn-primary" style="font-size: 14px; padding: 8px 16px;">
                                <i class="fas fa-coins"></i> Pay {{ $notification->data['contact_cost'] ?? 10 }} coins to reveal contact
                            </button>
                            <a href="{{ route('wallet.index') }}" class="btn" style="font-size: 14px; padding: 8px 16px; background: #f59e0b; color: white; text-decoration: none;">
                                <i class="fas fa-plus"></i> Add Coins
                            </a>
                        </div>
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

        // Reveal contact information
        function revealContact(notificationId, cost) {
            if (!confirm(`Are you sure you want to pay ${cost} coins to reveal this student's contact information?`)) {
                return;
            }

            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;

            fetch('/reveal-contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    notification_id: notificationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Replace button with contact information
                    button.parentElement.innerHTML = `
                        <div style="background: #d1fae5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px; margin-top: 10px;">
                            <h4 style="color: #065f46; margin-bottom: 8px;"><i class="fas fa-check-circle"></i> Contact Information Revealed</h4>
                            <p style="color: #065f46; margin-bottom: 4px;"><strong>Phone:</strong> ${data.contact_info.phone}</p>
                            <p style="color: #065f46; font-size: 12px;">You have been charged ${cost} coins for this information.</p>
                        </div>
                    `;
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    </script>
</body>
</html>