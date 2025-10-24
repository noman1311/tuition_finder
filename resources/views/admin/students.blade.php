<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Students - Admin</title>
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
                    <li><a href="{{ route('admin.students') }}" class="active">Students</a></li>
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
        <h1>Manage Students</h1>

        <!-- Filters -->
        <div class="admin-filters">
            <form method="GET" action="{{ route('admin.students') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, email, username, or location...">
                    </div>
                    <button type="submit" class="filter-btn">Search</button>
                </div>
            </form>
        </div>

        <!-- Students Table -->
        <div class="admin-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Class Level</th>
                        <th>Location</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>#{{ $student->student_id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->user->username ?? 'N/A' }}</td>
                            <td>{{ $student->user->email ?? 'N/A' }}</td>
                            <td>{{ ucfirst($student->gender) }}</td>
                            <td>{{ $student->class_level }}</td>
                            <td>{{ $student->location }}</td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #6b7280;">
                                No students found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $students->links() }}
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