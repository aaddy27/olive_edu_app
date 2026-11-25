<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') - EduApp Enterprise</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Roboto+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased bg-slate-50 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-full flex flex-col lg:flex-row">
        
        <!-- Mobile Sidebar Backdrop -->
        <div id="mobileBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

        <!-- Sidebar Navigation Drawer -->
        <aside id="sidebar" class="fixed lg:sticky top-0 h-screen w-72 bg-white border-r border-slate-200/80 z-50 flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-lg lg:shadow-none">
            
            <!-- Sidebar Header & Brand -->
            <div>
                <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-xs">
                            <span class="material-symbols-rounded text-2xl">school</span>
                        </div>
                        <div>
                            <span class="font-bold text-base text-slate-900 tracking-tight leading-none block">Olive Learnings</span>
                            <span class="text-xs text-slate-400 font-medium">Admin Portal</span>
                        </div>
                    </a>

                    <!-- Close mobile drawer button -->
                    <button id="closeMobileDrawer" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 lg:hidden">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-6 overflow-y-auto max-h-[calc(100vh-10rem)]">
                    
                    <!-- Core Section -->
                    <div>
                        <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Overview</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}">dashboard</span>
                                <span>Dashboard</span>
                            </a>
                        </div>
                    </div>

                    <!-- Academics Section -->
                    <div>
                        <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Academics</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.exam-boards.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.exam-boards.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.exam-boards.*') ? 'text-white' : 'text-slate-400' }}">account_balance</span>
                                <span>Exam Boards</span>
                            </a>

                            <a href="{{ route('admin.exams.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.exams.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.exams.*') ? 'text-white' : 'text-slate-400' }}">assignment</span>
                                <span>Exams</span>
                            </a>

                            <a href="{{ route('admin.subjects.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.subjects.*') ? 'text-white' : 'text-slate-400' }}">auto_stories</span>
                                <span>Subjects</span>
                            </a>

                            <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.courses.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.courses.*') ? 'text-white' : 'text-slate-400' }}">layers</span>
                                <span>Courses</span>
                            </a>
                        </div>
                    </div>

                    <!-- Content & Assessment Section -->
                    <div>
                        <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Content & Exams</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.videos.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.videos.*') ? 'text-white' : 'text-slate-400' }}">smart_display</span>
                                <span>Videos</span>
                            </a>

                            <a href="{{ route('admin.tests.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.tests.*') || request()->routeIs('admin.tests.questions.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.tests.*') || request()->routeIs('admin.tests.questions.*') ? 'text-white' : 'text-slate-400' }}">quiz</span>
                                <span>Tests & Questions</span>
                            </a>
                        </div>
                    </div>

                    <!-- Operations Section -->
                    <div>
                        <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Operations</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400' }}">group</span>
                                <span>Users</span>
                            </a>

                            <a href="{{ route('admin.purchases.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.purchases.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="material-symbols-rounded {{ request()->routeIs('admin.purchases.*') ? 'text-white' : 'text-slate-400' }}">receipt_long</span>
                                <span>Purchases</span>
                            </a>
                        </div>
                    </div>

                </nav>
            </div>

            <!-- Sidebar Footer / Admin Profile Pill -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-between p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200/80">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate leading-snug">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@eduapp.com' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" title="Sign Out" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <span class="material-symbols-rounded">logout</span>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Enterprise Top App Bar -->
            <header class="sticky top-0 z-30 h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-6 flex items-center justify-between shadow-xs">
                
                <div class="flex items-center gap-4">
                    <!-- Hamburger button for mobile -->
                    <button id="openMobileDrawer" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 lg:hidden">
                        <span class="material-symbols-rounded text-2xl">menu</span>
                    </button>

                    <div>
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight leading-none">@yield('page-title', 'Dashboard')</h1>
                        <nav class="flex items-center gap-1.5 text-xs text-slate-500 font-medium mt-1">
                            <span>Admin Portal</span>
                            <span class="text-slate-300">/</span>
                            <span class="text-indigo-600 font-semibold">@yield('title', 'Overview')</span>
                        </nav>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-4">
                    
                    <!-- Today Date -->
                    <div class="hidden sm:flex items-center text-xs font-medium text-slate-500">
                        {{ now()->format('l, d M Y') }}
                    </div>

                    <!-- Notifications Button -->
                    <button class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" title="Notifications">
                        <span class="material-symbols-rounded text-2xl">notifications</span>
                    </button>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                    <!-- User Monogram & Name -->
                    <div class="flex items-center gap-2.5">
                        <div class="h-9 w-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-xs font-semibold text-slate-800 leading-none">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Administrator</p>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Page Body -->
            <main class="flex-1 p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
                
                <!-- Flash Alerts / Material Snackbars -->
                @if(session('success'))
                    <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 shadow-2xs animate-fade-in">
                        <div class="h-8 w-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-rounded text-lg">check</span>
                        </div>
                        <div class="flex-1 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="p-1 text-emerald-600 hover:text-emerald-900 rounded-lg">
                            <span class="material-symbols-rounded text-base">close</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 shadow-2xs animate-fade-in">
                        <div class="h-8 w-8 rounded-lg bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-rounded text-lg">priority_high</span>
                        </div>
                        <div class="flex-1 text-sm font-medium">
                            {{ session('error') }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="p-1 text-rose-600 hover:text-rose-900 rounded-lg">
                            <span class="material-symbols-rounded text-base">close</span>
                        </button>
                    </div>
                @endif

                @yield('content')

            </main>

            <!-- Footer -->
            <footer class="mt-auto border-t border-slate-200/80 bg-white py-4 px-6 text-center text-xs text-slate-400">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 max-w-7xl mx-auto">
                    <p>© {{ date('Y') }} Olive Learnings. All rights reserved.</p>
                    <div class="flex items-center gap-4 text-slate-500">
                        <a href="#" class="hover:text-slate-800 transition">Privacy</a>
                        <span>•</span>
                        <a href="#" class="hover:text-slate-800 transition">Terms</a>
                        <span>•</span>
                        <a href="#" class="hover:text-slate-800 transition">Support</a>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!-- Universal Client-side Live Table Filter & Mobile Drawer Script -->
    <script>
        // Mobile Drawer Toggle
        const sidebar = document.getElementById('sidebar');
        const mobileBackdrop = document.getElementById('mobileBackdrop');
        const openDrawerBtn = document.getElementById('openMobileDrawer');
        const closeDrawerBtn = document.getElementById('closeMobileDrawer');

        function toggleDrawer(open) {
            if (open) {
                sidebar.classList.remove('-translate-x-full');
                mobileBackdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                mobileBackdrop.classList.add('hidden');
            }
        }

        if (openDrawerBtn) openDrawerBtn.addEventListener('click', () => toggleDrawer(true));
        if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', () => toggleDrawer(false));
        if (mobileBackdrop) mobileBackdrop.addEventListener('click', () => toggleDrawer(false));

        // Universal Real-time Data Table Search Filter
        window.filterDataTable = function(inputId, tableId, countElementId) {
            const input = document.getElementById(inputId);
            const table = document.getElementById(tableId);
            if (!input || !table) return;

            const filter = input.value.toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr[data-filterable="true"], tbody tr:not(.no-filter)');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle empty state row if exists
            const emptyStateRow = document.getElementById(tableId + '-empty');
            if (emptyStateRow) {
                emptyStateRow.style.display = (visibleCount === 0 && filter !== '') ? '' : 'none';
            }

            if (countElementId) {
                const countEl = document.getElementById(countElementId);
                if (countEl) countEl.textContent = visibleCount;
            }
        };
    </script>

    @stack('scripts')
</body>
</html>
