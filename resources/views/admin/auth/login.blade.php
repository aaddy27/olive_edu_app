<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Olive Learnings Admin</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            indigo: {
                                50: '#eef2ff',
                                100: '#e0e7ff',
                                200: '#c7d2fe',
                                500: '#6366f1',
                                600: '#4f46e5',
                                700: '#4338ca',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <div class="min-h-full flex flex-col lg:flex-row">
        
        <!-- Left Showcase Side (Hero Educational Illustration & Branding) -->
        <div class="relative hidden lg:flex lg:w-1/2 xl:w-7/12 flex-col justify-between p-12 overflow-hidden bg-slate-900">
            <!-- Background Image -->
            <img 
                src="/images/login-bg.jpg" 
                alt="Olive Learnings Campus" 
                class="absolute inset-0 h-full w-full object-cover object-center"
            >
            <!-- Elegant Depth & Contrast Overlays -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-slate-900/30"></div>
            <div class="absolute inset-0 bg-indigo-950/10 mix-blend-multiply"></div>

            <!-- Top Brand Emblem Over Image -->
            <div class="relative z-10 flex items-center gap-3.5">
                <div class="h-11 w-11 rounded-2xl bg-white/95 backdrop-blur-md text-indigo-600 flex items-center justify-center shadow-lg border border-white/40">
                    <span class="material-symbols-rounded text-2xl font-bold">school</span>
                </div>
                <div>
                    <span class="text-xl font-bold text-white tracking-tight drop-shadow-md">Olive Learnings</span>
                    <span class="block text-xs font-medium text-slate-200 drop-shadow-sm">Empowering Next-Gen Education</span>
                </div>
            </div>

            <!-- Bottom Value Proposition Card Over Image -->
            <div class="relative z-10 max-w-lg">
                <div class="bg-slate-900/40 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-white shadow-2xl">
                    <div class="flex items-center gap-2 mb-2 text-indigo-300 text-xs font-bold uppercase tracking-wider">
                        <span class="material-symbols-rounded text-sm">verified</span>
                        <span>Unified Management Console</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight leading-snug">
                        Seamless learning, assessment, and student progress tracking.
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                        Manage test series, multiple-choice item banks, video courses, and student performance from a single centralized dashboard.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side (Authentication Form) -->
        <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-12 xl:p-16 bg-white min-h-screen lg:min-h-full">
            
            <!-- Mobile Top Header (Visible on small screens) -->
            <div class="flex items-center justify-between lg:hidden mb-8">
                <div class="flex items-center gap-2.5">
                    <div class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs">
                        <span class="material-symbols-rounded text-xl">school</span>
                    </div>
                    <span class="font-bold text-slate-900 text-base">Olive Learnings</span>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 rounded-lg text-slate-600">Admin</span>
            </div>

            <!-- Desktop Spacer -->
            <div class="hidden lg:block"></div>

            <!-- Form Center Wrapper -->
            <div class="w-full max-w-sm mx-auto my-auto py-8">
                
                <!-- Desktop Brand Pill -->
                <div class="hidden lg:inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100/80 text-indigo-700 text-xs font-bold mb-6">
                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-600"></span>
                    <span>Admin Portal</span>
                </div>

                <!-- Header Title -->
                <div class="mb-8">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Sign in to console
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                        Please enter your administrator credentials below
                    </p>
                </div>

                <!-- Error Alert -->
                @if (isset($errors) && $errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3">
                        <span class="material-symbols-rounded text-rose-500 text-lg mt-0.5">error</span>
                        <div class="text-xs font-medium">
                            {{ $errors->first() }}
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Administrator Email
                        </label>
                        <div class="relative">
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                class="w-full h-11 px-3.5 bg-slate-50/60 focus:bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition shadow-2xs"
                                placeholder="name@olivelearnings.com"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700">
                                Password
                            </label>
                            <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                value=""
                                class="w-full h-11 pl-3.5 pr-10 bg-slate-50/60 focus:bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition shadow-2xs"
                                placeholder="••••••••"
                                required
                            >
                            <button 
                                type="button" 
                                id="togglePasswordBtn"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition"
                                title="Show / Hide Password"
                            >
                                <span class="material-symbols-rounded text-xl" id="togglePasswordIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input 
                                id="remember" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 focus:ring-2 transition"
                            >
                            <span class="text-xs text-slate-600 font-medium">Remember me for 30 days</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full h-11 mt-2 rounded-xl font-bold text-sm text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] transition shadow-sm shadow-indigo-200 flex items-center justify-center gap-2"
                    >
                        <span>Sign In to Console</span>
                        <span class="material-symbols-rounded text-lg">arrow_forward</span>
                    </button>
                </form>

                <!-- Security Trust Note -->
                <div class="mt-6 text-center text-xs text-slate-400 flex items-center justify-center gap-1.5 font-medium">
                    <span class="material-symbols-rounded text-slate-400 text-base">lock</span>
                    <span>256-bit encrypted administrator session</span>
                </div>

            </div>

            <!-- Bottom Footer Links -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
                <p>© {{ date('Y') }} Olive Learnings. All rights reserved.</p>
                <div class="flex items-center gap-4 text-slate-500 font-medium">
                    <a href="mailto:support@olivelearnings.com" class="hover:text-slate-800 transition">Support</a>
                    <span>•</span>
                    <a href="#" class="hover:text-slate-800 transition">Privacy</a>
                    <span>•</span>
                    <a href="#" class="hover:text-slate-800 transition">Terms</a>
                </div>
            </div>

        </div>

    </div>

    <!-- Password toggle script -->
    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.textContent = 'visibility_off';
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.textContent = 'visibility';
                }
            });
        }
    </script>
</body>
</html>
