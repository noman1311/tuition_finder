<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Users - Admin</title>
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
                    <li><a href="{{ route('admin.teachers') }}">Teachers</a></li>
                    <li><a href="{{ route('admin.users') }}" class="active">Users</a></li>
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
        <h1>Manage Users</h1>

        <!-- Filters -->
        <div class="admin-filters">
            <form method="GET" action="{{ route('admin.users') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="role">Role</label>
                        <select name="role" id="role">
                            <option value="">All Roles</option>
                            <option value="student/parent" {{ request('role') == 'student/parent' ? 'selected' : '' }}>Student/Parent</option>
                            <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by username or email...">
                    </div>
                    <button type="submit" class="filter-btn">Filter</button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="admin-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>#{{ $user->user_id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'Not provided' }}</td>
                            <td>
                                <span class="status-badge status-{{ str_replace('/', '_', $user->role) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>{{ $user->updated_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                No users found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $users->links() }}
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