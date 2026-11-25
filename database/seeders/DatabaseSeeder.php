<?php

namespace Database\Seeders;

use App\Models\{
    User,
    ExamBoard,
    Exam,
    Subject,
    SubjectNote,
    Course,
    Video,
    Test,
    Question,
    Purchase,
    TestAttempt,
    VideoViewLimit,
    Otp,
    Setting
};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete test data for every use case.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean truncation and seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'video_view_limits',
            'test_attempts',
            'questions',
            'test_series_subjects',
            'tests',
            'videos',
            'course_subjects',
            'subject_notes',
            'courses',
            'subjects',
            'exams',
            'exam_boards',
            'purchases',
            'otps',
            'settings',
            'users',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('🧹 Truncated all existing tables cleanly.');

        // ==========================================
        // 1. USERS & ROLES
        // ==========================================
        $this->command->info('👤 Seeding Administrators & Student Profiles...');

        // 1.1 Super Admin
        $superAdmin = User::create([
            'name' => 'Olive Admin',
            'email' => 'admin@eduapp.com',
            'phone' => '9999999999',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 1.2 Academic Coordinator (Second Admin)
        $academicAdmin = User::create([
            'name' => 'Dr. Meenakshi Sundaram',
            'email' => 'academic@olivelearnings.com',
            'phone' => '9888888888',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 1.3 Active Students (Various cities & use cases)
        $studentRohit = User::create([
            'name' => 'Rohit Sharma',
            'email' => 'rohit.sharma@gmail.com',
            'phone' => '9876543210',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $studentPriya = User::create([
            'name' => 'Priya Verma',
            'email' => 'priya.verma@gmail.com',
            'phone' => '9876543211',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $studentAmit = User::create([
            'name' => 'Amit Patel',
            'email' => 'amit.patel@gmail.com',
            'phone' => '9876543212',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $studentAnanya = User::create([
            'name' => 'Ananya Singh',
            'email' => 'ananya.singh@gmail.com',
            'phone' => '9876543213',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $studentVikram = User::create([
            'name' => 'Vikram Aditya',
            'email' => 'vikram.aditya@gmail.com',
            'phone' => '9876543214',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $studentSneha = User::create([
            'name' => 'Sneha Reddy',
            'email' => 'sneha.reddy@gmail.com',
            'phone' => '9876543215',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $studentRahul = User::create([
            'name' => 'Rahul Mehta',
            'email' => 'rahul.mehta@gmail.com',
            'phone' => '9876543216',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $studentPooja = User::create([
            'name' => 'Pooja Joshi',
            'email' => 'pooja.joshi@gmail.com',
            'phone' => '9876543217',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 1.4 Suspended / Inactive Student (For testing active filter / account lockouts)
        $studentInactive = User::create([
            'name' => 'Dev Kumar (Suspended Account)',
            'email' => 'dev.kumar@gmail.com',
            'phone' => '9876543218',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => false,
            'email_verified_at' => now(),
        ]);

        // 1.5 Pending OTP Student (For testing OTP login flow)
        $studentOtp = User::create([
            'name' => 'Karan Malhotra (Pending OTP)',
            'email' => 'karan.malhotra@gmail.com',
            'phone' => '9876543219',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'otp' => '4582',
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'email_verified_at' => null,
        ]);


        // ==========================================
        // 2. EXAM BOARDS
        // ==========================================
        $this->command->info('🏛️ Seeding Examination Boards...');

        $boardCbse = ExamBoard::create([
            'name' => 'CBSE Board',
            'slug' => 'cbse-board',
            'description' => 'Central Board of Secondary Education - India',
            'is_active' => true,
        ]);

        $boardIcse = ExamBoard::create([
            'name' => 'ICSE / ISC Board',
            'slug' => 'icse-isc-board',
            'description' => 'Council for the Indian School Certificate Examinations',
            'is_active' => true,
        ]);

        $boardState = ExamBoard::create([
            'name' => 'State Secondary Boards',
            'slug' => 'state-boards',
            'description' => 'State Education Boards (RBSE, UPMSP, BSEB)',
            'is_active' => true,
        ]);

        $boardCompetitive = ExamBoard::create([
            'name' => 'National Testing Agency & UPSC',
            'slug' => 'nta-upsc-competitive',
            'description' => 'National competitive examinations & central recruitments',
            'is_active' => true,
        ]);


        // ==========================================
        // 3. EXAMS
        // ==========================================
        $this->command->info('📝 Seeding Target Examinations...');

        $examJee = Exam::create([
            'exam_board_id' => $boardCompetitive->id,
            'name' => 'JEE Main 2026',
            'slug' => 'jee-main-2026',
            'description' => 'Joint Entrance Examination for Engineering Admissions',
            'is_active' => true,
        ]);

        $examNeet = Exam::create([
            'exam_board_id' => $boardCompetitive->id,
            'name' => 'NEET-UG 2026',
            'slug' => 'neet-ug-2026',
            'description' => 'National Eligibility cum Entrance Test for Medical Admissions',
            'is_active' => true,
        ]);

        $examClass12 = Exam::create([
            'exam_board_id' => $boardCbse->id,
            'name' => 'Class 12 Board Exam',
            'slug' => 'class-12-cbse',
            'description' => 'CBSE Class XII Higher Secondary Board Examinations',
            'is_active' => true,
        ]);

        $examClass10 = Exam::create([
            'exam_board_id' => $boardCbse->id,
            'name' => 'Class 10 Board Exam',
            'slug' => 'class-10-cbse',
            'description' => 'CBSE Class X Secondary School Examinations',
            'is_active' => true,
        ]);

        $examUpsc = Exam::create([
            'exam_board_id' => $boardCompetitive->id,
            'name' => 'UPSC Civil Services',
            'slug' => 'upsc-civil-services',
            'description' => 'Union Public Service Commission Preliminary & Mains',
            'is_active' => true,
        ]);

        $examSsc = Exam::create([
            'exam_board_id' => $boardCompetitive->id,
            'name' => 'SSC CGL & LDC Combined',
            'slug' => 'ssc-cgl-ldc',
            'description' => 'Staff Selection Commission Combined Graduate Level & Lower Division Clerk',
            'is_active' => true,
        ]);


        // ==========================================
        // 4. SUBJECTS
        // ==========================================
        $this->command->info('📚 Seeding Subjects & Academic Disciplines...');

        // Engineering & Medical Subjects
        $subjPhysics = Subject::create([
            'exam_id' => $examJee->id,
            'name' => 'Physics',
            'slug' => 'physics-jee-neet',
            'description' => 'Mechanics, Optics, Thermodynamics, Electromagnetism & Modern Physics',
            'is_active' => true,
        ]);

        $subjChemistry = Subject::create([
            'exam_id' => $examJee->id,
            'name' => 'Chemistry',
            'slug' => 'chemistry-jee-neet',
            'description' => 'Physical, Inorganic, and Organic Chemistry with NCERT linkages',
            'is_active' => true,
        ]);

        $subjMathematics = Subject::create([
            'exam_id' => $examJee->id,
            'name' => 'Mathematics',
            'slug' => 'mathematics-jee-12th',
            'description' => 'Calculus, Vectors, Coordinate Geometry, Trigonometry & Probability',
            'is_active' => true,
        ]);

        $subjBiology = Subject::create([
            'exam_id' => $examNeet->id,
            'name' => 'Biology (Botany & Zoology)',
            'slug' => 'biology-neet',
            'description' => 'Cell Biology, Genetics, Human Physiology & Ecology for NEET',
            'is_active' => true,
        ]);

        // Competitive / Govt Exam Subjects
        $subjPolity = Subject::create([
            'exam_id' => $examUpsc->id,
            'name' => 'Indian Polity & Constitution',
            'slug' => 'indian-polity-constitution',
            'description' => 'Governance, Constitutional Articles, Judiciary & Administrative Framework',
            'is_active' => true,
        ]);

        $subjGeneralStudies = Subject::create([
            'exam_id' => $examUpsc->id,
            'name' => 'General Studies & Modern History',
            'slug' => 'general-studies-history',
            'description' => 'Indian National Movement, Geography, Environment & Current Affairs',
            'is_active' => true,
        ]);

        $subjQuantReasoning = Subject::create([
            'exam_id' => $examSsc->id,
            'name' => 'Quantitative Aptitude & Reasoning',
            'slug' => 'quant-aptitude-reasoning',
            'description' => 'Arithmetic, Data Interpretation, Logical & Analytical Reasoning',
            'is_active' => true,
        ]);

        $subjEnglish = Subject::create([
            'exam_id' => $examSsc->id,
            'name' => 'English Language & Comprehension',
            'slug' => 'english-comprehension',
            'description' => 'Grammar, Reading Comprehension, Vocab & Verbal Ability',
            'is_active' => true,
        ]);


        // ==========================================
        // 5. SUBJECT NOTES
        // ==========================================
        $this->command->info('📄 Seeding Subject Notes & Revision Guides...');

        SubjectNote::create([
            'subject_id' => $subjPhysics->id,
            'title' => 'Mechanics & Newton Laws Revision Formula Sheet',
            'pdf_path' => 'uploads/notes/physics_mechanics_formula_sheet.pdf',
            'description' => 'Quick reference guide containing all formulas and free body diagrams for Newton laws of motion.',
        ]);

        SubjectNote::create([
            'subject_id' => $subjChemistry->id,
            'title' => 'Periodic Table & Chemical Bonding High-Yield Summary',
            'pdf_path' => 'uploads/notes/chemistry_bonding_summary.pdf',
            'description' => 'Complete summary of hybridization, molecular orbital theory, and periodic trends for NEET/JEE.',
        ]);

        SubjectNote::create([
            'subject_id' => $subjMathematics->id,
            'title' => 'Integral Calculus Standard Integrals & Substitution Cheat Sheet',
            'pdf_path' => 'uploads/notes/maths_calculus_cheatsheet.pdf',
            'description' => 'Formulas for definite and indefinite integrals with standard substitutions.',
        ]);

        SubjectNote::create([
            'subject_id' => $subjPolity->id,
            'title' => 'Important Articles & Amendments of Indian Constitution',
            'pdf_path' => 'uploads/notes/polity_articles_amendments.pdf',
            'description' => 'Tabulated summary of fundamental rights, DPSP, and major constitutional amendments.',
        ]);


        // ==========================================
        // 6. COURSES (Covering Every Use Case)
        // ==========================================
        $this->command->info('🎓 Seeding Comprehensive Courses...');

        // 6.1 Single Subject Premium Course (Published, Standard Price)
        $coursePhysics = Course::create([
            'exam_board_id' => $boardCompetitive->id,
            'title' => 'Complete Physics for JEE Main 2026',
            'slug' => 'complete-physics-jee-main-2026',
            'description' => 'Comprehensive masterclass covering Class 11 and 12 Physics with video lectures, DPPs, and problem-solving sessions.',
            'price' => 1999.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?auto=format&fit=crop&w=800&q=80',
            'created_by' => $superAdmin->id,
        ]);
        $coursePhysics->subjects()->attach($subjPhysics->id, ['sort_order' => 0]);

        // 6.2 Multi-Subject Premium Medical Bundle (Published, Higher Price)
        $courseNeetPcb = Course::create([
            'exam_board_id' => $boardCompetitive->id,
            'title' => 'Target NEET 2026: Complete PCB Ultimate Batch',
            'slug' => 'target-neet-2026-pcb-ultimate',
            'description' => 'All-inclusive preparation batch covering Physics, Chemistry, and Biology specifically mapped to latest NCERT rationalized syllabus.',
            'price' => 3499.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=800&q=80',
            'created_by' => $superAdmin->id,
        ]);
        $courseNeetPcb->subjects()->attach([
            $subjPhysics->id => ['sort_order' => 0],
            $subjChemistry->id => ['sort_order' => 1],
            $subjBiology->id => ['sort_order' => 2],
        ]);

        // 6.3 100% Free Introductory Course (Price = 0.00, Published)
        $courseFreeChemistry = Course::create([
            'exam_board_id' => $boardCbse->id,
            'title' => 'Free NCERT Chemistry Fundamentals & Lab Essentials',
            'slug' => 'free-ncert-chemistry-fundamentals',
            'description' => 'Zero-cost foundation course designed for students transitioning from Class 10 to Class 11 Science.',
            'price' => 0.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1603126857599-f6e157fa2fe6?auto=format&fit=crop&w=800&q=80',
            'created_by' => $academicAdmin->id,
        ]);
        $courseFreeChemistry->subjects()->attach($subjChemistry->id, ['sort_order' => 0]);

        // 6.4 Government Exam Multidisciplinary Foundation (Published, Moderate Price)
        $courseSscLdc = Course::create([
            'exam_board_id' => $boardCompetitive->id,
            'title' => 'SSC CGL & State LDC Complete Preparation Batch',
            'slug' => 'ssc-cgl-ldc-complete-preparation',
            'description' => 'Complete coaching for central and state exams featuring Quantitative Aptitude, General Studies, Indian Polity, and English.',
            'price' => 2499.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80',
            'created_by' => $superAdmin->id,
        ]);
        $courseSscLdc->subjects()->attach([
            $subjPolity->id => ['sort_order' => 0],
            $subjGeneralStudies->id => ['sort_order' => 1],
            $subjQuantReasoning->id => ['sort_order' => 2],
            $subjEnglish->id => ['sort_order' => 3],
        ]);

        // 6.5 Fast-Track Math Crash Course (Published, Budget Price)
        $courseMathCrash = Course::create([
            'exam_board_id' => $boardCbse->id,
            'title' => 'Class 12 CBSE Board: Mathematics 100/100 Crash Course',
            'slug' => 'class-12-cbse-mathematics-crash-course',
            'description' => 'Intensive 60-day crash course solving top previous 10-year question papers, NCERT exemplars, and sample papers.',
            'price' => 799.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?auto=format&fit=crop&w=800&q=80',
            'created_by' => $superAdmin->id,
        ]);
        $courseMathCrash->subjects()->attach($subjMathematics->id, ['sort_order' => 0]);

        // 6.6 Draft / Unpublished Course (For testing admin visibility & unpublished filters)
        $courseDraft = Course::create([
            'exam_board_id' => $boardCompetitive->id,
            'title' => 'Data Interpretation & Logical Reasoning Booster 2027 (Upcoming)',
            'slug' => 'data-interpretation-logical-reasoning-2027',
            'description' => 'Advanced module in development. Contains preliminary curriculum notes and syllabus outline.',
            'price' => 1299.00,
            'currency' => 'INR',
            'is_published' => false, // UNPUBLISHED
            'thumbnail_path' => null,
            'created_by' => $academicAdmin->id,
        ]);
        $courseDraft->subjects()->attach($subjQuantReasoning->id, ['sort_order' => 0]);


        // ==========================================
        // 7. VIDEOS (Lecture stream, preview & locked)
        // ==========================================
        $this->command->info('🎥 Seeding Educational Video Lectures...');

        // Course 1: Physics Videos
        Video::create([
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjPhysics->id,
            'title' => '01. Introduction to Vectors & Coordinate Systems',
            'description' => 'Fundamental concepts of scalar and vector quantities, dot and cross products with graphical intuition.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration_seconds' => 1800, // 30 min
            'view_limit' => 3,
            'sort_order' => 1,
            'is_preview' => true, // FREE PREVIEW
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        Video::create([
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjPhysics->id,
            'title' => '02. Kinematics: Motion in One Dimension',
            'description' => 'Displacement, instantaneous velocity, uniform acceleration and calculus-based equation derivations.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'duration_seconds' => 2400, // 40 min
            'view_limit' => 3,
            'sort_order' => 2,
            'is_preview' => false, // LOCKED TO ENROLLED STUDENTS
            'is_free' => false,
            'is_active' => true,
            'status' => 'ready',
        ]);

        Video::create([
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjPhysics->id,
            'title' => '03. Newton\'s Laws of Motion & Friction Dynamics',
            'description' => 'In-depth analysis of pseudo forces, free body diagrams, pulley constraints, and angle of repose.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'duration_seconds' => 3300, // 55 min
            'view_limit' => 3,
            'sort_order' => 3,
            'is_preview' => false,
            'is_free' => false,
            'is_active' => true,
            'status' => 'ready',
        ]);

        // Course 2: NEET PCB Videos
        Video::create([
            'course_id' => $courseNeetPcb->id,
            'subject_id' => $subjBiology->id,
            'title' => 'Cell Structure & Biomolecules: Complete Overview',
            'description' => 'Prokaryotic vs eukaryotic organelles, membrane models, and peptide linkages explained with 3D models.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'duration_seconds' => 2100,
            'view_limit' => 5,
            'sort_order' => 1,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        Video::create([
            'course_id' => $courseNeetPcb->id,
            'subject_id' => $subjChemistry->id,
            'title' => 'Chemical Kinetics: Order of Reaction & Half-Life',
            'description' => 'Zero, first, and second order rate equations, Arrhenius equation, and catalyst mechanisms.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'duration_seconds' => 2700,
            'view_limit' => 3,
            'sort_order' => 2,
            'is_preview' => false,
            'is_free' => false,
            'is_active' => true,
            'status' => 'ready',
        ]);

        // Course 3: Free Chemistry Course Video
        Video::create([
            'course_id' => $courseFreeChemistry->id,
            'subject_id' => $subjChemistry->id,
            'title' => 'Free Lecture: Balancing Chemical Redox Equations',
            'description' => 'Step-by-step ion electron method in acidic and basic mediums with worked exam problems.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyBlazes.mp4',
            'duration_seconds' => 1500,
            'view_limit' => 10,
            'sort_order' => 1,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        // Course 4: SSC / LDC Video
        Video::create([
            'course_id' => $courseSscLdc->id,
            'subject_id' => $subjPolity->id,
            'title' => 'Constitutional Preamble & Fundamental Rights (Articles 12-35)',
            'description' => 'Doctrine of basic structure, writs jurisdiction under Article 32 and Article 226.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
            'duration_seconds' => 2400,
            'view_limit' => 4,
            'sort_order' => 1,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);


        // ==========================================
        // 8. TESTS & TEST SERIES (Parent & Child Tests)
        // ==========================================
        $this->command->info('🧪 Seeding Standalone Tests & Test Series Hierarchy...');

        // 8.1 Standalone Free Diagnostic Test (price = 0)
        $testFreeDiagnostic = Test::create([
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjPhysics->id,
            'title' => 'Physics Diagnostic Assessment 2026',
            'slug' => 'physics-diagnostic-assessment-2026',
            'description' => 'Fast 30-minute benchmark assessment evaluating mechanics and dimensional analysis readiness.',
            'instructions' => '1. All questions carry equal marks (+4 for correct, -1 for incorrect).\n2. Calculator is not permitted.\n3. Do not refresh browser while taking the test.',
            'total_questions' => 5,
            'duration_minutes' => 30,
            'duration' => 30,
            'total_marks' => 20,
            'passing_marks' => 8,
            'negative_marking' => 1.00,
            'price' => 0.00,
            'is_free' => true,
            'is_active' => true,
            'is_test_series' => false,
            'parent_id' => null,
        ]);

        // 8.2 Standalone Paid Assessment Test
        $testNeetFullMock = Test::create([
            'course_id' => $courseNeetPcb->id,
            'subject_id' => $subjBiology->id,
            'title' => 'NEET All-India High-Yield Biology Mock 1',
            'slug' => 'neet-high-yield-biology-mock-1',
            'description' => 'Complete high-yield NCERT diagram-based mock test for medical aspirants.',
            'instructions' => 'Strict 45-minute countdown. Negative marking applies for wrong answers.',
            'total_questions' => 5,
            'duration_minutes' => 45,
            'duration' => 45,
            'total_marks' => 20,
            'passing_marks' => 10,
            'negative_marking' => 1.00,
            'price' => 199.00,
            'is_free' => false,
            'is_active' => true,
            'is_test_series' => false,
            'parent_id' => null,
        ]);

        // 8.3 Parent Test Series (Package with multiple child tests & attached subjects)
        $parentTestSeries = Test::create([
            'course_id' => $coursePhysics->id,
            'title' => 'All India JEE Main 2026 Grand Test Series',
            'slug' => 'all-india-jee-main-2026-grand-test-series',
            'description' => '5 Complete simulated mock exams structured on exact NTA computer-based exam interface.',
            'instructions' => 'Access each individual mock exam in sequential order. Detailed solution and percentile provided after submission.',
            'total_questions' => 15,
            'duration_minutes' => 180,
            'duration' => 180,
            'total_marks' => 300,
            'passing_marks' => 120,
            'negative_marking' => 1.00,
            'price' => 799.00,
            'is_free' => false,
            'is_active' => true,
            'is_test_series' => true, // TEST SERIES PARENT
            'parent_id' => null,
        ]);

        // Attach subjects to test series
        $parentTestSeries->subjects()->attach([
            $subjPhysics->id => ['question_count' => 5],
            $subjChemistry->id => ['question_count' => 5],
            $subjMathematics->id => ['question_count' => 5],
        ]);

        // 8.4 Child Test 1 (Under parent test series)
        $childTest1 = Test::create([
            'parent_id' => $parentTestSeries->id,
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjPhysics->id,
            'title' => 'Mock Paper 01: Physics Mechanics & Thermodynamics',
            'slug' => 'mock-paper-01-physics-mechanics',
            'test_number' => 1,
            'description' => 'Full test series paper 1 focusing on Mechanics, Work Energy Power, and Heat.',
            'instructions' => 'Time allowed: 60 minutes. Marks: +4 for correct, -1 for incorrect.',
            'total_questions' => 5,
            'duration_minutes' => 60,
            'duration' => 60,
            'total_marks' => 20,
            'passing_marks' => 8,
            'negative_marking' => 1.00,
            'price' => 0.00,
            'is_free' => false,
            'is_active' => true,
            'is_test_series' => false,
        ]);

        // 8.5 Child Test 2 (Under parent test series)
        $childTest2 = Test::create([
            'parent_id' => $parentTestSeries->id,
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjChemistry->id,
            'title' => 'Mock Paper 02: Chemistry Physical & Organic Reaction Mechanisms',
            'slug' => 'mock-paper-02-chemistry',
            'test_number' => 2,
            'description' => 'Full test series paper 2 focusing on Thermodynamics, Equilibrium, and Hydrocarbons.',
            'instructions' => 'Time allowed: 60 minutes. Marks: +4 for correct, -1 for incorrect.',
            'total_questions' => 5,
            'duration_minutes' => 60,
            'duration' => 60,
            'total_marks' => 20,
            'passing_marks' => 8,
            'negative_marking' => 1.00,
            'price' => 0.00,
            'is_free' => false,
            'is_active' => true,
            'is_test_series' => false,
        ]);

        // 8.6 Inactive / Draft Test (For testing inactive filters)
        $testInactive = Test::create([
            'course_id' => $courseSscLdc->id,
            'subject_id' => $subjPolity->id,
            'title' => 'Polity Tier-2 Speed Test (Draft Under Review)',
            'slug' => 'polity-tier-2-speed-test-draft',
            'description' => 'Review copy pending answer key verification by subject matter expert.',
            'instructions' => 'Not yet published for students.',
            'total_questions' => 5,
            'duration_minutes' => 20,
            'duration' => 20,
            'total_marks' => 20,
            'passing_marks' => 10,
            'negative_marking' => 0.50,
            'price' => 99.00,
            'is_free' => false,
            'is_active' => false, // INACTIVE TEST
            'is_test_series' => false,
            'parent_id' => null,
        ]);


        // ==========================================
        // 9. QUESTIONS (MCQ Item Bank with Rich Explanations)
        // ==========================================
        $this->command->info('❓ Seeding Questions with Keys & Educational Explanations...');

        // Questions for Free Diagnostic Test ($testFreeDiagnostic)
        Question::create([
            'test_id' => $testFreeDiagnostic->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'A vehicle accelerates uniformly from rest to a speed of 72 km/h in 10 seconds. What is the distance traversed in this interval?',
            'options' => [
                'a' => '100 meters',
                'b' => '200 meters',
                'c' => '50 meters',
                'd' => '150 meters',
            ],
            'correct_option' => 'a',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Speed = 72 km/h = 72 × (5/18) = 20 m/s. Acceleration a = (v - u)/t = 20/10 = 2 m/s². Distance s = ut + 0.5at² = 0 + 0.5 × 2 × 100 = 100 meters.',
        ]);

        Question::create([
            'test_id' => $testFreeDiagnostic->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'Which of the following physical quantities has the same dimensions as Planck\'s constant (h)?',
            'options' => [
                'a' => 'Force × Velocity',
                'b' => 'Angular Momentum',
                'c' => 'Linear Momentum',
                'd' => 'Energy × Time²',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Dimensions of Planck\'s constant h = [M L² T⁻¹]. Angular momentum L = mvr = [M][L][L T⁻¹] = [M L² T⁻¹]. Thus both possess identical dimensional formulas.',
        ]);

        Question::create([
            'test_id' => $testFreeDiagnostic->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'A body of mass 5 kg is sliding down an inclined plane of 30° at a constant velocity. What is the coefficient of kinetic friction (μk)?',
            'options' => [
                'a' => '1 / √3 (≈ 0.577)',
                'b' => '√3 / 2 (≈ 0.866)',
                'c' => '0.500',
                'd' => '1.000',
            ],
            'correct_option' => 'a',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'When a body moves down an inclined plane at constant velocity, acceleration is zero: mg sin(θ) = μk mg cos(θ). Therefore μk = tan(θ) = tan(30°) = 1/√3.',
        ]);

        Question::create([
            'test_id' => $testFreeDiagnostic->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'In a conservative force field, the work done along any closed path is always equal to:',
            'options' => [
                'a' => 'Positive infinity',
                'b' => 'Dependent on the path chosen',
                'c' => 'Zero',
                'd' => 'Total mechanical energy of the system',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'By fundamental definition, the line integral of a conservative force over any closed loop is identically zero: ∮ F · dr = 0.',
        ]);

        Question::create([
            'test_id' => $testFreeDiagnostic->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'The escape velocity from the surface of the Earth is approximately 11.2 km/s. If a projectile is fired at 45° to the horizontal, its escape velocity will be:',
            'options' => [
                'a' => '11.2 × sin(45°) km/s',
                'b' => '11.2 km/s',
                'c' => '11.2 × √2 km/s',
                'd' => '22.4 km/s',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Escape velocity ve = √(2GM/R) is completely independent of the projection angle. Hence it remains 11.2 km/s regardless of whether it is launched vertically or at 45°.',
        ]);

        // Questions for NEET Biology Mock ($testNeetFullMock)
        Question::create([
            'test_id' => $testNeetFullMock->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'Which cell organelle is responsible for the synthesis of steroidal hormones and lipid metabolism in animal cells?',
            'options' => [
                'a' => 'Rough Endoplasmic Reticulum',
                'b' => 'Smooth Endoplasmic Reticulum (SER)',
                'c' => 'Golgi Apparatus',
                'd' => 'Peroxisomes',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'The Smooth Endoplasmic Reticulum (SER) is the major site for synthesis of lipids and steroidal hormones in animal cells.',
        ]);

        Question::create([
            'test_id' => $testNeetFullMock->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'During which phase of Meiosis I does the process of crossing over (genetic recombination) occur?',
            'options' => [
                'a' => 'Leptotene',
                'b' => 'Zygotene',
                'c' => 'Pachytene',
                'd' => 'Diplotene',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Crossing over takes place at the Pachytene stage of Prophase I, mediated by the recombinase enzyme complex between non-sister chromatids.',
        ]);

        Question::create([
            'test_id' => $testNeetFullMock->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'Which of the following is considered the universal electron acceptor in anaerobic glycolysis?',
            'options' => [
                'a' => 'Oxygen',
                'b' => 'NAD⁺',
                'c' => 'FAD',
                'd' => 'Cytochrome c',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'In glycolysis, NAD⁺ acts as the oxidizing agent accepting electrons during the conversion of glyceraldehyde 3-phosphate to 1,3-bisphosphoglycerate.',
        ]);

        Question::create([
            'test_id' => $testNeetFullMock->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'The functional unit of human kidney where selective reabsorption and ultrafiltration occur is called:',
            'options' => [
                'a' => 'Neuron',
                'b' => 'Nephron',
                'c' => 'Alveolus',
                'd' => 'Hepatic Lobule',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Each human kidney contains approximately one million complex tubular structures called nephrons, which are the functional filtration units.',
        ]);

        Question::create([
            'test_id' => $testNeetFullMock->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'According to the competitive inhibition model, an increase in substrate concentration results in:',
            'options' => [
                'a' => 'Reduction of Km with constant Vmax',
                'b' => 'Overcoming the inhibitor effect, reaching normal Vmax',
                'c' => 'Permanent denaturation of enzyme',
                'd' => 'Decrease in both Vmax and Km',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'In competitive inhibition, Vmax remains unchanged because a high concentration of substrate outcompetes the inhibitor for the active site, while Km increases.',
        ]);

        // Questions for Child Test 1 ($childTest1)
        Question::create([
            'test_id' => $childTest1->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'The moment of inertia of a uniform solid sphere of mass M and radius R about its diametrical axis is:',
            'options' => [
                'a' => '(1/2) M R²',
                'b' => '(2/5) M R²',
                'c' => '(2/3) M R²',
                'd' => '(7/5) M R²',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'For a solid sphere about a central axis through its diameter, I = (2/5) M R². For a hollow spherical shell it is (2/3) M R².',
        ]);

        Question::create([
            'test_id' => $childTest1->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'A Carnot engine operating between 500 K and 300 K has an ideal thermodynamic efficiency of:',
            'options' => [
                'a' => '40%',
                'b' => '60%',
                'c' => '20%',
                'd' => '30%',
            ],
            'correct_option' => 'a',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Efficiency η = 1 - (T_cold / T_hot) = 1 - (300 / 500) = 1 - 0.6 = 0.40, which corresponds to 40%.',
        ]);

        // Questions for Child Test 2 ($childTest2)
        Question::create([
            'test_id' => $childTest2->id,
            'subject_id' => $subjChemistry->id,
            'question_text' => 'What is the hybridization state of the central carbon in the carbocation intermediate formed during SN1 reactions?',
            'options' => [
                'a' => 'sp',
                'b' => 'sp²',
                'c' => 'sp³',
                'd' => 'sp³d',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'A carbocation intermediate possesses three bonded groups and an empty p-orbital, adopting a planar geometry with sp² hybridization.',
        ]);

        Question::create([
            'test_id' => $childTest2->id,
            'subject_id' => $subjChemistry->id,
            'question_text' => 'Which of the following compounds will exhibit highest nucleophilic addition reactivity towards HCN?',
            'options' => [
                'a' => 'Formaldehyde (HCHO)',
                'b' => 'Acetaldehyde (CH₃CHO)',
                'c' => 'Acetone (CH₃COCH₃)',
                'd' => 'Acetophenone (C₆H₅COCH₃)',
            ],
            'correct_option' => 'a',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'HCHO has no electron donating alkyl groups and minimal steric hindrance, leaving its carbonyl carbon highly electrophilic.',
        ]);


        // ==========================================
        // 10. PURCHASES & TRANSACTIONS
        // ==========================================
        $this->command->info('💳 Seeding Customer Transactions (Completed, Pending, Failed, Refunded)...');

        // 10.1 Completed Course Purchase 1 (Razorpay)
        Purchase::create([
            'user_id' => $studentRohit->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $coursePhysics->id,
            'amount' => 1999.00,
            'currency' => 'INR',
            'payment_provider' => 'Razorpay',
            'payment_status' => 'completed',
            'payment_meta' => [
                'gateway' => 'Razorpay Gateway',
                'method' => 'UPI',
                'vpa' => 'rohit@okhdfcbank',
                'bank_ref' => 'REF202611250101',
            ],
            'order_id' => 'ORD-RZP-902148',
            'payment_id' => 'pay_Nz9Y7hK8L2vB01',
            'created_at' => Carbon::now()->subDays(12),
        ]);

        // 10.2 Completed Course Purchase 2 (Cashfree)
        Purchase::create([
            'user_id' => $studentPriya->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $courseNeetPcb->id,
            'amount' => 3499.00,
            'currency' => 'INR',
            'payment_provider' => 'Cashfree',
            'payment_status' => 'completed',
            'payment_meta' => [
                'gateway' => 'Cashfree Payments',
                'method' => 'Credit Card',
                'card_network' => 'VISA',
                'last4' => '4242',
            ],
            'order_id' => 'ORD-CF-881204',
            'payment_id' => 'cf_pay_189204712',
            'created_at' => Carbon::now()->subDays(8),
        ]);

        // 10.3 Completed Test Series Purchase 3 (Stripe / UPI)
        Purchase::create([
            'user_id' => $studentRohit->id,
            'purchasable_type' => Test::class,
            'purchasable_id' => $parentTestSeries->id,
            'amount' => 799.00,
            'currency' => 'INR',
            'payment_provider' => 'Razorpay',
            'payment_status' => 'completed',
            'payment_meta' => [
                'gateway' => 'Razorpay Gateway',
                'method' => 'NetBanking',
                'bank' => 'State Bank of India',
            ],
            'order_id' => 'ORD-TST-554109',
            'payment_id' => 'pay_Lk2X4jP9M1nC99',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // 10.4 Completed Purchase 4
        Purchase::create([
            'user_id' => $studentSneha->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $courseSscLdc->id,
            'amount' => 2499.00,
            'currency' => 'INR',
            'payment_provider' => 'Razorpay',
            'payment_status' => 'completed',
            'payment_meta' => [
                'gateway' => 'Razorpay Gateway',
                'method' => 'UPI',
                'vpa' => 'sneha@icici',
            ],
            'order_id' => 'ORD-RZP-440187',
            'payment_id' => 'pay_Qm8P2wK6L4vA33',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        // 10.5 Pending Purchase (Use Case: User initiated payment but callback awaiting)
        Purchase::create([
            'user_id' => $studentAmit->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $courseNeetPcb->id,
            'amount' => 3499.00,
            'currency' => 'INR',
            'payment_provider' => 'Razorpay',
            'payment_status' => 'pending',
            'payment_meta' => [
                'session_created' => true,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ],
            'order_id' => 'ORD-PND-331902',
            'payment_id' => null,
            'created_at' => Carbon::now()->subHours(2),
        ]);

        // 10.6 Failed Purchase (Use Case: Card declined or insufficient funds)
        Purchase::create([
            'user_id' => $studentVikram->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $coursePhysics->id,
            'amount' => 1999.00,
            'currency' => 'INR',
            'payment_provider' => 'Cashfree',
            'payment_status' => 'failed',
            'payment_meta' => [
                'error_code' => 'INSUFFICIENT_FUNDS',
                'error_description' => 'Issuing bank declined transaction due to insufficient balance.',
            ],
            'order_id' => 'ORD-FLD-771890',
            'payment_id' => 'cf_failed_992184',
            'created_at' => Carbon::now()->subDays(4),
        ]);

        // 10.7 Refunded Purchase (Use Case: Customer requested refund within 7 days)
        Purchase::create([
            'user_id' => $studentRahul->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $courseMathCrash->id,
            'amount' => 799.00,
            'currency' => 'INR',
            'payment_provider' => 'Razorpay',
            'payment_status' => 'refunded',
            'payment_meta' => [
                'refund_id' => 'rfnd_Kl99Pq1280Zx',
                'refund_reason' => 'Accidental duplicate subscription',
                'refund_amount' => 799.00,
            ],
            'order_id' => 'ORD-RFD-119284',
            'payment_id' => 'pay_Yy7U5rT3W1qZ22',
            'created_at' => Carbon::now()->subDays(15),
        ]);


        // ==========================================
        // 11. TEST ATTEMPTS (Student Submissions)
        // ==========================================
        $this->command->info('📊 Seeding Student Test Attempts & Performance Scores...');

        // 11.1 Completed High-Scoring Attempt (Rohit)
        TestAttempt::create([
            'user_id' => $studentRohit->id,
            'test_id' => $testFreeDiagnostic->id,
            'started_at' => Carbon::now()->subDays(2)->subMinutes(25),
            'finished_at' => Carbon::now()->subDays(2),
            'score' => 20.00, // 5 / 5 correct
            'answers' => [
                '1' => 'a',
                '2' => 'b',
                '3' => 'a',
                '4' => 'c',
                '5' => 'b',
            ],
            'status' => 'completed',
        ]);

        // 11.2 Completed Moderate-Scoring Attempt (Priya)
        TestAttempt::create([
            'user_id' => $studentPriya->id,
            'test_id' => $testNeetFullMock->id,
            'started_at' => Carbon::now()->subDays(1)->subMinutes(40),
            'finished_at' => Carbon::now()->subDays(1),
            'score' => 15.00, // 4 correct, 1 wrong
            'answers' => [
                '6' => 'b',
                '7' => 'c',
                '8' => 'b',
                '9' => 'b',
                '10' => 'a', // incorrect, correct is b
            ],
            'status' => 'completed',
        ]);

        // 11.3 In-Progress Test Attempt (Pooja currently taking test)
        TestAttempt::create([
            'user_id' => $studentPooja->id,
            'test_id' => $testFreeDiagnostic->id,
            'started_at' => Carbon::now()->subMinutes(12),
            'finished_at' => null,
            'score' => 0.00,
            'answers' => [
                '1' => 'a',
                '2' => 'b',
            ],
            'status' => 'in_progress', // IN PROGRESS
        ]);

        // 11.4 Abandoned Test Attempt (Amit closed tab / disconnected)
        TestAttempt::create([
            'user_id' => $studentAmit->id,
            'test_id' => $childTest1->id,
            'started_at' => Carbon::now()->subDays(6)->subMinutes(60),
            'finished_at' => Carbon::now()->subDays(6)->subMinutes(50),
            'score' => 4.00,
            'answers' => [
                '11' => 'b',
            ],
            'status' => 'abandoned', // ABANDONED
        ]);


        // ==========================================
        // 12. VIDEO VIEW LIMIT TRACKING
        // ==========================================
        $this->command->info('👁️ Seeding Video View Limits & Watch Records...');

        $firstVideo = Video::first();
        $secondVideo = Video::skip(1)->first();

        // 12.1 Student who reached view limit (Max: 3, Viewed: 3)
        if ($firstVideo) {
            VideoViewLimit::create([
                'video_id' => $firstVideo->id,
                'user_id' => $studentAnanya->id,
                'max_views' => 3,
                'viewed_count' => 3, // LIMIT EXHAUSTED
                'last_viewed_at' => Carbon::now()->subHours(5),
            ]);

            // 12.2 Student with 1 view consumed (2 views remaining)
            VideoViewLimit::create([
                'video_id' => $firstVideo->id,
                'user_id' => $studentRohit->id,
                'max_views' => 3,
                'viewed_count' => 1,
                'last_viewed_at' => Carbon::now()->subDays(1),
            ]);
        }

        if ($secondVideo) {
            // 12.3 Student with 2 views consumed (1 view remaining)
            VideoViewLimit::create([
                'video_id' => $secondVideo->id,
                'user_id' => $studentPriya->id,
                'max_views' => 3,
                'viewed_count' => 2,
                'last_viewed_at' => Carbon::now()->subDays(2),
            ]);
        }


        // ==========================================
        // 13. OTPS (For MSG91 & Verification Audit)
        // ==========================================
        $this->command->info('📱 Seeding OTP Logs...');

        // Active valid OTP (Valid for next 8 mins)
        Otp::create([
            'phone' => '9876543210',
            'otp' => '6821',
            'expires_at' => Carbon::now()->addMinutes(8),
            'is_verified' => false,
        ]);

        // Used OTP (Already verified)
        Otp::create([
            'phone' => '9876543211',
            'otp' => '3914',
            'expires_at' => Carbon::now()->subMinutes(15),
            'is_verified' => true,
        ]);

        // Expired unused OTP
        Otp::create([
            'phone' => '9876543212',
            'otp' => '9045',
            'expires_at' => Carbon::now()->subMinutes(45),
            'is_verified' => false,
        ]);


        // ==========================================
        // 14. SETTINGS
        // ==========================================
        $this->command->info('⚙️ Seeding Application Settings...');

        $settings = [
            [
                'key' => 'app_name',
                'value' => 'Olive Learnings',
                'type' => 'string',
                'description' => 'Official name of the learning portal',
            ],
            [
                'key' => 'default_video_view_limit',
                'value' => '3',
                'type' => 'integer',
                'description' => 'Default maximum times a student can stream any lecture video',
            ],
            [
                'key' => 'support_email',
                'value' => 'support@olivelearnings.com',
                'type' => 'string',
                'description' => 'Primary student helpdesk & grievance email',
            ],
            [
                'key' => 'support_phone',
                'value' => '+91 98765 43210',
                'type' => 'string',
                'description' => 'Toll-free customer support helpline',
            ],
            [
                'key' => 'currency_symbol',
                'value' => '₹',
                'type' => 'string',
                'description' => 'Base currency symbol used across checkout',
            ],
            [
                'key' => 'msg91_sender_id',
                'value' => 'CIS015',
                'type' => 'string',
                'description' => 'Configured MSG91 SMS DLT approved Sender ID',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Flag to temporarily restrict portal during upgrades',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('====================================================');
        $this->command->info('✅ COMPLETE TEST DATA SEEDED SUCCESSFULLY FOR ALL USE CASES!');
        $this->command->info('====================================================');
        $this->command->info('🔑 Admin Login: admin@eduapp.com | Password: password');
        $this->command->info('🔑 Second Admin: academic@olivelearnings.com | Password: password');
        $this->command->info('👨‍🎓 Student 1 (Enrolled & Active): rohit.sharma@gmail.com | Password: password');
        $this->command->info('👨‍🎓 Student 2 (NEET Enrolled): priya.verma@gmail.com | Password: password');
        $this->command->info('👨‍🎓 Student 3 (Pending Purchase): amit.patel@gmail.com | Password: password');
        $this->command->info('👨‍🎓 Student 4 (Limit Reached): ananya.singh@gmail.com | Password: password');
        $this->command->info('👨‍🎓 Student 5 (Refund/Decline): vikram.aditya@gmail.com | Password: password');
        $this->command->info('👨‍🎓 Student 6 (Suspended): dev.kumar@gmail.com | Password: password');
        $this->command->info('====================================================');
    }
}
