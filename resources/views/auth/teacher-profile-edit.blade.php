<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Teacher Profile - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,.08); padding: 32px; width: 100%; max-width: 500px; }
        h1 { margin: 0 0 20px; font-size: 24px; color: #111827; text-align: center; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; }
        input, select, textarea { width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; box-sizing: border-box; }
        textarea { min-height: 80px; resize: vertical; }
        .btn { width: 100%; padding: 12px 16px; background: #2563eb; border: none; color: #fff; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        .error { color: #b91c1c; background: #fee2e2; border: 1px solid #fecaca; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
        .help-text { font-size: 12px; color: #6b7280; margin-top: 4px; }
    </style>
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
