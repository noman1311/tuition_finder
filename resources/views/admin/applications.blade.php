<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Applications - Admin</title>
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
                    <li><a href="{{ route('admin.posts') }}">Posts</a></li>
                    <li><a href="{{ route('admin.applications') }}" class="active">Applications</a></li>
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
        <h1>Manage Applications</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filters -->
        <div class="admin-filters">
            <form method="GET" action="{{ route('admin.applications') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by teacher name or subject...">
                    </div>
                    <button type="submit" class="filter-btn">Filter</button>
                </div>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="admin-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Teacher</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>#{{ $application->application_id }}</td>
                            <td>{{ $application->teacher->name ?? 'Unknown' }}</td>
                            <td>
                                <strong>{{ $application->tuitionOffer->subject ?? 'Unknown' }}</strong><br>
                                <small>{{ $application->tuitionOffer->class_level ?? '' }}</small>
                            </td>
                            <td>
                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                    {{ Str::limit($application->message, 50) }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $application->status }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.applications.update-status', $application->application_id) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="action-btn primary">
                                        <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="withdrawn" {{ $application->status == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                No applications found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
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