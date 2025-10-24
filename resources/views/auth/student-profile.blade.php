<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Student Profile - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profile-forms.css') }}">
</head>
<body>
    <div class="container">
        <h1>Complete Your Student Profile</h1>
        <p class="info">Please provide some additional information to complete your profile.</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('student.profile.complete') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">-- Select --</option>
                    <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                    <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="class_level">Class/Level</label>
                <input id="class_level" name="class_level" type="text" value="{{ old('class_level') }}" placeholder="e.g., Grade 10, Class 12, University Level" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="City, Area" required>
            </div>
            <button type="submit" class="btn">Complete Profile</button>
        </form>
    </div>
</body>
</html>
