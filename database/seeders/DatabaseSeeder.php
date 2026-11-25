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

        // 6.4 100% Free Course: Vedic Mathematics & Super Fast Calculation Tricks
        $courseFreeVedicMath = Course::create([
            'exam_board_id' => $boardCompetitive->id,
            'title' => 'Free Vedic Mathematics & Rapid Calculation Tricks',
            'slug' => 'free-vedic-mathematics-rapid-calculation-tricks',
            'description' => 'Master mental arithmetic, lightning-fast multiplication, square roots, and algebraic shortcuts essential for all competitive and board exams.',
            'price' => 0.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?auto=format&fit=crop&w=800&q=80',
            'created_by' => $superAdmin->id,
        ]);
        $courseFreeVedicMath->subjects()->attach($subjMathematics->id, ['sort_order' => 0]);

        // 6.5 100% Free Course: Daily Current Affairs & General Studies 2026
        $courseFreeCurrentAffairs = Course::create([
            'exam_board_id' => $boardCompetitive->id,
            'title' => 'Daily Current Affairs & General Studies Foundation 2026',
            'slug' => 'daily-current-affairs-general-studies-2026',
            'description' => 'Daily analytical video series and editorial breakdown covering Indian Constitution, Union Budget, Science & Tech, and Global Affairs.',
            'price' => 0.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=800&q=80',
            'created_by' => $academicAdmin->id,
        ]);
        $courseFreeCurrentAffairs->subjects()->attach([
            $subjGeneralStudies->id => ['sort_order' => 0],
            $subjPolity->id => ['sort_order' => 1],
        ]);

        // 6.6 100% Free Course: Spoken English & Grammar Masterclass
        $courseFreeEnglish = Course::create([
            'exam_board_id' => $boardCbse->id,
            'title' => 'Free Spoken English & Grammar Foundation Masterclass',
            'slug' => 'free-spoken-english-grammar-masterclass',
            'description' => 'Complete zero-cost English module covering Subject-Verb Agreement, Active-Passive Voice, Vocabulary Roots, and Everyday Conversation.',
            'price' => 0.00,
            'currency' => 'INR',
            'is_published' => true,
            'thumbnail_path' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=800&q=80',
            'created_by' => $superAdmin->id,
        ]);
        $courseFreeEnglish->subjects()->attach($subjEnglish->id, ['sort_order' => 0]);

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

        // Course 5: Free Vedic Mathematics Videos
        Video::create([
            'course_id' => $courseFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'title' => '01. Mental Maths: 11-Rule, Criss-Cross & Base Multiplication Tricks',
            'description' => 'Solve 2-digit and 3-digit multiplication in seconds without paper calculation.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration_seconds' => 1200,
            'view_limit' => 10,
            'sort_order' => 1,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        Video::create([
            'course_id' => $courseFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'title' => '02. Lightning Fast Square Roots & Cube Roots in 5 Seconds',
            'description' => 'Shortcut elimination method for perfect and non-perfect roots.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'duration_seconds' => 1500,
            'view_limit' => 10,
            'sort_order' => 2,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        // Course 6: Free Current Affairs & General Studies Videos
        Video::create([
            'course_id' => $courseFreeCurrentAffairs->id,
            'subject_id' => $subjPolity->id,
            'title' => '01. Landmark Supreme Court Cases & Fundamental Rights Overview',
            'description' => 'Kesavananda Bharati, Maneka Gandhi, and recent constitutional bench decisions explained.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'duration_seconds' => 1800,
            'view_limit' => 10,
            'sort_order' => 1,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        Video::create([
            'course_id' => $courseFreeCurrentAffairs->id,
            'subject_id' => $subjGeneralStudies->id,
            'title' => '02. Union Budget 2026 Analysis & Fiscal Deficit Explained',
            'description' => 'Capital expenditure trends, tax slab restructuring, and major government schemes.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'duration_seconds' => 2100,
            'view_limit' => 10,
            'sort_order' => 2,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        // Course 7: Free Spoken English Videos
        Video::create([
            'course_id' => $courseFreeEnglish->id,
            'subject_id' => $subjEnglish->id,
            'title' => '01. Master 10 Essential Rules of Subject-Verb Agreement',
            'description' => 'Avoid the most common grammatical pitfalls tested in competitive and board exams.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'duration_seconds' => 1350,
            'view_limit' => 10,
            'sort_order' => 1,
            'is_preview' => true,
            'is_free' => true,
            'is_active' => true,
            'status' => 'ready',
        ]);

        Video::create([
            'course_id' => $courseFreeEnglish->id,
            'subject_id' => $subjEnglish->id,
            'title' => '02. Power Vocabulary: 50 Root Words That Unlock 500 English Words',
            'description' => 'Etymological breakdown of Greek and Latin roots to build an unstoppable lexicon.',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyBlazes.mp4',
            'duration_seconds' => 1600,
            'view_limit' => 10,
            'sort_order' => 2,
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

        // 8.7 100% FREE Parent Test Series 1: All India Free Scholarship & Diagnostic Mock Test Series
        $freeTestSeriesScholarship = Test::create([
            'course_id' => $coursePhysics->id,
            'title' => 'All India Free Scholarship & Diagnostic Mock Test Series 2026',
            'slug' => 'all-india-free-scholarship-mock-test-series-2026',
            'description' => 'National-level free diagnostic mock series for JEE & NEET aspirants with all-India percentile ranking and detailed solutions.',
            'instructions' => 'Free access for all students. Answer all MCQs within the allotted duration. Negative marking applies (-1 per wrong answer).',
            'total_questions' => 10,
            'duration_minutes' => 60,
            'duration' => 60,
            'total_marks' => 40,
            'passing_marks' => 16,
            'negative_marking' => 1.00,
            'price' => 0.00,
            'is_free' => true, // 100% FREE TEST SERIES
            'is_active' => true,
            'is_test_series' => true, // PARENT TEST SERIES
            'parent_id' => null,
        ]);

        $freeTestSeriesScholarship->subjects()->attach([
            $subjPhysics->id => ['question_count' => 5],
            $subjChemistry->id => ['question_count' => 5],
        ]);

        // Child Test 1 (Under Free Scholarship Series)
        $freeChildTest1 = Test::create([
            'parent_id' => $freeTestSeriesScholarship->id,
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjPhysics->id,
            'title' => 'Free Mock 01: Physics Mechanics & Dimensional Analysis',
            'slug' => 'free-mock-01-physics-mechanics',
            'test_number' => 1,
            'description' => 'Complete free mock test covering Kinematics, Newton Laws, Work Power Energy, and Units.',
            'instructions' => '5 questions, 20 marks, 30 minutes duration. +4 for correct, -1 for wrong.',
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
        ]);

        // Child Test 2 (Under Free Scholarship Series)
        $freeChildTest2 = Test::create([
            'parent_id' => $freeTestSeriesScholarship->id,
            'course_id' => $coursePhysics->id,
            'subject_id' => $subjChemistry->id,
            'title' => 'Free Mock 02: Chemistry Atomic Structure & Bonding',
            'slug' => 'free-mock-02-chemistry-atomic-structure',
            'test_number' => 2,
            'description' => 'Free practice paper covering Bohr Model, Quantum Numbers, and Chemical Bonding.',
            'instructions' => '5 questions, 20 marks, 30 minutes duration. +4 for correct, -1 for wrong.',
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
        ]);

        // 8.8 100% FREE Parent Test Series 2: SSC & Govt Exams Free Weekly Mock Challenge
        $freeTestSeriesSsc = Test::create([
            'course_id' => $courseSscLdc->id,
            'title' => 'SSC & Govt Exams Free Weekly Mock Challenge 2026',
            'slug' => 'ssc-govt-exams-free-weekly-mock-challenge-2026',
            'description' => 'Weekly free simulated test series designed for SSC CGL, CHSL, and State Public Service Commission aspirants.',
            'instructions' => 'Negative marking of 0.50 applies per incorrect answer. Review explanations after finishing.',
            'total_questions' => 10,
            'duration_minutes' => 45,
            'duration' => 45,
            'total_marks' => 40,
            'passing_marks' => 16,
            'negative_marking' => 0.50,
            'price' => 0.00,
            'is_free' => true, // 100% FREE TEST SERIES
            'is_active' => true,
            'is_test_series' => true, // PARENT TEST SERIES
            'parent_id' => null,
        ]);

        $freeTestSeriesSsc->subjects()->attach([
            $subjPolity->id => ['question_count' => 5],
            $subjEnglish->id => ['question_count' => 5],
        ]);

        // Child Test 1 (Under Free SSC Series)
        $freeSscChild1 = Test::create([
            'parent_id' => $freeTestSeriesSsc->id,
            'course_id' => $courseSscLdc->id,
            'subject_id' => $subjPolity->id,
            'title' => 'SSC Weekly Mock 01: Indian Constitution & Polity Essentials',
            'slug' => 'ssc-weekly-mock-01-indian-constitution',
            'test_number' => 1,
            'description' => 'Covers Preamble, Fundamental Rights, Directive Principles, and President powers.',
            'instructions' => '5 questions, 20 marks, 20 minutes duration. +4 for correct, -0.5 for wrong.',
            'total_questions' => 5,
            'duration_minutes' => 20,
            'duration' => 20,
            'total_marks' => 20,
            'passing_marks' => 8,
            'negative_marking' => 0.50,
            'price' => 0.00,
            'is_free' => true,
            'is_active' => true,
            'is_test_series' => false,
        ]);

        // Child Test 2 (Under Free SSC Series)
        $freeSscChild2 = Test::create([
            'parent_id' => $freeTestSeriesSsc->id,
            'course_id' => $courseSscLdc->id,
            'subject_id' => $subjEnglish->id,
            'title' => 'SSC Weekly Mock 02: English Vocab & Sentence Improvement',
            'slug' => 'ssc-weekly-mock-02-english-vocab',
            'test_number' => 2,
            'description' => 'Covers Synonyms, Antonyms, Idioms, One Word Substitutions, and Cloze Test.',
            'instructions' => '5 questions, 20 marks, 20 minutes duration. +4 for correct, -0.5 for wrong.',
            'total_questions' => 5,
            'duration_minutes' => 20,
            'duration' => 20,
            'total_marks' => 20,
            'passing_marks' => 8,
            'negative_marking' => 0.50,
            'price' => 0.00,
            'is_free' => true,
            'is_active' => true,
            'is_test_series' => false,
        ]);

        // 8.9 Standalone Free Test 2: NEET Biology Rapid Revision Test
        $testFreeBiology = Test::create([
            'course_id' => $courseNeetPcb->id,
            'subject_id' => $subjBiology->id,
            'title' => 'NEET Biology Free Rapid Fire Quiz (Genetics & Physiology)',
            'slug' => 'neet-biology-free-rapid-fire-quiz',
            'description' => 'High-yield 5 question NCERT based diagnostic quiz to test genetics and human body systems.',
            'instructions' => 'Each correct answer gives +4 marks; each incorrect deducts -1 mark.',
            'total_questions' => 5,
            'duration_minutes' => 15,
            'duration' => 15,
            'total_marks' => 20,
            'passing_marks' => 8,
            'negative_marking' => 1.00,
            'price' => 0.00,
            'is_free' => true,
            'is_active' => true,
            'is_test_series' => false,
            'parent_id' => null,
        ]);

        // 8.10 Standalone Free Test 3: Vedic Maths & Quantitative Speed Test
        $testFreeVedicMath = Test::create([
            'course_id' => $courseFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'title' => 'Vedic Maths & Speed Calculation Free Challenge',
            'slug' => 'vedic-maths-speed-calculation-free-challenge',
            'description' => 'Test your mental calculation speed with 5 high-speed arithmetic questions.',
            'instructions' => 'Try to solve each question in under 30 seconds without scratch paper.',
            'total_questions' => 5,
            'duration_minutes' => 10,
            'duration' => 10,
            'total_marks' => 20,
            'passing_marks' => 8,
            'negative_marking' => 1.00,
            'price' => 0.00,
            'is_free' => true,
            'is_active' => true,
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

        // ========================================================
        // Questions for Free Scholarship Series - Child Test 1 (Physics)
        // ========================================================
        Question::create([
            'test_id' => $freeChildTest1->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'A ball is thrown vertically upwards with a velocity of 20 m/s. Taking g = 10 m/s², what is the maximum height attained?',
            'options' => [
                'a' => '15 meters',
                'b' => '20 meters',
                'c' => '25 meters',
                'd' => '30 meters',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Using v² = u² - 2gh. At maximum height, v = 0. Therefore h = u² / 2g = (20 × 20) / (2 × 10) = 400 / 20 = 20 meters.',
        ]);

        Question::create([
            'test_id' => $freeChildTest1->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'The SI unit of magnetic flux is:',
            'options' => [
                'a' => 'Tesla',
                'b' => 'Weber',
                'c' => 'Henry',
                'd' => 'Gauss',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Magnetic flux is measured in Weber (Wb) in the SI system. Tesla (T) is the unit of magnetic flux density (B).',
        ]);

        Question::create([
            'test_id' => $freeChildTest1->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'Two forces of magnitude 6 N and 8 N act at a right angle (90°) to each other. What is the magnitude of their resultant force?',
            'options' => [
                'a' => '14 N',
                'b' => '10 N',
                'c' => '2 N',
                'd' => '7 N',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Resultant R = √(A² + B² + 2AB cos θ). For θ = 90°, R = √(6² + 8²) = √(36 + 64) = √100 = 10 N.',
        ]);

        Question::create([
            'test_id' => $freeChildTest1->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'Which thermodynamic process occurs at constant volume?',
            'options' => [
                'a' => 'Isothermal',
                'b' => 'Isobaric',
                'c' => 'Isochoric',
                'd' => 'Adiabatic',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'An isochoric (or isometric) process is one in which the volume remains strictly constant (ΔV = 0, Work W = 0).',
        ]);

        Question::create([
            'test_id' => $freeChildTest1->id,
            'subject_id' => $subjPhysics->id,
            'question_text' => 'A convex lens of focal length 20 cm produces a real image of the same size as the object. The distance of the object from the lens is:',
            'options' => [
                'a' => '10 cm',
                'b' => '20 cm',
                'c' => '40 cm',
                'd' => '60 cm',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'A convex lens forms a real, inverted image of the same size when the object is placed at 2F (twice the focal length). Here 2f = 2 × 20 = 40 cm.',
        ]);

        // ========================================================
        // Questions for Free Scholarship Series - Child Test 2 (Chemistry)
        // ========================================================
        Question::create([
            'test_id' => $freeChildTest2->id,
            'subject_id' => $subjChemistry->id,
            'question_text' => 'What is the total number of orbitals associated with the principal quantum number n = 3?',
            'options' => [
                'a' => '3',
                'b' => '6',
                'c' => '9',
                'd' => '18',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'The total number of orbitals in any principal shell is given by n². For n = 3, n² = 3² = 9 (1 s-orbital, 3 p-orbitals, and 5 d-orbitals).',
        ]);

        Question::create([
            'test_id' => $freeChildTest2->id,
            'subject_id' => $subjChemistry->id,
            'question_text' => 'Which of the following molecules has a dipole moment equal to zero?',
            'options' => [
                'a' => 'H₂O',
                'b' => 'NH₃',
                'c' => 'BF₃',
                'd' => 'SO₂',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'BF₃ possesses a symmetrical trigonal planar geometry (120° bond angles). The individual B-F bond dipoles cancel out symmetrically giving a net dipole moment μ = 0.',
        ]);

        Question::create([
            'test_id' => $freeChildTest2->id,
            'subject_id' => $subjChemistry->id,
            'question_text' => 'The pH of a 0.001 M hydrochloric acid (HCl) solution at 25°C is:',
            'options' => [
                'a' => '1',
                'b' => '2',
                'c' => '3',
                'd' => '4',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'HCl is a strong monoprotic acid. [H⁺] = 10⁻³ M. pH = -log₁₀[H⁺] = -log₁₀(10⁻³) = 3.',
        ]);

        Question::create([
            'test_id' => $freeChildTest2->id,
            'subject_id' => $subjChemistry->id,
            'question_text' => 'According to Le Chatelier\'s principle, increasing the pressure in the synthesis of ammonia (N₂ + 3H₂ ⇌ 2NH₃) will:',
            'options' => [
                'a' => 'Shift equilibrium forward (more NH₃)',
                'b' => 'Shift equilibrium backward',
                'c' => 'Have no effect on equilibrium',
                'd' => 'Decompose ammonia immediately',
            ],
            'correct_option' => 'a',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Increasing pressure favors the direction with fewer moles of gas. Reactants = 4 moles, products = 2 moles. Thus equilibrium shifts towards products (forward).',
        ]);

        Question::create([
            'test_id' => $freeChildTest2->id,
            'subject_id' => $subjChemistry->id,
            'question_text' => 'Which element has the highest electronegativity on the Pauling scale?',
            'options' => [
                'a' => 'Oxygen (O)',
                'b' => 'Fluorine (F)',
                'c' => 'Chlorine (Cl)',
                'd' => 'Nitrogen (N)',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Fluorine is the most electronegative element with a Pauling value of 3.98 (approximately 4.0).',
        ]);

        // ========================================================
        // Questions for Free SSC Mock Series - Child Test 1 (Polity)
        // ========================================================
        Question::create([
            'test_id' => $freeSscChild1->id,
            'subject_id' => $subjPolity->id,
            'question_text' => 'Which Article of the Indian Constitution is referred to as the "Heart and Soul of the Constitution" by Dr. B.R. Ambedkar?',
            'options' => [
                'a' => 'Article 14 (Equality before Law)',
                'b' => 'Article 19 (Right to Freedom)',
                'c' => 'Article 21 (Protection of Life & Liberty)',
                'd' => 'Article 32 (Right to Constitutional Remedies)',
            ],
            'correct_option' => 'd',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'Article 32 provides the right to approach the Supreme Court for enforcement of fundamental rights, termed the Heart and Soul by Dr. Ambedkar.',
        ]);

        Question::create([
            'test_id' => $freeSscChild1->id,
            'subject_id' => $subjPolity->id,
            'question_text' => 'What is the minimum age prescribed for appointment as the Governor of an Indian State?',
            'options' => [
                'a' => '25 years',
                'b' => '30 years',
                'c' => '35 years',
                'd' => '40 years',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'According to Article 157, a person must be an Indian citizen and have completed the age of 35 years to be eligible for appointment as Governor.',
        ]);

        Question::create([
            'test_id' => $freeSscChild1->id,
            'subject_id' => $subjPolity->id,
            'question_text' => 'The concept of Directive Principles of State Policy (DPSP) in the Indian Constitution was borrowed from which country?',
            'options' => [
                'a' => 'United States of America',
                'b' => 'Ireland',
                'c' => 'United Kingdom',
                'd' => 'Canada',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'Part IV of the Indian Constitution (DPSPs, Articles 36-51) was inspired by the Irish Constitution of 1937.',
        ]);

        Question::create([
            'test_id' => $freeSscChild1->id,
            'subject_id' => $subjPolity->id,
            'question_text' => 'Who presides over the Joint Sitting of both Houses of Parliament in India?',
            'options' => [
                'a' => 'President of India',
                'b' => 'Vice-President of India',
                'c' => 'Speaker of Lok Sabha',
                'd' => 'Prime Minister of India',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'Under Article 118(4), the Speaker of Lok Sabha presides over a joint sitting of Parliament called by the President under Article 108.',
        ]);

        Question::create([
            'test_id' => $freeSscChild1->id,
            'subject_id' => $subjPolity->id,
            'question_text' => 'Under which Constitutional Amendment was the Right to Property removed from the list of Fundamental Rights?',
            'options' => [
                'a' => '42nd Amendment Act, 1976',
                'b' => '44th Amendment Act, 1978',
                'c' => '52nd Amendment Act, 1985',
                'd' => '73rd Amendment Act, 1992',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'The 44th Constitutional Amendment Act of 1978 removed Right to Property from Part III and made it a legal right under Article 300A.',
        ]);

        // ========================================================
        // Questions for Free SSC Mock Series - Child Test 2 (English)
        // ========================================================
        Question::create([
            'test_id' => $freeSscChild2->id,
            'subject_id' => $subjEnglish->id,
            'question_text' => 'Select the most appropriate SYNONYM of the given word: "EPHEMERAL"',
            'options' => [
                'a' => 'Eternal',
                'b' => 'Transitory',
                'c' => 'Enormous',
                'd' => 'Resilient',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'Ephemeral means lasting for a very short time. Transitory is an exact synonym. Eternal is the antonym.',
        ]);

        Question::create([
            'test_id' => $freeSscChild2->id,
            'subject_id' => $subjEnglish->id,
            'question_text' => 'Identify the segment containing a grammatical error: "Neither of the two students / were present in the classroom / during the seminar."',
            'options' => [
                'a' => 'Neither of the two students',
                'b' => 'were present in the classroom',
                'c' => 'during the seminar',
                'd' => 'No error',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => '"Neither" is singular when referring to individual choices. It takes a singular verb "was present" instead of plural "were present".',
        ]);

        Question::create([
            'test_id' => $freeSscChild2->id,
            'subject_id' => $subjEnglish->id,
            'question_text' => 'What is the meaning of the idiom: "To spill the beans"?',
            'options' => [
                'a' => 'To waste food carelessly',
                'b' => 'To reveal a secret prematurely',
                'c' => 'To work hard in fields',
                'd' => 'To lose temper suddenly',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => '"Spill the beans" is an idiom that means divulging secret or confidential information without intended timing.',
        ]);

        Question::create([
            'test_id' => $freeSscChild2->id,
            'subject_id' => $subjEnglish->id,
            'question_text' => 'Choose the correct ONE-WORD substitution: "A person who renounces a religious or political belief"',
            'options' => [
                'a' => 'Apostate',
                'b' => 'Agnostic',
                'c' => 'Altruist',
                'd' => 'Ascetic',
            ],
            'correct_option' => 'a',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'An apostate is someone who abandons or rejects a religious or political principle. Agnostic questions divine certainty.',
        ]);

        Question::create([
            'test_id' => $freeSscChild2->id,
            'subject_id' => $subjEnglish->id,
            'question_text' => 'Fill in the blank with appropriate preposition: "She has been suffering from fever _____ Monday last."',
            'options' => [
                'a' => 'for',
                'b' => 'since',
                'c' => 'from',
                'd' => 'by',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 0.50,
            'explanation' => 'In present perfect continuous tense, "since" denotes a specific point in time (point of beginning), whereas "for" denotes a duration.',
        ]);

        // ========================================================
        // Questions for Standalone Free Test: NEET Biology Rapid Quiz
        // ========================================================
        Question::create([
            'test_id' => $testFreeBiology->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'Which blood vessel carries oxygenated blood from the lungs into the left atrium of the human heart?',
            'options' => [
                'a' => 'Pulmonary Artery',
                'b' => 'Pulmonary Vein',
                'c' => 'Coronary Sinus',
                'd' => 'Superior Vena Cava',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'The pulmonary vein is an exception among veins; it carries freshly oxygenated blood from lungs back into the heart\'s left atrium.',
        ]);

        Question::create([
            'test_id' => $testFreeBiology->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'The functional unit of the human kidney where filtration of blood occurs is called:',
            'options' => [
                'a' => 'Neuron',
                'b' => 'Nephron',
                'c' => 'Alveolus',
                'd' => 'Glomerulus capsule',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'The nephron is the structural and functional microscopic filtration unit of the kidney. Each human kidney contains ~1 million nephrons.',
        ]);

        Question::create([
            'test_id' => $testFreeBiology->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'Mendel\'s famous phenotypic dihybrid cross ratio in the F2 generation is:',
            'options' => [
                'a' => '3 : 1',
                'b' => '1 : 2 : 1',
                'c' => '9 : 3 : 3 : 1',
                'd' => '9 : 7',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'In dihybrid inheritance with independent assortment, the F2 generation produces 9 round-yellow : 3 round-green : 3 wrinkled-yellow : 1 wrinkled-green.',
        ]);

        Question::create([
            'test_id' => $testFreeBiology->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'Which hormone is known as the "Emergency Hormone" or "Fight-or-Flight Hormone"?',
            'options' => [
                'a' => 'Insulin',
                'b' => 'Thyroxine',
                'c' => 'Adrenaline (Epinephrine)',
                'd' => 'Glucagon',
            ],
            'correct_option' => 'c',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Adrenaline secreted by the adrenal medulla prepares the body to face stressful and emergency conditions.',
        ]);

        Question::create([
            'test_id' => $testFreeBiology->id,
            'subject_id' => $subjBiology->id,
            'question_text' => 'In DNA, adenine pairs with thymine via how many hydrogen bonds?',
            'options' => [
                'a' => '1 hydrogen bond',
                'b' => '2 hydrogen bonds',
                'c' => '3 hydrogen bonds',
                'd' => 'Covalent peptide bond',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Adenine forms 2 hydrogen bonds with Thymine (A = T), while Guanine forms 3 hydrogen bonds with Cytosine (G ≡ C).',
        ]);

        // ========================================================
        // Questions for Standalone Free Test: Vedic Maths Speed Test
        // ========================================================
        Question::create([
            'test_id' => $testFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'question_text' => 'What is the product of 98 × 97 using the Vedic Maths Nikhilam (Base 100) sutra?',
            'options' => [
                'a' => '9506',
                'b' => '9406',
                'c' => '9606',
                'd' => '9504',
            ],
            'correct_option' => 'a',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Base = 100. Deficiencies: -2 and -3. Left part: 98 - 3 = 95. Right part: (-2) × (-3) = 06. Result = 9506.',
        ]);

        Question::create([
            'test_id' => $testFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'question_text' => 'Using the Ekadhikena Purvena rule, what is the square of 75 (75²)?',
            'options' => [
                'a' => '5425',
                'b' => '5625',
                'c' => '5825',
                'd' => '5725',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'For numbers ending in 5: Left = 7 × (7 + 1) = 7 × 8 = 56. Right = 5² = 25. Together = 5625.',
        ]);

        Question::create([
            'test_id' => $testFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'question_text' => 'Multiply 42 × 11 in mind using the Vedic 11-Multiplication rule:',
            'options' => [
                'a' => '452',
                'b' => '462',
                'c' => '472',
                'd' => '482',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => 'Keep outer digits 4 and 2. Sum middle: 4 + 2 = 6. Insert between them: 462.',
        ]);

        Question::create([
            'test_id' => $testFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'question_text' => 'What is 15% of 480 calculated mentally?',
            'options' => [
                'a' => '68',
                'b' => '72',
                'c' => '76',
                'd' => '80',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => '10% of 480 = 48. 5% of 480 = half of 48 = 24. 15% = 48 + 24 = 72.',
        ]);

        Question::create([
            'test_id' => $testFreeVedicMath->id,
            'subject_id' => $subjMathematics->id,
            'question_text' => 'What is the square root of 5184 (√5184)?',
            'options' => [
                'a' => '68',
                'b' => '72',
                'c' => '78',
                'd' => '82',
            ],
            'correct_option' => 'b',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'explanation' => '70² = 4900, 80² = 6400. 5184 ends in 4, so unit digit is 2 or 8. Since 5184 is closer to 4900 than 6400, √5184 = 72.',
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
