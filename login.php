<?php
require_once 'auth/functions.php';
requireGuest();

$error = '';
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($identifier) || empty($password)) {
        $error = 'Please enter your email or mobile number';
    } else {
        if (loginFarmer($identifier, $password)) {
            header("Location: index.php");
            exit();
        } else {
            $error = 'Invalid email/mobile or password';
        }
    }
}

$page_title = 'Login - Krishi Sahay';
include 'includes/header.php';
?>

<style>
/* Floating label design matching adaptive theme variables */
.floating-group {
    position: relative;
}
.floating-input {
    width: 100%;
    border: 1px solid var(--input-border);
    border-radius: 1.25rem; /* rounded-2xl */
    background: var(--input-bg);
    backdrop-filter: blur(4px);
    transition: all 0.25s ease-in-out;
    padding-top: 1.35rem;
    padding-bottom: 0.45rem;
    padding-left: 2.75rem; /* Space for the icon */
    padding-right: 1.25rem;
    font-size: 1rem;
    color: var(--input-text);
    outline: none;
}
.floating-input:focus {
    background: var(--input-focus-bg);
    border-color: var(--label-active-color);
    box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.15);
}
.floating-label {
    position: absolute;
    left: 2.75rem; /* Align with input text padding */
    top: 0.95rem;
    color: var(--label-color);
    font-size: 0.95rem;
    transition: all 0.25s ease-in-out;
    pointer-events: none;
    transform-origin: 0 0;
}
.floating-input:focus ~ .floating-label,
.floating-input:not(:placeholder-shown) ~ .floating-label {
    transform: translateY(-0.75rem) scale(0.78);
    color: var(--label-active-color);
    font-weight: 600;
}
.floating-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--label-color);
    transition: color 0.25s;
    pointer-events: none;
}
.floating-input:focus ~ .floating-icon {
    color: var(--label-active-color);
}
</style>

<div class="flex-grow flex items-center justify-center p-4">
    <!-- Centered Glassmorphic Form Card -->
    <div class="bg-[var(--card-bg)] backdrop-blur-md max-w-md w-full rounded-[2.25rem] shadow-[0_25px_60px_-15px_rgba(0,0,0,0.15)] border border-[var(--card-border)] overflow-hidden my-6">
        
        <!-- Premium Accent Bar -->
        <div class="h-2 w-full bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>
        
        <div class="p-8 sm:p-10">
            <!-- Branding Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 bg-white/5 border border-[var(--nav-border)] rounded-2xl mb-4 shadow-sm">
                    <img class="h-10 w-auto" src="https://cdn-icons-png.flaticon.com/512/3069/3069172.png" alt="Logo">
                </div>
                <h1 class="text-3xl font-extrabold text-[var(--card-text-title)] tracking-tight">Krishi Sahay</h1>
                <p class="text-[var(--card-text-body)] mt-2 text-sm font-medium">Empowering Indian Farmers & Communities</p>
            </div>
            
            <!-- Styled Slide Switcher -->
            <div class="relative flex bg-[var(--switcher-bg)] p-1 rounded-2xl mb-8 border border-[var(--nav-border)]">
                <div class="absolute top-1 bottom-1 left-1 w-[calc(50%-4px)] bg-[var(--switcher-active-bg)] border border-[var(--nav-border)] rounded-xl shadow-sm transition-transform duration-300 transform translate-x-0"></div>
                <a href="login.php" class="relative z-10 w-1/2 py-2 text-center text-sm font-bold text-[var(--switcher-text-active)] transition duration-150">Login</a>
                <a href="register.php" class="relative z-10 w-1/2 py-2 text-center text-sm font-bold text-[var(--switcher-text-inactive)] hover:opacity-95 transition duration-150">Register</a>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-950/40 border border-red-500/20 text-red-200 px-4 py-3 rounded-2xl mb-6 shadow-sm flex items-center space-x-2.5">
                    <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" id="loginForm" autocomplete="on">
                <!-- Email or Mobile Input -->
                <div class="floating-group">
                    <input type="text" id="identifier" name="identifier" required placeholder=" " 
                           value="<?php echo htmlspecialchars($identifier ?? ''); ?>"
                           class="floating-input"
                           autocomplete="username">
                    <!-- Icon -->
                    <span class="floating-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <label for="identifier" class="floating-label">Email or Mobile Number</label>
                </div>
                
                <!-- Password Input -->
                <div class="floating-group">
                    <input type="password" id="password" name="password" required placeholder=" " 
                           class="floating-input pr-12"
                           autocomplete="current-password">
                    <!-- Icon -->
                    <span class="floating-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <label for="password" class="floating-label">Password</label>
                    <!-- Show/Hide Button -->
                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-500 transition-colors p-1" tabindex="-1">
                        <svg class="h-5 w-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="h-5 w-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858-.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4.5 w-4.5 text-emerald-600 focus:ring-emerald-500 border-[var(--input-border)] bg-[var(--input-bg)] rounded cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-[var(--card-text-body)] font-medium cursor-pointer select-none">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-semibold text-emerald-500 hover:text-emerald-400 hover:underline transition">Forgot?</a>
                </div>
                
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-2xl transition duration-150 transform hover:-translate-y-[1px] active:translate-y-0 shadow-lg shadow-emerald-600/20 flex items-center justify-center space-x-2">
                        <span>Sign In</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-[var(--card-text-body)]">
                    New to Krishi Sahay? 
                    <a href="register.php" class="text-emerald-500 font-bold hover:text-emerald-400 transition">Create account</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Password toggle helper
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const openIcon = button.querySelector('.eye-open');
    const closedIcon = button.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        openIcon.classList.add('hidden');
        closedIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        openIcon.classList.remove('hidden');
        closedIcon.classList.add('hidden');
    }
}
</script>

<?php include 'includes/footer.php'; ?>