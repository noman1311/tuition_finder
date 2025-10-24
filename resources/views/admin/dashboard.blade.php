<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('admin.dashboard') }}" class="logo">TuitionFinder Admin</a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a></li>
                    <li><a href="{{ route('admin.posts') }}">Posts</a></li>
                    <li><a href="{{ route('admin.applications') }}">Applications</a></li>
                    <li><a href="{{ route('admin.students') }}">Students</a></li>
                    <li><a href="{{ route('admin.teachers') }}">Teachers</a></li>
                    <li><a href="{{ route('admin.users') }}">Users</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" id="userMenuTrigger">
                    <div class="user-avatar">A</div>
                    <span>Admin</span>
                </div>
                <div class="dropdown" id="userDropdown">
                    <a href="{{ route('home') }}">View Site</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="admin-container">
        <h1>Admin Dashboard</h1>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_users'] }}</h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_students'] }}</h3>
                    <p>Students</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_teachers'] }}</h3>
                    <p>Teachers</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_posts'] }}</h3>
                    <p>Total Posts</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['open_posts'] }}</h3>
                    <p>Open Posts</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_applications'] }}</h3>
                    <p>Applications</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <div class="activity-card">
                <h2><i class="fas fa-clipboard-list"></i> Recent Posts</h2>
                <div class="activity-list">
                    @forelse($recent_posts as $post)
                        <div class="activity-item">
                            <div class="activity-info">
                                <h4>{{ $post->subject }} - {{ $post->class_level }}</h4>
                                <p>by {{ $post->student->user->username ?? 'Unknown' }} • {{ $post->location }}</p>
                                <small>{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="activity-status">
                                <span class="status-badge status-{{ $post->status }}">{{ ucfirst($post->status) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="no-data">No recent posts</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.posts') }}" class="view-all-btn">View All Posts</a>
            </div>

            <div class="activity-card">
                <h2><i class="fas fa-paper-plane"></i> Recent Applications</h2>
                <div class="activity-list">
                    @forelse($recent_applications as $application)
                        <div class="activity-item">
                            <div class="activity-info">
                                <h4>{{ $application->teacher->name ?? 'Unknown Teacher' }}</h4>
                                <p>Applied for {{ $application->tuitionOffer->subject ?? 'Unknown Subject' }}</p>
                                <small>{{ $application->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="activity-status">
                                <span class="status-badge status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="no-data">No recent applications</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.applications') }}" class="view-all-btn">View All Applications</a>
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