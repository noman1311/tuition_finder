<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Post;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample teachers
        $teachers = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+1-555-0123',
                'expertise' => ['Mathematics', 'Calculus', 'Statistics', 'Algebra'],
                'location' => 'New York, NY',
                'bio' => 'Experienced mathematics professor with 10+ years of teaching experience. Specializes in calculus and statistics.',
                'hourly_rate' => 45.00,
                'is_available' => true,
            ],
            [
                'name' => 'Prof. Michael Chen',
                'email' => 'michael.chen@example.com',
                'phone' => '+1-555-0124',
                'expertise' => ['Computer Science', 'Programming', 'Data Structures', 'Python'],
                'location' => 'San Francisco, CA',
                'bio' => 'Software engineer turned educator. Expert in Python, Java, and web development.',
                'hourly_rate' => 50.00,
                'is_available' => true,
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@example.com',
                'phone' => '+1-555-0125',
                'expertise' => ['English Literature', 'Writing', 'Grammar', 'Creative Writing'],
                'location' => 'Los Angeles, CA',
                'bio' => 'Published author and English professor. Helps students improve their writing and literature analysis skills.',
                'hourly_rate' => 40.00,
                'is_available' => true,
            ],
            [
                'name' => 'Dr. James Wilson',
                'email' => 'james.wilson@example.com',
                'phone' => '+1-555-0126',
                'expertise' => ['Physics', 'Chemistry', 'Biology', 'Science'],
                'location' => 'Chicago, IL',
                'bio' => 'Former research scientist with expertise in all branches of science. Makes complex concepts easy to understand.',
                'hourly_rate' => 55.00,
                'is_available' => true,
            ],
            [
                'name' => 'Ms. Lisa Thompson',
                'email' => 'lisa.thompson@example.com',
                'phone' => '+1-555-0127',
                'expertise' => ['Spanish', 'French', 'Language Learning', 'ESL'],
                'location' => 'Miami, FL',
                'bio' => 'Native Spanish speaker with fluency in French. Specializes in language learning and ESL.',
                'hourly_rate' => 35.00,
                'is_available' => true,
            ],
            [
                'name' => 'Dr. Robert Kim',
                'email' => 'robert.kim@example.com',
                'phone' => '+1-555-0128',
                'expertise' => ['Economics', 'Business', 'Finance', 'Accounting'],
                'location' => 'Boston, MA',
                'bio' => 'Former investment banker with MBA from Harvard. Expert in economics and business concepts.',
                'hourly_rate' => 60.00,
                'is_available' => true,
            ],
        ];

        foreach ($teachers as $teacherData) {
            Teacher::create($teacherData);
        }

        // Create a sample user
        $user = User::create([
            'name' => 'John Student',
            'email' => 'john.student@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create sample posts
        $posts = [
            [
                'user_id' => $user->id,
                'title' => 'Need Help with Calculus',
                'description' => 'I am struggling with calculus concepts, particularly derivatives and integrals. Looking for a patient tutor who can explain things clearly.',
                'subject' => 'Mathematics',
                'location' => 'New York, NY',
                'budget' => 40.00,
                'duration' => '3 months',
                'status' => 'open',
            ],
            [
                'user_id' => $user->id,
                'title' => 'Python Programming Tutor Needed',
                'description' => 'I want to learn Python programming from scratch. Need someone who can teach me the basics and help with projects.',
                'subject' => 'Computer Science',
                'location' => 'San Francisco, CA',
                'budget' => 45.00,
                'duration' => '6 months',
                'status' => 'open',
            ],
            [
                'user_id' => $user->id,
                'title' => 'English Essay Writing Help',
                'description' => 'I need help improving my essay writing skills for college applications. Looking for someone who can review and provide feedback.',
                'subject' => 'English Literature',
                'location' => 'Los Angeles, CA',
                'budget' => 30.00,
                'duration' => '2 months',
                'status' => 'open',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }
    }
}