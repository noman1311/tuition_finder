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
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

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
                        <li>
                            <a href="{{ route('notifications.index') }}">
                                Notifications
                                @if(Auth::user()->unread_notifications_count > 0)
                                    <span style="background: #ef4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; margin-left: 4px;">
                                        {{ Auth::user()->unread_notifications_count }}
                                    </span>
                                @endif
                            </a>
                        </li>
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
                        @if(Auth::user()->role === 'teacher')
                            <div style="padding:8px 12px; background:#f8fafc; border-bottom:1px solid #e5e7eb; font-size:12px; color:#6b7280; font-weight:600;">DASHBOARD</div>
                            <a href="{{ route('teacher.dashboard') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Teacher Dashboard</a>
                            @if(session('current_view') === 'teacher')
                                <a href="{{ route('switch.to.student') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Switch to Student View</a>
                            @else
                                <a href="{{ route('switch.to.teacher') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Switch to Teacher View</a>
                            @endif
                            <div style="border-top:1px solid #e5e7eb; margin:4px 0;"></div>
                        @endif
                        @if(Auth::user()->role === 'student/parent' || (Auth::user()->role === 'teacher' && session('current_view') === 'student'))
                            <a href="{{ route('student.profile.edit') }}" style="display:block; padding:10px 12px; color:#111827; text-decoration:none;">Edit profile</a>
                        @elseif(Auth::user()->role === 'teacher')
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
