<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Search Results - TuitionFinder</title>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">

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
                                @if($teacher->image)
                                    <img src="{{ asset('storage/' . $teacher->image) }}" alt="{{ $teacher->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    {{ substr($teacher->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="teacher-info">
                                <h3>{{ $teacher->name }}</h3>
                                <div class="teacher-location">
                                    <i class="fas fa-map-marker-alt"></i> {{ $teacher->location }}
                                </div>
                                <div class="teacher-experience">
                                    <i class="fas fa-graduation-cap"></i> {{ $teacher->experience }} years experience
                                </div>
                            </div>
                        </div>

                        @if($teacher->subject_expertise)
                            <div class="teacher-expertise">
                                <div class="expertise-tags">
                                    @foreach($teacher->subject_expertise as $skill)
                                        <span class="expertise-tag">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($teacher->current_education_institution)
                            <div class="teacher-institution">
                                <i class="fas fa-university"></i> {{ $teacher->current_education_institution }}
                            </div>
                        @endif

                        @if($teacher->description)
                            <div class="teacher-bio">
                                {{ Str::limit($teacher->description, 120) }}
                            </div>
                        @endif

                        <div class="teacher-type">
                            <i class="fas fa-laptop"></i> 
                            <span class="type-badge type-{{ $teacher->preferred_type }}">
                                {{ ucfirst($teacher->preferred_type) }} Teaching
                            </span>
                        </div>

                        <div class="teacher-footer">
                            <div style="flex: 1;"></div>
                            <button class="contact-btn" onclick="contactTeacher({{ $teacher->teacher_id }}, '{{ $teacher->name }}')">
                                Contact Teacher
                            </button>
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
                <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <a href="/search" class="btn" style="display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px;">
                        Browse All Tutors
                    </a>
                    <a href="/" class="btn" style="display: inline-block; padding: 10px 20px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px;">
                        Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Contact Modal -->
    <div id="contactModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; margin: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #1f2937;">Contact Teacher</h3>
                <button onclick="closeContactModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">&times;</button>
            </div>
            
            <div id="contactContent">
                <p style="color: #6b7280; margin-bottom: 20px;">
                    Send a contact request to <strong id="teacherName"></strong>. 
                    The teacher will be notified and can choose to reveal your contact information for a small fee.
                </p>
                
                <form id="contactForm">
                    @csrf
                    <input type="hidden" id="teacherId" name="teacher_id">
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #374151;">Your Message</label>
                        <textarea name="message" rows="4" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; resize: vertical;" placeholder="Introduce yourself and explain what you're looking for..."></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="closeContactModal()" style="padding: 10px 20px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
                        <button type="submit" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer;">Send Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function contactTeacher(teacherId, teacherName) {
            document.getElementById('teacherId').value = teacherId;
            document.getElementById('teacherName').textContent = teacherName;
            document.getElementById('contactModal').style.display = 'flex';
        }

        function closeContactModal() {
            document.getElementById('contactModal').style.display = 'none';
            document.getElementById('contactForm').reset();
        }

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            fetch('/contact-teacher', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('contactContent').innerHTML = `
                        <div style="text-align: center; padding: 20px;">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981; margin-bottom: 15px;"></i>
                            <h4 style="color: #1f2937; margin-bottom: 10px;">Request Sent Successfully!</h4>
                            <p style="color: #6b7280;">The teacher has been notified of your contact request. They will be able to see your message and contact information after paying a small fee.</p>
                            <button onclick="closeContactModal()" style="margin-top: 15px; padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer;">Close</button>
                        </div>
                    `;
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Close modal when clicking outside
        document.getElementById('contactModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeContactModal();
            }
        });
    </script>
</body>
</html>
