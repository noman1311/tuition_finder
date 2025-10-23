<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Jobs - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .header { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0 20px; }
        .header-container { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; height: 70px; }
        .logo { font-size: 24px; font-weight: 700; color: #2563eb; text-decoration: none; }
        .nav-menu { display: flex; list-style: none; gap: 30px; align-items: center; }
        .nav-menu a { text-decoration: none; color: #4b5563; font-weight: 500; transition: color 0.3s; }
        .nav-menu a:hover, .nav-menu a.active { color: #2563eb; }
        .user-menu { display: flex; align-items: center; gap: 15px; position: relative; }
        .user-profile { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 6px; background: #f1f5f9; cursor: pointer; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .dropdown { display: none; position: absolute; right: 0; top: 56px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 20px rgba(0,0,0,.08); min-width: 180px; z-index: 1000; }
        .dropdown a, .dropdown button { display: block; width: 100%; padding: 10px 12px; color: #111827; text-decoration: none; background: none; border: none; text-align: left; cursor: pointer; }
        .dropdown a:hover, .dropdown button:hover { background: #f8fafc; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { font-size: 28px; color: #1f2937; margin-bottom: 8px; }
        .page-header p { color: #6b7280; }
        .job-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .job-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px; }
        .job-title { font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 4px; }
        .job-subject { background: #dbeafe; color: #2563eb; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-accepted { background: #d1fae5; color: #059669; }
        .status-rejected { background: #fee2e2; color: #dc2626; }
        .status-available { background: #e0f2fe; color: #0277bd; }
        .apply-btn { background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .apply-btn:hover { background: #1d4ed8; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 15% auto; padding: 20px; border-radius: 8px; width: 500px; max-width: 90%; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; }
        .close { font-size: 24px; cursor: pointer; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 5px; }
        .form-group textarea { width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; min-height: 100px; resize: vertical; }
        .modal-btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; margin-right: 10px; }
        .modal-btn:not(.secondary) { background: #2563eb; color: white; }
        .modal-btn.secondary { background: #6b7280; color: white; }
        .job-meta { color: #6b7280; font-size: 14px; margin-bottom: 12px; }
        .job-description { color: #374151; line-height: 1.6; }
        .no-jobs { text-align: center; padding: 60px 20px; color: #6b7280; }
        .pagination { display: flex; justify-content: center; margin-top: 30px; }
        .pagination a { padding: 8px 16px; margin: 0 4px; background: white; color: #2563eb; text-decoration: none; border-radius: 6px; border: 1px solid #e2e8f0; }
        .pagination a:hover { background: #2563eb; color: white; }
        .pagination .active { background: #2563eb; color: white; }
    </style>
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
            <h1>All Jobs & My Applications</h1>
            <p>View all available jobs and track your application status</p>
        </div>

        @forelse($jobs as $job)
            @php
                // Check if teacher has applied for this job
                $application = $job->applications->where('teacher_id', $teacher->teacher_id)->first();
                $hasApplied = $application !== null;
            @endphp
            
            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">{{ $job->subject }} - {{ $job->class_level }}</h3>
                        <span class="job-subject">{{ $job->type }}</span>
                    </div>
                    @if($hasApplied)
                        <span class="status-badge status-{{ $application->status }}">Applied - {{ ucfirst($application->status) }}</span>
                    @else
                        <span class="status-badge status-available">Available</span>
                    @endif
                </div>
                <div class="job-meta">
                    <i class="fas fa-map-marker-alt"></i> {{ $job->location }} • 
                    <i class="fas fa-clock"></i> {{ $job->preferred_type }} • 
                    <i class="fas fa-dollar-sign"></i> ৳{{ number_format($job->salary, 2) }}/hr •
                    <i class="fas fa-phone"></i> 
                    @if($hasApplied)
                        {{ $job->formatted_phone }}
                    @else
                        {{ $job->hidden_phone }}
                    @endif
                </div>
                <div class="job-description">{{ $job->description }}</div>
                
                @if($hasApplied && $application->message)
                    <div style="margin-top: 12px; padding: 12px; background: #f8fafc; border-radius: 8px;">
                        <strong>Your Application:</strong> {{ $application->message }}
                        <br><small style="color: #6b7280;">Applied on {{ $application->created_at->format('M d, Y \a\t h:i A') }}</small>
                    </div>
                @elseif(!$hasApplied)
                    @if($teacher->coins >= config('tuition.application_cost', 10))
                        <button class="apply-btn" onclick="openApplyModal({{ $job->offer_id }})" style="margin-top: 12px;">
                            Apply Now ({{ config('tuition.application_cost', 10) }} coins)
                        </button>
                    @else
                        <div style="margin-top: 12px; padding: 8px 12px; background: #fee2e2; border-radius: 6px; color: #dc2626; font-size: 14px;">
                            <i class="fas fa-exclamation-triangle"></i> Can't apply - Not enough coins (Need {{ config('tuition.application_cost', 10) }} coins)
                        </div>
                    @endif
                @endif
            </div>
        @empty
            <div class="no-jobs">
                <i class="fas fa-briefcase" style="font-size: 48px; margin-bottom: 16px; color: #d1d5db;"></i>
                <h3>No jobs available</h3>
                <p>Check back later for new teaching opportunities.</p>
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

        // Apply modal functions
        function openApplyModal(offerId) {
            // Check if teacher has enough coins (this is also checked server-side)
            const teacherCoins = {{ $teacher->coins }};
            const applicationCost = {{ config('tuition.application_cost', 10) }};
            
            if (teacherCoins < applicationCost) {
                alert(`You don't have enough coins to apply. You need ${applicationCost} coins but only have ${teacherCoins} coins.`);
                return;
            }
            
            const modal = document.getElementById('applyModal');
            const form = document.getElementById('applyForm');
            form.action = `/teacher/apply/${offerId}`;
            modal.style.display = 'block';
        }

        function closeApplyModal() {
            const modal = document.getElementById('applyModal');
            modal.style.display = 'none';
            document.getElementById('message').value = '';
            // Re-enable submit button
            const submitBtn = document.querySelector('#applyForm button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Application';
            }
        }

        // Prevent double submission
        document.getElementById('applyForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('applyModal');
            if (event.target === modal) {
                closeApplyModal();
            }
        }
    </script>
</body>
</html>
