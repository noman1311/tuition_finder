<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results - TuitionFinder</title>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .search-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .back-btn {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .search-results-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
        }

        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .teacher-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .teacher-card:hover {
            transform: translateY(-2px);
        }

        .teacher-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .teacher-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .teacher-info h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .teacher-location {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .teacher-expertise {
            margin-bottom: 15px;
        }

        .expertise-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .expertise-tag {
            background: #dbeafe;
            color: #2563eb;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .teacher-bio {
            color: #6b7280;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .teacher-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .teacher-rate {
            font-size: 1.1rem;
            font-weight: 600;
            color: #059669;
        }

        .contact-btn {
            background: #2563eb;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .contact-btn:hover {
            background: #1d4ed8;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #d1d5db;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            background: white;
            color: #2563eb;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .pagination a:hover {
            background: #2563eb;
            color: white;
        }

        .pagination .active {
            background: #2563eb;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="search-header">
                <a href="/" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
                <h1 class="search-results-title">
                    Search Results
                    @if(request('location') || request('subject'))
                        for
                        @if(request('location'))
                            "{{ request('location') }}"
                        @endif
                        @if(request('location') && request('subject'))
                            and
                        @endif
                        @if(request('subject'))
                            "{{ request('subject') }}"
                        @endif
                    @endif
                </h1>
            </div>
        </div>
    </div>

    <div class="container">
        @if($teachers->count() > 0)
            <div class="teachers-grid">
                @foreach($teachers as $teacher)
                    <div class="teacher-card">
                        <div class="teacher-header">
                            <div class="teacher-avatar">
                                {{ substr($teacher->name, 0, 1) }}
                            </div>
                            <div class="teacher-info">
                                <h3>{{ $teacher->name }}</h3>
                                <div class="teacher-location">
                                    <i class="fas fa-map-marker-alt"></i> {{ $teacher->location }}
                                </div>
                            </div>
                        </div>

                        @if($teacher->expertise)
                            <div class="teacher-expertise">
                                <div class="expertise-tags">
                                    @foreach($teacher->expertise as $skill)
                                        <span class="expertise-tag">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($teacher->bio)
                            <div class="teacher-bio">
                                {{ Str::limit($teacher->bio, 120) }}
                            </div>
                        @endif

                        <div class="teacher-footer">
                            @if($teacher->hourly_rate)
                                <div class="teacher-rate">${{ $teacher->hourly_rate }}/hr</div>
                            @else
                                <div class="teacher-rate">Rate on request</div>
                            @endif
                            <button class="contact-btn">Contact</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination">
                {{ $teachers->links() }}
            </div>
        @else
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>No tutors found</h3>
                <p>Try adjusting your search criteria or browse all available tutors.</p>
                <a href="/" class="btn" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px;">
                    Browse All Tutors
                </a>
            </div>
        @endif
    </div>
</body>
</html>
