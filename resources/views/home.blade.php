<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TuitionFinder - Find the Best Tutors</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }

        /* Header Styles */
        .header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 30px;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: #4b5563;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: #2563eb;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-outline {
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
        }

        .btn-outline:hover {
            background: #2563eb;
            color: white;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 6px;
            background: #f1f5f9;
            cursor: pointer;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .hero-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .search-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .search-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .search-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: #1d4ed8;
        }

        /* Sticky Search Bar */
        .sticky-search {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: white;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 999;
            transform: translateY(-100%);
            transition: transform 0.3s;
        }

        .sticky-search.visible {
            transform: translateY(0);
        }

        .sticky-search-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .sticky-search .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
        }

        .sticky-search .search-btn {
            padding: 12px 20px;
            font-size: 14px;
        }

        /* Topics Section */
        .topics-section {
            padding: 60px 0;
            background: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 50px;
        }

        .topics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .topic-card {
            background: #f8fafc;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .topic-card:hover {
            background: #e0f2fe;
            border-color: #2563eb;
            transform: translateY(-2px);
        }

        .topic-icon {
            font-size: 2.5rem;
            color: #2563eb;
            margin-bottom: 15px;
        }

        .topic-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
        }

        /* Recent Posts Section */
        .posts-section {
            padding: 60px 0;
            background: #f8fafc;
        }

        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }

        .post-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .post-card:hover {
            transform: translateY(-2px);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .post-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .post-subject {
            background: #dbeafe;
            color: #2563eb;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .post-description {
            color: #6b7280;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #9ca3af;
        }

        .post-location {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .post-budget {
            font-weight: 600;
            color: #059669;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .search-row {
                flex-direction: column;
            }

            .sticky-search-container {
                flex-direction: column;
            }

            .topics-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .posts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo">TuitionFinder</a>
            
            <nav>
                <ul class="nav-menu">
                    @auth
                        <li><a href="{{ route('my.posts') }}">MY Posts</a></li>
                        <li><a href="{{ route('requirements.create') }}">Post Requirements</a></li>
                        <li><a href="{{ route('find.tutors') }}">Find Tutors</a></li>
                        <li><a href="{{ route('notifications') }}">Notifications</a></li>
                    @endauth
                </ul>
            </nav>

            <div class="user-menu" style="position: relative;">
                @if(Auth::check())
                    <div class="user-profile" id="userMenuTrigger" style="cursor:pointer;">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->username,0,1)) }}</div>
                        <span>{{ Auth::user()->username }}</span>
                    </div>
                    <div id="userDropdown" style="display:none; position:absolute; right:0; top:56px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 20px rgba(0,0,0,.08); min-width:180px;">
                        @if(Auth::user()->role === 'student')
                            <a href="{{ route('student.profile.edit') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Edit profile</a>
                        @else
                            <a href="{{ route('teacher.profile.edit') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Edit profile</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" style="width:100%; text-align:left; padding:10px 12px; background:none; border:none; cursor:pointer; color:#111827;">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Login / Signup</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Sticky Search Bar -->
    <div class="sticky-search" id="stickySearch">
        <div class="sticky-search-container">
            <input type="text" class="search-input" placeholder="Search by location..." id="stickyLocation">
            <input type="text" class="search-input" placeholder="Search by subject..." id="stickySubject">
            <button class="search-btn" onclick="performStickySearch()">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <h1>Find the Perfect Tutor for You</h1>
            <p>Connect with qualified tutors in your area and get personalized learning experiences</p>
            
            <form class="search-form" action="/search" method="GET" @if(!Auth::check()) onsubmit="event.preventDefault(); window.location='{{ route('login') }}'" @endif>
                <div class="search-row">
                    <input type="text" name="location" class="search-input" placeholder="Enter your location..." value="{{ request('location') }}">
                    <input type="text" name="subject" class="search-input" placeholder="Enter subject or topic..." value="{{ request('subject') }}">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Find Tutors
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Topics Section -->
    <section class="topics-section">
        <div class="container">
            <h2 class="section-title">Popular Subjects</h2>
            <p class="section-subtitle">Find tutors for your favorite subjects</p>
            
            <div class="topics-grid">
                @forelse($topics as $topic)
                    <div class="topic-card" @if(!Auth::check()) onclick="window.location='{{ route('login') }}'" @else onclick="searchByTopic('{{ $topic }}')" @endif>
                        <div class="topic-icon">
                            @if(str_contains(strtolower($topic), 'math'))
                                <i class="fas fa-calculator"></i>
                            @elseif(str_contains(strtolower($topic), 'science'))
                                <i class="fas fa-flask"></i>
                            @elseif(str_contains(strtolower($topic), 'english'))
                                <i class="fas fa-book"></i>
                            @elseif(str_contains(strtolower($topic), 'computer'))
                                <i class="fas fa-laptop-code"></i>
                            @elseif(str_contains(strtolower($topic), 'music'))
                                <i class="fas fa-music"></i>
                            @elseif(str_contains(strtolower($topic), 'art'))
                                <i class="fas fa-palette"></i>
                            @else
                                <i class="fas fa-graduation-cap"></i>
                            @endif
                        </div>
                        <div class="topic-name">{{ $topic }}</div>
                    </div>
                @empty
                    <div class="topic-card">
                        <div class="topic-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="topic-name">Mathematics</div>
                    </div>
                    <div class="topic-card">
                        <div class="topic-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="topic-name">Science</div>
                    </div>
                    <div class="topic-card">
                        <div class="topic-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="topic-name">English</div>
                    </div>
                    <div class="topic-card">
                        <div class="topic-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div class="topic-name">Programming</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Recent Posts Section -->
    <section class="posts-section">
        <div class="container">
            <h2 class="section-title">Recent Tuition Posts</h2>
            <p class="section-subtitle">Latest requirements from students</p>
            
            <div class="posts-grid">
                @forelse($recentPosts as $post)
                    <div class="post-card" @if(!Auth::check()) onclick="window.location='{{ route('login') }}'" @endif style="cursor:pointer;">
                        <div class="post-header">
                            <div>
                                <h3 class="post-title">{{ $post->title }}</h3>
                                <span class="post-subject">{{ $post->subject }}</span>
                            </div>
                        </div>
                        <p class="post-description">{{ Str::limit($post->description, 120) }}</p>
                        <div class="post-meta">
                            <div class="post-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $post->location }}
                            </div>
                            @if($post->salary)
                                <div class="post-budget">${{ $post->salary }}/hr</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="post-card" @if(!Auth::check()) onclick="window.location='{{ route('login') }}'" @endif style="cursor:pointer;">
                        <h3 class="post-title">Mathematics Tutoring Needed</h3>
                        <span class="post-subject">Mathematics</span>
                        <p class="post-description">Looking for a qualified math tutor to help with calculus and algebra...</p>
                        <div class="post-meta">
                            <div class="post-location">
                                <i class="fas fa-map-marker-alt"></i>
                                New York, NY
                            </div>
                            <div class="post-budget">$25/hr</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script>
        // Sticky search bar functionality
        window.addEventListener('scroll', function() {
            const stickySearch = document.getElementById('stickySearch');
            const hero = document.querySelector('.hero');
            const heroBottom = hero.offsetTop + hero.offsetHeight;
            
            if (window.scrollY > heroBottom) {
                stickySearch.classList.add('visible');
            } else {
                stickySearch.classList.remove('visible');
            }
        });

        // Search by topic
        function searchByTopic(topic) {
            const form = document.querySelector('.search-form');
            const subjectInput = form.querySelector('input[name="subject"]');
            subjectInput.value = topic;
            form.submit();
        }

        // Sticky search functionality
        function performStickySearch() {
            const location = document.getElementById('stickyLocation').value;
            const subject = document.getElementById('stickySubject').value;

            const url = new URL('/search', window.location.origin);
            if (location) url.searchParams.set('location', location);
            if (subject) url.searchParams.set('subject', subject);

            // If guest, redirect to login instead of search
            const isGuest = {{ auth()->check() ? 'false' : 'true' }};
            if (isGuest) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            window.location.href = url.toString();
        }

        // Sync sticky search with main search
        document.addEventListener('DOMContentLoaded', function() {
            const mainLocation = document.querySelector('input[name="location"]');
            const mainSubject = document.querySelector('input[name="subject"]');
            const stickyLocation = document.getElementById('stickyLocation');
            const stickySubject = document.getElementById('stickySubject');

            if (mainLocation && stickyLocation) {
                mainLocation.addEventListener('input', function() {
                    stickyLocation.value = this.value;
                });
            }

            if (mainSubject && stickySubject) {
                mainSubject.addEventListener('input', function() {
                    stickySubject.value = this.value;
                });
            }

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
        });
    </script>
</body>
</html>
