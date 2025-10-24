<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Teachers - Admin</title>
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
                    <li><a href="{{ route('admin.applications') }}">Applications</a></li>
                    <li><a href="{{ route('admin.students') }}">Students</a></li>
                    <li><a href="{{ route('admin.teachers') }}" class="active">Teachers</a></li>
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
        <h1>Manage Teachers</h1>

        <!-- Filters -->
        <div class="admin-filters">
            <form method="GET" action="{{ route('admin.teachers') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, email, username, location, or subject...">
                    </div>
                    <button type="submit" class="filter-btn">Search</button>
                </div>
            </form>
        </div>

        <!-- Teachers Table -->
        <div class="admin-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Experience</th>
                        <th>Subjects</th>
                        <th>Location</th>
                        <th>Coins</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>#{{ $teacher->teacher_id }}</td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->user->username ?? 'N/A' }}</td>
                            <td>{{ $teacher->user->email ?? 'N/A' }}</td>
                            <td>{{ $teacher->experience }} years</td>
                            <td>
                                <div style="max-width: 150px; overflow: hidden;">
                                    @if(is_array($teacher->subject_expertise))
                                        {{ implode(', ', array_slice($teacher->subject_expertise, 0, 2)) }}
                                        @if(count($teacher->subject_expertise) > 2)
                                            <small>+{{ count($teacher->subject_expertise) - 2 }} more</small>
                                        @endif
                                    @else
                                        {{ $teacher->subject_expertise }}
                                    @endif
                                </div>
                            </td>
                            <td>{{ $teacher->location }}</td>
                            <td>
                                <span style="color: #f59e0b; font-weight: 600;">
                                    <i class="fas fa-coins"></i> {{ $teacher->coins }}
                                </span>
                            </td>
                            <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: #6b7280;">
                                No teachers found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $teachers->links() }}
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