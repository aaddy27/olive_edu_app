<!DOCTYPE html>
<html lang="en" class="h-full">
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
            background-image: url("/images/login-bg.jpg");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between antialiased text-slate-800 bg-slate-900/10 selection:bg-indigo-500 selection:text-white">
    
    <!-- Top Floating Header -->
    <header class="pt-6 px-4 sm:px-8 max-w-7xl w-full mx-auto">
        <div class="bg-white/85 backdrop-blur-md rounded-2xl border border-white/70 shadow-xs px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs">
                    <span class="material-symbols-rounded text-2xl">school</span>
                </div>
                <div>
                    <span class="font-bold text-base sm:text-lg text-slate-900 tracking-tight leading-none block">Olive Learnings</span>
                    <span class="text-[11px] text-slate-500 font-medium mt-0.5 block">Admin Management Console</span>
                </div>
            </div>
            <a href="mailto:support@olivelearnings.com" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100/70">
                <span class="material-symbols-rounded text-base text-slate-500">support</span>
                <span class="hidden sm:inline">Need Help?</span>
            </a>
        </div>
    </header>

    <!-- Main Auth Card Container -->
    <main class="flex-1 flex items-center justify-center px-4 py-8 sm:px-6">
        <div class="w-full max-w-[420px]">
            
            <!-- Frosted Glass Card Container -->
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-7 sm:p-9 shadow-[0_20px_50px_rgba(15,23,42,0.12)] border border-white/80">
                
                <!-- Heading -->
                <div class="mb-6">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Sign in</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Please enter your admin credentials to continue</p>
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

                <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Email address
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
                    <div class="flex items-center pt-1">
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
                        <span>Sign In to Admin</span>
                        <span class="material-symbols-rounded text-lg">arrow_forward</span>
                    </button>
                </form>

            </div>

            <!-- Security Trust Note -->
            <div class="mt-4 text-center text-xs text-slate-600 flex items-center justify-center gap-1.5 font-medium drop-shadow-xs">
                <span class="material-symbols-rounded text-slate-600 text-base">lock</span>
                <span>Encrypted & secure session</span>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="pb-6 px-4 max-w-7xl w-full mx-auto">
        <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/70 shadow-2xs px-6 py-3 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <p>© {{ date('Y') }} Olive Learnings. All rights reserved.</p>
            <div class="flex items-center gap-4 text-slate-600 font-medium">
                <a href="#" class="hover:text-indigo-600 transition">Privacy</a>
                <span>•</span>
                <a href="#" class="hover:text-indigo-600 transition">Terms</a>
                <span>•</span>
                <a href="#" class="hover:text-indigo-600 transition">Support</a>
            </div>
        </div>
    </footer>

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
