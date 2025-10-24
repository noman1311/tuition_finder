<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Jobs - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jobs.css') }}">

</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">TuitionFinder</a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('teacher.jobs.my') }}" class="active">Jobs</a></li>
                    <li><a href="{{ route('wallet.index') }}">Wallet</a></li>
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
                    <li><a href="{{ route('teacher.profile.edit') }}">Edit Profile</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" id="userMenuTrigger">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->username,0,1)) }}</div>
                    <span>{{ Auth::user()->username }}</span>
                </div>
                <div class="dropdown" id="userDropdown">
                    <a href="{{ route('switch.to.student') }}">Switch to Student View</a>
                    <a href="{{ route('teacher.profile.edit') }}">Edit Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1>Jobs</h1>
            <p>Manage your teaching opportunities</p>
        </div>

        <div class="job-tabs">
            <a href="{{ route('teacher.jobs.my') }}" class="job-tab">Applied Jobs</a>
            <a href="{{ route('teacher.jobs.all') }}" class="job-tab active">All Jobs</a>
            <a href="{{ route('teacher.jobs.online') }}" class="job-tab">Online Jobs</a>
        </div>

        <div class="filters">
            <form method="GET" action="{{ route('teacher.jobs.all') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" value="{{ request('location') }}" placeholder="Filter by location">
                    </div>
                    <div class="filter-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ request('subject') }}" placeholder="Filter by subject">
                    </div>
                    <button type="submit" class="filter-btn">Filter</button>
                </div>
            </form>
        </div>

        @forelse($jobs as $job)
            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">{{ $job->subject }} - {{ $job->class_level }}</h3>
                        <span class="job-subject">{{ $job->type }}</span>
                    </div>
                </div>
                <div class="job-meta">
                    <i class="fas fa-map-marker-alt"></i> {{ $job->location }} • 
                    <i class="fas fa-clock"></i> {{ $job->preferred_type }} • 
                    <i class="fas fa-dollar-sign"></i> ৳{{ number_format($job->salary, 2) }}/hr •
                    <i class="fas fa-phone"></i> {{ $job->hidden_phone }}
                </div>
                <div class="job-description">{{ $job->description }}</div>
                @if($teacher->coins >= config('tuition.application_cost', 10))
                    <button class="apply-btn" onclick="openApplyModal({{ $job->offer_id }})">
                        Apply Now ({{ config('tuition.application_cost', 10) }} coins)
                    </button>
                @else
                    <div style="margin-top: 12px; padding: 8px 12px; background: #fee2e2; border-radius: 6px; color: #dc2626; font-size: 14px;">
                        <i class="fas fa-exclamation-triangle"></i> Can't apply - Not enough coins (Need {{ config('tuition.application_cost', 10) }} coins)
                    </div>
                @endif
            </div>
        @empty
            <div class="no-jobs">
                <i class="fas fa-briefcase" style="font-size: 48px; margin-bottom: 16px; color: #d1d5db;"></i>
                <h3>No jobs found</h3>
                <p>Try adjusting your filters or check back later.</p>
            </div>
        @endforelse

        <div class="pagination">
            {{ $jobs->links() }}
        </div>
    </div>

    <!-- Apply Modal -->
    <div id="applyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Apply for Job</h3>
                <span class="close" onclick="closeApplyModal()">&times;</span>
            </div>
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #1e40af;">
                    <i class="fas fa-info-circle"></i>
                    <strong>Application Cost: {{ config('tuition.application_cost', 10) }} coins</strong>
                </div>
                <p style="margin: 8px 0 0 0; font-size: 14px; color: #1e3a8a;">
                    After applying, you'll get access to the full phone number and can contact the student directly.
                </p>
                <p style="margin: 4px 0 0 0; font-size: 14px; color: #1e3a8a;">
                    Your current balance: <strong>{{ $teacher->coins }} coins</strong>
                </p>
            </div>
            <form id="applyForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="message">Application Message</label>
                    <textarea name="message" id="message" placeholder="Tell the student why you're the right fit for this job..." required></textarea>
                </div>
                <button type="submit" class="modal-btn">Submit Application ({{ config('tuition.application_cost', 10) }} coins)</button>
                <button type="button" class="modal-btn secondary" onclick="closeApplyModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
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

        // Apply modal
        function openApplyModal(offerId) {
            // Check if teacher has enough coins (this is also checked server-side)
            const teacherCoins = {{ $teacher->coins }};
            const applicationCost = {{ config('tuition.application_cost', 10) }};
            
            if (teacherCoins < applicationCost) {
                alert(`You don't have enough coins to apply. You need ${applicationCost} coins but only have ${teacherCoins} coins.`);
                return;
            }
            
            document.getElementById('applyForm').action = '{{ route("teacher.apply", ":id") }}'.replace(':id', offerId);
            document.getElementById('applyModal').style.display = 'block';
            // Reset submit button
            const submitBtn = document.querySelector('#applyForm button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Application ({{ config('tuition.application_cost', 10) }} coins)';
            }
        }

        function closeApplyModal() {
            document.getElementById('applyModal').style.display = 'none';
            document.getElementById('message').value = '';
        }

        // Prevent double submission
        document.addEventListener('DOMContentLoaded', function() {
            const applyForm = document.getElementById('applyForm');
            if (applyForm) {
                applyForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Submitting...';
                });
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('applyModal');
            if (event.target == modal) {
                closeApplyModal();
            }
        }
    </script>
</body>
</html>
