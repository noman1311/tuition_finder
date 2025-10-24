<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Teacher Profile - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profile-forms.css') }}">
</head>
<body>
    <div class="container">
        <h1>Edit Teacher Profile</h1>
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('teacher.profile.update') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $teacher->name) }}" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">-- Select --</option>
                    <option value="male" {{ old('gender', $teacher->gender)=='male'?'selected':'' }}>Male</option>
                    <option value="female" {{ old('gender', $teacher->gender)=='female'?'selected':'' }}>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="subject_expertise">Subject Expertise</label>
                <input id="subject_expertise" name="subject_expertise" type="text" value="{{ old('subject_expertise', is_array($teacher->subject_expertise) ? implode(', ', $teacher->subject_expertise) : $teacher->subject_expertise) }}" required>
                <div class="help-text">Separate multiple subjects with commas (e.g., Mathematics, Physics, Chemistry)</div>
            </div>
            <div class="form-group">
                <label for="experience">Years of Experience</label>
                <input id="experience" name="experience" type="number" min="0" value="{{ old('experience', $teacher->experience) }}" required>
            </div>
            <div class="form-group">
                <label for="current_education_institution">Current Education Institution</label>
                <input id="current_education_institution" name="current_education_institution" type="text" value="{{ old('current_education_institution', $teacher->current_education_institution) }}" placeholder="e.g., University of Dhaka, MIT, Harvard University">
                <div class="help-text">Your current educational institution or workplace</div>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location', $teacher->location) }}" required>
            </div>
            <div class="form-group">
                <label for="preferred_type">Preferred Teaching Type</label>
                <select id="preferred_type" name="preferred_type" required>
                    <option value="">-- Select --</option>
                    <option value="online" {{ old('preferred_type', $teacher->preferred_type)=='online'?'selected':'' }}>Online</option>
                    <option value="offline" {{ old('preferred_type', $teacher->preferred_type)=='offline'?'selected':'' }}>Offline</option>
                    <option value="both" {{ old('preferred_type', $teacher->preferred_type)=='both'?'selected':'' }}>Both</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Tell students about your teaching style and experience...">{{ old('description', $teacher->description) }}</textarea>
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</body>
</html>
