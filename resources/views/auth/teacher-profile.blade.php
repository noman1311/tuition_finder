<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Teacher Profile - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profile-forms.css') }}">
</head>
<body>
    <div class="container">
        <h1>Complete Your Teacher Profile</h1>
        <p class="info">Please provide your teaching information to complete your profile.</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('teacher.profile.complete') }}">
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
                <label for="subject_expertise">Subject Expertise</label>
                <input id="subject_expertise" name="subject_expertise" type="text" value="{{ old('subject_expertise') }}" placeholder="e.g., Mathematics, Physics, English" required>
                <div class="help-text">Separate multiple subjects with commas</div>
            </div>
            <div class="form-group">
                <label for="experience">Years of Experience</label>
                <input id="experience" name="experience" type="number" value="{{ old('experience') }}" min="0" required>
            </div>
            <div class="form-group">
                <label for="current_education_institution">Current Education Institution</label>
                <input id="current_education_institution" name="current_education_institution" type="text" value="{{ old('current_education_institution') }}" placeholder="e.g., University of Dhaka, MIT, Harvard University">
                <div class="help-text">Your current educational institution or workplace</div>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="City, Area" required>
            </div>
            <div class="form-group">
                <label for="preferred_type">Preferred Teaching Type</label>
                <select id="preferred_type" name="preferred_type" required>
                    <option value="">-- Select --</option>
                    <option value="online" {{ old('preferred_type')=='online'?'selected':'' }}>Online</option>
                    <option value="offline" {{ old('preferred_type')=='offline'?'selected':'' }}>Offline</option>
                    <option value="both" {{ old('preferred_type')=='both'?'selected':'' }}>Both</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description" placeholder="Tell students about your teaching style and experience...">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn">Complete Profile</button>
        </form>
    </div>
</body>
</html>
