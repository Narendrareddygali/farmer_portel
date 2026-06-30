<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Farmer Scheme Portal'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    
    <script>
        // Inline script to prevent theme flashing
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.add('light-theme');
            }
        })();
    </script>
    
    <style>
        :root {
            /* Dark Theme variables */
            --bg-color: #080d09;
            --text-color: #f3f4f6;
            --nav-bg: rgba(0, 0, 0, 0.35);
            --nav-border: rgba(255, 255, 255, 0.1);
            
            --orb1-bg: #16301d;
            --orb2-bg: #c2410c;
            --orb3-bg: #b45309;
            --orb-opacity: 0.55;
            
            --card-bg: rgba(0, 0, 0, 0.35);
            --card-border: rgba(255, 255, 255, 0.1);
            --card-text-title: #ffffff;
            --card-text-body: #d1d5db;
            
            --input-bg: rgba(255, 255, 255, 0.05);
            --input-border: rgba(255, 255, 255, 0.1);
            --input-text: #ffffff;
            --input-focus-bg: rgba(255, 255, 255, 0.08);
            --label-color: #9ca3af;
            --label-active-color: #34d399; /* mint */
            
            --switcher-bg: rgba(255, 255, 255, 0.05);
            --switcher-active-bg: rgba(255, 255, 255, 0.1);
            --switcher-text-active: #ffffff;
            --switcher-text-inactive: #9ca3af;
        }
        
        .light-theme {
            /* Light Theme variables */
            --bg-color: #f3f6f3;
            --text-color: #1f2937;
            --nav-bg: rgba(255, 255, 255, 0.45);
            --nav-border: rgba(0, 0, 0, 0.06);
            
            --orb1-bg: #d2e0d3;
            --orb2-bg: #eddcb4;
            --orb3-bg: #c0ccc0;
            --orb-opacity: 0.65;
            
            --card-bg: rgba(255, 255, 255, 0.75);
            --card-border: rgba(255, 255, 255, 0.6);
            --card-text-title: #1f2937;
            --card-text-body: #4b5563;
            
            --input-bg: rgba(255, 255, 255, 0.8);
            --input-border: #cbd5e1; /* slate-300 */
            --input-text: #1f2937;
            --input-focus-bg: #ffffff;
            --label-color: #9ca3af;
            --label-active-color: #059669; /* emerald-600 */
            
            --orb1-bg: #d4e5d6;
            --orb2-bg: #faedd2;
            --orb3-bg: #dae6da;
            
            --switcher-bg: rgba(0, 0, 0, 0.05);
            --switcher-active-bg: #ffffff;
            --switcher-text-active: #047857; /* emerald-700 */
            --switcher-text-inactive: #6b7280; /* gray-500 */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
            transition: background 0.4s ease, color 0.4s ease;
        }
        
        /* Smooth theme transitions */
        nav, div, label, input, select, button, svg {
            transition: background-color 0.4s ease, border-color 0.4s ease, color 0.4s ease, opacity 0.4s ease, transform 0.25s ease;
        }
        
        /* Drifting ambient orbs keyframes */
        @keyframes drift {
            0%, 100% { transform: translateY(0px) translateX(0px) scale(1); }
            50% { transform: translateY(45px) translateX(30px) scale(1.15); }
        }
        @keyframes drift-reverse {
            0%, 100% { transform: translateY(0px) translateX(0px) scale(1); }
            50% { transform: translateY(-55px) translateX(-35px) scale(0.85); }
        }
        @keyframes drift-slow {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(35px) translateX(-20px); }
        }
        .animate-drift { animation: drift 22s infinite ease-in-out; }
        .animate-drift-reverse { animation: drift-reverse 26s infinite ease-in-out; }
        .animate-drift-slow { animation: drift-slow 30s infinite ease-in-out; }

        /* Icon visibility toggling */
        .light-icon {
            display: none !important;
        }
        .dark-icon {
            display: block !important;
        }
        .light-theme .light-icon {
            display: block !important;
        }
        .light-theme .dark-icon {
            display: none !important;
        }

        /* Animated active navigation link (Frosted Glass with Moving Shine) */
        .nav-link-active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(245, 158, 11, 0.08) 100%);
            border: 1.5px solid rgba(16, 185, 129, 0.25);
            color: #34d399 !important; /* emerald-400 */
            padding: 0.5rem 1.2rem !important;
            border-radius: 0.75rem; /* rounded-xl */
            font-weight: 600;
            box-shadow: 0 4px 12px -3px rgba(16, 185, 129, 0.15);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
        }
        
        .light-theme .nav-link-active {
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
            border: 1.5px solid rgba(5, 150, 105, 0.25);
            color: #047857 !important; /* emerald-700 */
            box-shadow: 0 4px 12px -3px rgba(5, 150, 105, 0.1);
        }
        
        .nav-link-active::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 30%;
            height: 200%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: rotate(30deg);
            animation: shine 4.5s infinite linear;
        }
        
        @keyframes shine {
            0% { left: -60%; }
            100% { left: 160%; }
        }

        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="h-full">

    <!-- Global Live Wallpaper Layer -->
    <div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none" aria-hidden="true">
        <!-- Live wallpaper blobs -->
        <div class="absolute w-[450px] h-[450px] rounded-full bg-[var(--orb1-bg)] blur-[100px] -top-20 -left-20 opacity-[var(--orb-opacity)] animate-drift"></div>
        <div class="absolute w-[550px] h-[550px] rounded-full bg-[var(--orb2-bg)] blur-[130px] bottom-10 right-10 opacity-[var(--orb-opacity)] animate-drift-reverse"></div>
        <div class="absolute w-[350px] h-[350px] rounded-full bg-[var(--orb3-bg)] blur-[100px] top-1/2 left-1/3 opacity-[var(--orb-opacity)] animate-drift-slow"></div>
    </div>

    <!-- Floating Sticky Glassmorphic Navbar -->
    <nav class="max-w-7xl w-full mx-auto px-4 pt-4 sticky top-0 z-50">
        <div class="bg-[var(--nav-bg)] backdrop-blur-md border border-[var(--nav-border)] rounded-2xl shadow-xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php#home" class="flex-shrink-0 flex items-center hover:opacity-90 transition">
                        <img class="h-10 w-auto bg-white/10 rounded-xl p-1 border border-white/10 shadow-sm" src="https://cdn-icons-png.flaticon.com/512/3069/3069172.png" alt="Logo">
                        <span class="ml-2.5 text-xl font-bold tracking-tight text-emerald-500 light-theme:text-emerald-600">Krishi Sahay</span>
                    </a>
                </div>
                
                <div class="hidden md:ml-6 md:flex md:items-center space-x-8">
                    <a href="index.php#home" id="nav-home" class="nav-link px-3 py-2 font-medium transition duration-150 text-[var(--text-color)] hover:text-emerald-500">Home</a>
                    <a href="index.php#schemes" id="nav-schemes" class="nav-link px-3 py-2 font-medium transition duration-150 text-[var(--text-color)] hover:text-emerald-500">Schemes</a>
                    <a href="index.php#marketplace" id="nav-marketplace" class="nav-link px-3 py-2 font-medium transition duration-150 text-[var(--text-color)] hover:text-emerald-500">Marketplace</a>
                    <a href="index.php#about-us" id="nav-about" class="nav-link px-3 py-2 font-medium transition duration-150 text-[var(--text-color)] hover:text-emerald-500">About</a>
                </div>
                
                <div class="flex items-center">
                    <!-- Farmer Nature Theme Toggle Button -->
                    <button id="themeToggleBtn" onclick="toggleTheme()" class="p-2.5 rounded-xl border border-[var(--nav-border)] bg-[var(--nav-bg)] hover:opacity-80 transition-all duration-150 mr-3 flex items-center justify-center shadow-sm" title="Toggle Nature Theme">
                        <!-- Light mode: Sun over Sprout -->
                        <svg class="h-5 w-5 text-amber-500 light-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="3" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 2v2M8.5 3.5l1.5 1.5M4 7h2M8.5 10.5l1.5-1.5M15.5 3.5l-1.5 1.5M20 7h-2M15.5 10.5l-1.5-1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 21v-7M12 14c-2-1.5-4-1-4-1s.5-3 4-3m0 4c2-1.5 4-1 4-1s-.5-3-4-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <!-- Dark mode: Moon over Sprout -->
                        <svg class="h-5 w-5 text-emerald-300 dark-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 17v-4c-1-1-3-1-3-1s0-2 3-2m0 3c1-1 3-1 3-1s0-2-3-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <span class="mr-4 text-[var(--text-color)] text-sm hidden sm:inline font-medium">Welcome, <?php echo htmlspecialchars($_SESSION['farmer_name']); ?></span>
                        <a href="logout.php" class="px-4 py-2 border border-red-500/20 text-sm font-semibold rounded-xl text-red-200 bg-red-950/40 hover:bg-red-900/60 transition duration-150">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 border border-emerald-500/20 text-sm font-semibold rounded-xl text-emerald-500 bg-emerald-950/20 hover:bg-emerald-900/30 mr-2 transition duration-150">
                            Login
                        </a>
                        <a href="register.php" class="px-4 py-2 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition duration-150">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>
        function toggleTheme() {
            const isLight = document.documentElement.classList.toggle('light-theme');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        }
    </script>