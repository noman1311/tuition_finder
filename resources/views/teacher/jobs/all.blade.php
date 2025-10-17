<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Jobs - TuitionFinder</title>
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
        .filters { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .filter-row { display: flex; gap: 15px; align-items: end; }
        .filter-group { flex: 1; }
        .filter-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; }
        .filter-group input, .filter-group select { width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; }
        .filter-btn { padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; }
        .filter-btn:hover { background: #1d4ed8; }
        .job-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .job-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px; }
        .job-title { font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 4px; }
        .job-subject { background: #dbeafe; color: #2563eb; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .job-meta { color: #6b7280; font-size: 14px; margin-bottom: 12px; }
        .job-description { color: #374151; line-height: 1.6; margin-bottom: 15px; }
        .apply-btn { background: #059669; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer; }
        .apply-btn:hover { background: #047857; }
        .no-jobs { text-align: center; padding: 60px 20px; color: #6b7280; }
        .pagination { display: flex; justify-content: center; margin-top: 30px; }
        .pagination a { padding: 8px 16px; margin: 0 4px; background: white; color: #2563eb; text-decoration: none; border-radius: 6px; border: 1px solid #e2e8f0; }
        .pagination a:hover { background: #2563eb; color: white; }
        .pagination .active { background: #2563eb; color: white; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fff; margin: 15% auto; padding: 20px; border-radius: 12px; width: 90%; max-width: 500px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: #000; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; }
        .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; min-height: 100px; resize: vertical; }
        .modal-btn { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 500; cursor: pointer; margin-right: 10px; }
        .modal-btn:hover { background: #1d4ed8; }
        .modal-btn.secondary { background: #6b7280; }
        .modal-btn.secondary:hover { background: #4b5563; }
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
                    <li><a href="{{ route('wallets') }}">Wallet</a></li>
                    <li><a href="{{ route('teacher.profile.edit') }}">Edit Profile</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" id="userMenuTrigger">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->username,0,1)) }}</div>
                    <span>{{ Auth::user()->username }}</span>
                </div>
                <div class="dropdown" id="userDropdown">
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
            <h1>All Teaching Jobs</h1>
            <p>Browse all available teaching opportunities</p>
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
                    <i class="fas fa-dollar-sign"></i> ৳{{ number_format($job->salary, 2) }}/hr
                </div>
                <div class="job-description">{{ $job->description }}</div>
                <button class="apply-btn" onclick="openApplyModal({{ $job->offer_id }})">Apply Now</button>
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
            <form id="applyForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="message">Application Message</label>
                    <textarea name="message" id="message" placeholder="Tell the student why you're the right fit for this job..." required></textarea>
                </div>
                <button type="submit" class="modal-btn">Submit Application</button>
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
            document.getElementById('applyForm').action = '{{ route("teacher.apply", ":id") }}'.replace(':id', offerId);
            document.getElementById('applyModal').style.display = 'block';
        }

        function closeApplyModal() {
            document.getElementById('applyModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('applyModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
