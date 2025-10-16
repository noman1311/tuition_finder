<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; }
        .container { max-width: 420px; margin: 80px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,.08); padding: 32px; }
        h1 { margin: 0 0 8px; font-size: 26px; color: #111827; }
        p { margin: 0 0 24px; color: #6b7280; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; }
        .btn { width: 100%; padding: 12px 16px; background: #2563eb; border: none; color: #fff; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        .error { color: #b91c1c; background: #fee2e2; border: 1px solid #fecaca; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
        .helper { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; font-size: 14px; }
        a { color: #2563eb; text-decoration: none; }
    </style>
    <script>
        function goHome() { window.location.href = '/'; }
    </script>
 </head>
<body>
    <div class="container">
        <h1>Welcome back</h1>
        <p>Sign in to your account</p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button type="submit" class="btn">Sign in</button>
            <div class="helper">
                <a href="javascript:goHome()">Back to home</a>
            </div>
        </form>
    </div>
</body>
</html>


