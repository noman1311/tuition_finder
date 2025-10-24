<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Posts - Admin</title>
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
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.posts') }}" class="active">Posts</a></li>
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
        <h1>Manage Posts</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filters -->
        <div class="admin-filters">
            <form method="GET" action="{{ route('admin.posts') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All Statuses</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by subject, location, or description...">
                    </div>
                    <button type="submit" class="filter-btn">Filter</button>
                </div>
            </form>
        </div>

        <!-- Posts Table -->
        <div class="admin-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Student</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>#{{ $post->offer_id }}</td>
                            <td>
                                <strong>{{ $post->subject }}</strong><br>
                                <small>{{ $post->class_level }}</small>
                            </td>
                            <td>{{ $post->student->user->username ?? 'Unknown' }}</td>
                            <td>{{ $post->location }}</td>
                            <td>${{ number_format($post->salary, 2) }}/hr</td>
                            <td>
                                <span class="status-badge status-{{ $post->status }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->created_at->format('M d, Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.posts.update-status', $post->offer_id) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="action-btn primary">
                                        <option value="open" {{ $post->status == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ $post->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="in_progress" {{ $post->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $post->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #6b7280;">
                                No posts found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $posts->links() }}
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