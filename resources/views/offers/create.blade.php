<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Post Requirements - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; }
        .wrap { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,.06); padding: 24px 28px; }
        h1 { margin: 0 0 20px; font-size: 26px; color: #111827; }
        .row { margin-bottom: 16px; display: grid; grid-template-columns: 220px 1fr; gap: 12px; align-items: start; }
        label { font-weight: 600; color: #374151; padding-top: 10px; }
        input[type=text], input[type=number], textarea, select { width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; }
        textarea { min-height: 140px; }
        .muted { color: #6b7280; font-size: 13px; margin-top: 6px; }
        .checks { display: flex; gap: 16px; }
        .btn { padding: 12px 16px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        .error { color: #b91c1c; background: #fee2e2; border: 1px solid #fecaca; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
        .success { color: #065f46; background: #d1fae5; border: 1px solid #a7f3d0; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Request a tutor</h1>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('requirements.store') }}">
            @csrf

            <div class="row">
                <label>Location</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="City, Area" required>
            </div>

            <div class="row">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
            </div>

            <div class="row">
                <label>Details of your requirement</label>
                <div>
                    <textarea name="description" placeholder="Please don't share any contact details here" required>{{ old('description') }}</textarea>
                    <div class="muted">Please don't share any contact details (phone, email, website etc) here</div>
                </div>
            </div>

            <div class="row">
                <label>Subjects</label>
                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Enter subjects" required>
            </div>

            <div class="row">
                <label>Your level</label>
                <input type="text" name="class_level" value="{{ old('class_level') }}" placeholder="Grade / Class / Level" required>
            </div>

            <div class="row">
                <label>I want</label>
                <select name="type" required>
                    <option value="">-- Select --</option>
                    <option value="assignment_help" {{ old('type')==='assignment_help'?'selected':'' }}>assignment_help</option>
                    <option value="tutoring" {{ old('type')==='tutoring'?'selected':'' }}>tutoring</option>
                </select>
            </div>

            <div class="row">
                <label>Meeting options</label>
                <select name="preferred_type" required>
                    <option value="online" {{ old('preferred_type')==='online'?'selected':'' }}>online</option>
                    <option value="offline" {{ old('preferred_type')==='offline'?'selected':'' }}>offline</option>
                    <option value="both" {{ old('preferred_type')==='both'?'selected':'' }}>both</option>
                </select>
            </div>

            <div class="row">
                <label>Salary (per hour)</label>
                <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" placeholder="e.g., 500" required>
            </div>

            <div class="row" style="grid-template-columns: 1fr;">
                <button type="submit" class="btn">Continue</button>
            </div>
        </form>
    </div>
</body>
</html>


