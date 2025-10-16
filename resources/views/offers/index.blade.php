<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Posts - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; }
        .wrap { max-width: 1000px; margin: 40px auto; }
        h1 { margin: 0 0 16px; font-size: 26px; color: #111827; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,.06); padding: 18px 20px; margin-bottom: 14px; }
        .row { display: flex; justify-content: space-between; gap: 12px; }
        .tag { background:#dbeafe; color:#1d4ed8; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .muted { color:#6b7280; font-size: 13px; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>My Posts</h1>
        @forelse ($offers as $offer)
            <div class="card">
                <div class="row">
                    <div>
                        <div class="tag">{{ $offer->status }}</div>
                        <h3 style="margin:8px 0 6px;">{{ $offer->subject }} — {{ $offer->class_level }}</h3>
                        <div class="muted">{{ $offer->location }} • {{ $offer->preferred_type }} • {{ $offer->type }}</div>
                    </div>
                    <div style="font-weight:700; color:#065f46;">৳ {{ number_format($offer->salary,2) }}/hr</div>
                </div>
                <p style="margin-top:10px; color:#374151;">{{ $offer->description }}</p>
            </div>
        @empty
            <p class="muted">No posts yet. <a href="{{ route('requirements.create') }}">Create one</a>.</p>
        @endforelse

        <div style="margin-top:16px;">{{ $offers->links() }}</div>
    </div>
</body>
</html>


