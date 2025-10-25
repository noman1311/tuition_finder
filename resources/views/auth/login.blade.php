<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Join - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="container">
        <h1>Login | Join</h1>
        
        <div class="tabs">
            <button class="tab active" onclick="showTab('login')">Login</button>
            <button class="tab" onclick="showTab('join')">Join</button>
        </div>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <!-- Login Form -->
        <div id="login-form" class="form-section active">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="Enter your name or email" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>

        <!-- Join Form -->
        <div id="join-form" class="form-section">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required>
                </div>
                <div class="form-group">
                    <label for="role">I am a</label>
                    <select id="role" name="role" required>
                        <option value="">-- Select --</option>
                        <option value="student/parent" {{ old('role')=='student/parent'?'selected':'' }}>Student/Parent</option>
                        <option value="teacher" {{ old('role')=='teacher'?'selected':'' }}>Teacher</option>
                    </select>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>

        <div class="back-link">
            <a href="{{ route('home') }}">Back to home</a>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all form sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected form and activate tab
            document.getElementById(tabName + '-form').classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>