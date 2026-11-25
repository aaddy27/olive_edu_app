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

    <!-- Tailwind CSS with CDN fallback -->
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
                            800: '#3730a3',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: url("/images/login-bg.jpg");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Material Symbols helper */
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 antialiased text-slate-800 relative selection:bg-indigo-500 selection:text-white">
    
    <!-- Ambient Backdrop Light Tint for High-End Contrast -->
    <div class="fixed inset-0 bg-slate-900/15 backdrop-blur-[2px] pointer-events-none"></div>

    <!-- Unified All-in-One Glassmorphic Auth Card -->
    <div class="relative z-10 w-full max-w-[440px] bg-white/90 backdrop-blur-2xl rounded-3xl p-7 sm:p-9 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.22)] border border-white/80 transition-all">
        
        <!-- Integrated Brand Header (Inside the Card) -->
        <div class="flex items-center justify-between pb-5 border-b border-slate-200/70">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/25 flex-shrink-0">
                    <span class="material-symbols-rounded text-2xl">school</span>
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight leading-none block">Olive Learnings</span>
                    <span class="text-xs font-medium text-slate-500 mt-0.5 block">Education Management</span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-600"></span>
                Admin
            </span>
        </div>

        <!-- Greeting & Subtitle -->
        <div class="pt-6 pb-2">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Sign in to console</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">Welcome back! Please enter your credentials to access the administrative portal.</p>
        </div>

        <!-- Error Alert -->
        @if (isset($errors) && $errors->any())
            <div class="my-4 p-3.5 rounded-xl bg-rose-50/90 border border-rose-200 text-rose-800 flex items-start gap-2.5 text-xs font-semibold shadow-xs">
                <span class="material-symbols-rounded text-rose-500 text-lg flex-shrink-0">error</span>
                <span class="mt-0.5">{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Form Elements -->
        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4 pt-3">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Administrator Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-rounded text-lg">mail</span>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        class="w-full h-11 pl-10 pr-3.5 bg-white/80 focus:bg-white border border-slate-300/90 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-600 transition shadow-2xs font-medium"
                        placeholder="admin@olivelearnings.com"
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
                    <a href="mailto:support@olivelearnings.com?subject=Admin%20Password%20Reset%20Request" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                        Forgot password?
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-rounded text-lg">lock</span>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        value=""
                        class="w-full h-11 pl-10 pr-10 bg-white/80 focus:bg-white border border-slate-300/90 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-600 transition shadow-2xs font-medium"
                        placeholder="••••••••"
                        required
                    >
                    <button 
                        type="button" 
                        id="togglePasswordBtn"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition cursor-pointer"
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
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 focus:ring-2 transition cursor-pointer"
                    >
                    <span class="text-xs text-slate-600 font-medium">Remember me for 30 days</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full h-12 mt-2 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 active:scale-[0.99] transition shadow-md shadow-indigo-500/25 flex items-center justify-center gap-2 cursor-pointer"
            >
                <span>Sign In to Console</span>
                <span class="material-symbols-rounded text-lg">arrow_forward</span>
            </button>
        </form>

        <!-- Integrated Security & Copyright Footer (Inside the Card) -->
        <div class="mt-6 pt-5 border-t border-slate-200/60 flex flex-col items-center justify-center gap-2 text-center">
            <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                <span class="material-symbols-rounded text-emerald-600 text-base">verified_user</span>
                <span>256-Bit SSL Encrypted Admin Portal</span>
            </div>
            <div class="text-[11px] text-slate-400 flex items-center gap-3">
                <span>© {{ date('Y') }} Olive Learnings</span>
                <span>•</span>
                <a href="mailto:support@olivelearnings.com" class="hover:text-slate-700 transition">Need Help?</a>
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
