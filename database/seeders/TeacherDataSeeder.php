<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Playlist;
use App\Models\Video;
use App\Models\ExamBoard;
use App\Models\User;
use Illuminate\Support\Str;

class TeacherDataSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1;
        $examBoard = ExamBoard::first();
        $examBoardId = $examBoard ? $examBoard->id : null;

        $this->command->info('Creating Teacher Subjects...');

        $subjectsData = [
            [
                'name' => 'Pedagogy & Classroom Management',
                'description' => 'Effective teaching methods, student engagement strategies, positive reinforcement, and classroom behavior management.',
                'slug' => 'pedagogy-classroom-management-tchr',
            ],
            [
                'name' => 'STEM & Interactive Science Teaching',
                'description' => 'Hands-on laboratory demonstrations, conceptual visual models, scientific inquiry, and experiential learning methods.',
                'slug' => 'stem-interactive-science-tchr',
            ],
            [
                'name' => 'Mathematics Pedagogical Techniques',
                'description' => 'Removing math anxiety, teaching foundational arithmetic, geometric visualization, and diagnostic problem solving.',
                'slug' => 'math-pedagogical-techniques-tchr',
            ],
            [
                'name' => 'Communication & Voice Modulation for Educators',
                'description' => 'Public speaking, body language, clear pronunciation, lecture pacing, and active teacher-student communication.',
                'slug' => 'communication-voice-modulation-tchr',
            ],
            [
                'name' => 'Digital Smart Classroom & EdTech Tools',
                'description' => 'Smart boards, digital whiteboards, Canva for education, quiz apps, and modern educational technology tools.',
                'slug' => 'digital-smart-classroom-edtech-tchr',
            ],
        ];

        $createdSubjects = [];
        foreach ($subjectsData as $s) {
            $createdSubjects[] = Subject::firstOrCreate(
                ['slug' => $s['slug']],
                [
                    'target_audience' => 'teacher',
                    'exam_id' => null,
                    'name' => $s['name'],
                    'description' => $s['description'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Creating Teacher Courses...');

        $coursesData = [
            [
                'title' => 'National Educator Professional Development (NEPD) 2026',
                'slug' => 'national-educator-professional-development-2026',
                'description' => 'Comprehensive master certification program for school and coaching teachers covering 21st-century teaching methodologies, differentiated instruction, and student counseling.',
                'subjects_indices' => [0, 1, 2],
            ],
            [
                'title' => 'Digital Classroom & Smart Board Teaching Mastery',
                'slug' => 'digital-classroom-smart-board-teaching-mastery',
                'description' => 'Master interactive flat panels (IFP), digital whiteboards, screen casting, and online lesson recording for hybrid and in-person teaching.',
                'subjects_indices' => [3, 4],
            ],
            [
                'title' => 'Advanced Lesson Planning & Formative Assessment Framework',
                'slug' => 'advanced-lesson-planning-formative-assessment',
                'description' => 'Design engaging daily lesson plans using the 5E model, question paper blueprints, objective rubric assessment, and remedial coaching.',
                'subjects_indices' => [0, 3],
            ],
        ];

        $createdCourses = [];
        foreach ($coursesData as $c) {
            $course = Course::firstOrCreate(
                ['slug' => $c['slug']],
                [
                    'target_audience' => 'teacher',
                    'exam_board_id' => $examBoardId,
                    'title' => $c['title'],
                    'description' => $c['description'],
                    'price' => 0.00,
                    'currency' => 'INR',
                    'is_published' => true,
                    'created_by' => $adminId,
                ]
            );

            // Attach subjects with sort order
            $subjectIds = [];
            foreach ($c['subjects_indices'] as $idx => $sIdx) {
                if (isset($createdSubjects[$sIdx])) {
                    $subjectIds[$createdSubjects[$sIdx]->id] = ['sort_order' => $idx + 1];
                }
            }
            $course->subjects()->syncWithoutDetaching($subjectIds);
            $createdCourses[] = $course;
        }

        $this->command->info('Creating Teacher Playlists & Video Lessons...');

        $playlistsData = [
            // Course 1 Playlists
            [
                'course' => $createdCourses[0],
                'subject' => $createdSubjects[0], // Pedagogy
                'title' => 'Module 1: Foundations of Student Psychology & Active Engagement',
                'slug' => 'module-1-student-psychology-engagement',
                'description' => 'Understanding cognitive growth stages, attention spans in classrooms, and building positive student-teacher rapport.',
                'sort_order' => 1,
                'videos' => [
                    [
                        'title' => 'Lecture 1.1: Cognitive Development Stages & Classroom Readiness',
                        'description' => 'How children of different age groups process abstract concepts and concrete examples.',
                        'video_url' => 'https://vimeo.com/76979871',
                        'duration' => 1450,
                    ],
                    [
                        'title' => 'Lecture 1.2: Overcoming Attention Deficit & Engagement Techniques',
                        'description' => '10 proven activities to recapture classroom focus within 60 seconds.',
                        'video_url' => 'https://vimeo.com/76979872',
                        'duration' => 1820,
                    ],
                    [
                        'title' => 'Lecture 1.3: Differentiated Teaching for Fast and Slow Learners',
                        'description' => 'How to design tiered assignments and scaffold learning inside mixed-ability classrooms.',
                        'video_url' => 'https://vimeo.com/76979873',
                        'duration' => 2100,
                    ],
                ],
            ],
            [
                'course' => $createdCourses[0],
                'subject' => $createdSubjects[1], // STEM
                'title' => 'Module 2: Inquiry-Based Science & Laboratory Demonstrations',
                'slug' => 'module-2-inquiry-science-lab-demos',
                'description' => 'Low-cost experiments and engaging demonstrations that make physics and chemistry unforgettable.',
                'sort_order' => 2,
                'videos' => [
                    [
                        'title' => 'Lecture 2.1: The Hypothesis Cycle: Converting Theory to Inquiry',
                        'description' => 'Structuring science lectures around real-world phenomena rather than rote formulas.',
                        'video_url' => 'https://vimeo.com/76979874',
                        'duration' => 1650,
                    ],
                    [
                        'title' => 'Lecture 2.2: 5 High-Impact Low-Cost Classroom Physics Demonstrations',
                        'description' => 'Demonstrating inertia, refraction, air pressure, and center of gravity with everyday items.',
                        'video_url' => 'https://vimeo.com/76979875',
                        'duration' => 1950,
                    ],
                ],
            ],
            [
                'course' => $createdCourses[0],
                'subject' => $createdSubjects[2], // Math
                'title' => 'Module 3: Visual & Diagnostic Mathematics Instruction',
                'slug' => 'module-3-visual-diagnostic-math',
                'description' => 'Visualizing algebra and geometry, diagnosing common arithmetic errors, and teaching Vedic shortcuts.',
                'sort_order' => 3,
                'videos' => [
                    [
                        'title' => 'Lecture 3.1: Diagnosing Common Student Misconceptions in Fractions & Algebra',
                        'description' => 'Identifying why students make systematic sign and denominator errors and how to correct them.',
                        'video_url' => 'https://vimeo.com/76979876',
                        'duration' => 1520,
                    ],
                    [
                        'title' => 'Lecture 3.2: Mental Math Shortcuts & Vedic Math in School Curricula',
                        'description' => 'Teaching rapid multiplication and square roots to build student calculation confidence.',
                        'video_url' => 'https://vimeo.com/76979877',
                        'duration' => 1740,
                    ],
                ],
            ],

            // Course 2 Playlists (Digital Teaching)
            [
                'course' => $createdCourses[1],
                'subject' => $createdSubjects[4], // Digital EdTech
                'title' => 'Module 1: Interactive Flat Panels & Digital Whiteboards',
                'slug' => 'module-1-interactive-flat-panels-whiteboards',
                'description' => 'Tools, gestures, annotation apps, and split-screen features of modern smart boards.',
                'sort_order' => 1,
                'videos' => [
                    [
                        'title' => 'Lecture 1.1: Getting Started with Smart Board Annotations & Tools',
                        'description' => 'Navigating canvas, highlighter layers, 3D shape tools, and saving PDF class notes for students.',
                        'video_url' => 'https://vimeo.com/76979878',
                        'duration' => 1380,
                    ],
                    [
                        'title' => 'Lecture 1.2: Integrating Simulations (PhET) & GeoGebra Live in Class',
                        'description' => 'How to run interactive physics and geometry simulations directly on the board.',
                        'video_url' => 'https://vimeo.com/76979879',
                        'duration' => 1890,
                    ],
                ],
            ],
            [
                'course' => $createdCourses[1],
                'subject' => $createdSubjects[3], // Communication
                'title' => 'Module 2: Camera Presence, Voice Modulation & Hybrid Delivery',
                'slug' => 'module-2-camera-presence-voice-modulation',
                'description' => 'Overcoming camera shyness, lighting, lapel mic placement, and engaging both offline & online students simultaneously.',
                'sort_order' => 2,
                'videos' => [
                    [
                        'title' => 'Lecture 2.1: Voice Warmups & Pitch Control for High-Energy Lectures',
                        'description' => 'Diaphragmatic breathing techniques and tonal shifts to keep lectures dynamic.',
                        'video_url' => 'https://vimeo.com/76979880',
                        'duration' => 1240,
                    ],
                    [
                        'title' => 'Lecture 2.2: Managing Hybrid Classroom Attention: Dual-Mode Teaching',
                        'description' => 'Balancing interaction between physical front-row students and online stream viewers.',
                        'video_url' => 'https://vimeo.com/76979881',
                        'duration' => 1600,
                    ],
                ],
            ],

            // Course 3 Playlists (Lesson Planning)
            [
                'course' => $createdCourses[2],
                'subject' => $createdSubjects[0], // Pedagogy
                'title' => 'Module 1: Constructing Lesson Plans Using the 5E Model',
                'slug' => 'module-1-constructing-lesson-plans-5e-model',
                'description' => 'Engage, Explore, Explain, Elaborate, Evaluate - A standardized blueprint for every academic topic.',
                'sort_order' => 1,
                'videos' => [
                    [
                        'title' => 'Lecture 1.1: Deconstructing the 5E Instructional Framework',
                        'description' => 'Step-by-step walkthrough of building a 45-minute lesson plan around student activities.',
                        'video_url' => 'https://vimeo.com/76979882',
                        'duration' => 1780,
                    ],
                    [
                        'title' => 'Lecture 1.2: Formative Assessment During Lecture: Exit Tickets & Quick Polls',
                        'description' => 'How to know in real-time whether your students actually understood the core concept.',
                        'video_url' => 'https://vimeo.com/76979883',
                        'duration' => 1420,
                    ],
                ],
            ],
        ];

        foreach ($playlistsData as $plData) {
            $playlist = Playlist::firstOrCreate(
                ['slug' => $plData['slug']],
                [
                    'target_audience' => 'teacher',
                    'course_id' => $plData['course']->id,
                    'subject_id' => $plData['subject']->id,
                    'title' => $plData['title'],
                    'description' => $plData['description'],
                    'sort_order' => $plData['sort_order'],
                    'is_active' => true,
                ]
            );

            foreach ($plData['videos'] as $vIdx => $v) {
                Video::firstOrCreate(
                    [
                        'target_audience' => 'teacher',
                        'course_id' => $plData['course']->id,
                        'title' => $v['title'],
                    ],
                    [
                        'subject_id' => $plData['subject']->id,
                        'playlist_id' => $playlist->id,
                        'description' => $v['description'],
                        'video_url' => $v['video_url'],
                        'video_type' => 'vimeo',
                        'vimeo_video_id' => substr($v['video_url'], -8),
                        'duration_seconds' => $v['duration'],
                        'sort_order' => $vIdx + 1,
                        'is_preview' => false,
                        'is_free' => true,
                        'is_active' => true,
                        'status' => 'ready',
                    ]
                );
            }
        }

        // Add 2 Standalone Teacher Videos (Direct course lectures without playlists)
        Video::firstOrCreate(
            [
                'target_audience' => 'teacher',
                'course_id' => $createdCourses[0]->id,
                'title' => 'Orientation: Welcome to Teacher Continuous Professional Development',
            ],
            [
                'subject_id' => $createdSubjects[0]->id,
                'playlist_id' => null,
                'description' => 'Master course orientation, learning milestones, and institutional guidelines for teachers.',
                'video_url' => 'https://vimeo.com/76979890',
                'video_type' => 'vimeo',
                'vimeo_video_id' => '76979890',
                'duration_seconds' => 960,
                'sort_order' => 1,
                'is_preview' => true,
                'is_free' => true,
                'is_active' => true,
                'status' => 'ready',
            ]
        );

        Video::firstOrCreate(
            [
                'target_audience' => 'teacher',
                'course_id' => $createdCourses[1]->id,
                'title' => 'Quickstart: 10 Essential Keyboard Shortcuts for Digital Classrooms',
            ],
            [
                'subject_id' => $createdSubjects[4]->id,
                'playlist_id' => null,
                'description' => 'Speed up presentation flow, window switching, and live drawing shortcuts during live teaching.',
                'video_url' => 'https://vimeo.com/76979891',
                'video_type' => 'vimeo',
                'vimeo_video_id' => '76979891',
                'duration_seconds' => 720,
                'sort_order' => 1,
                'is_preview' => true,
                'is_free' => true,
                'is_active' => true,
                'status' => 'ready',
            ]
        );

        $this->command->info('Teacher dummy data created successfully!');
    }
}
