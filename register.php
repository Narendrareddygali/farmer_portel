<?php
require_once 'auth/functions.php';
requireGuest();

$error = '';
$success = '';

$name = '';
$email = '';
$mobile = '';
$state = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $state = $_POST['state'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($mobile) || empty($state) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (!preg_match('/^\d{10}$/', $mobile)) {
        $error = 'Mobile must be a 10-digit number';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        // Attempt registration
        if (registerFarmer($name, $email, $mobile, $state, $password)) {
            $success = 'Registration successful! Please login.';
            // Clear form
            $name = $email = $mobile = $state = '';
        } else {
            $error = 'Registration failed. Email or Mobile may already be registered.';
        }
    }
}

$page_title = 'Register - Krishi Sahay';
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
                <h1 class="text-3xl font-extrabold text-[var(--card-text-title)] tracking-tight">Create Account</h1>
                <p class="text-[var(--card-text-body)] mt-2 text-sm font-medium">Join Krishi Sahay to access crop schemes</p>
            </div>
            
            <!-- Styled Slide Switcher -->
            <div class="relative flex bg-[var(--switcher-bg)] p-1 rounded-2xl mb-8 border border-[var(--nav-border)]">
                <div class="absolute top-1 bottom-1 left-1 w-[calc(50%-4px)] bg-[var(--switcher-active-bg)] border border-[var(--nav-border)] rounded-xl shadow-sm transition-transform duration-300 transform translate-x-full"></div>
                <a href="login.php" class="relative z-10 w-1/2 py-2 text-center text-sm font-bold text-[var(--switcher-text-inactive)] hover:opacity-95 transition duration-150">Login</a>
                <a href="register.php" class="relative z-10 w-1/2 py-2 text-center text-sm font-bold text-[var(--switcher-text-active)] transition duration-150">Register</a>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-950/40 border border-red-500/20 text-red-200 px-4 py-3 rounded-2xl mb-6 shadow-sm flex items-center space-x-2.5">
                    <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-emerald-950/40 border border-emerald-500/20 text-emerald-200 px-4 py-3 rounded-2xl mb-6 shadow-sm flex items-center space-x-2.5">
                    <svg class="h-5 w-5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5" id="registerForm" autocomplete="on">
                <!-- Name Input -->
                <div class="floating-group">
                    <input type="text" id="name" name="name" required placeholder=" " 
                           value="<?php echo htmlspecialchars($name ?? ''); ?>"
                           class="floating-input">
                    <span class="floating-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <label for="name" class="floating-label">Full Name</label>
                </div>
                
                <!-- Email Input -->
                <div class="floating-group">
                    <input type="email" id="email" name="email" required placeholder=" " 
                           value="<?php echo htmlspecialchars($email ?? ''); ?>"
                           class="floating-input">
                    <span class="floating-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <label for="email" class="floating-label">Email Address</label>
                </div>

                <!-- State Selector -->
                <div class="relative">
                    <select id="state" name="state" required 
                            class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 appearance-none bg-no-repeat pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%239CA3AF%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-position: right 1rem center; background-size: 1.25rem;">
                        <option value="" disabled <?php echo empty($state) ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--label-color);">Select your state</option>
                        <option value="Andhra Pradesh" <?php echo ($state ?? '') === 'Andhra Pradesh' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Andhra Pradesh</option>
                        <option value="Bihar" <?php echo ($state ?? '') === 'Bihar' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Bihar</option>
                        <option value="Gujarat" <?php echo ($state ?? '') === 'Gujarat' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Gujarat</option>
                        <option value="Haryana" <?php echo ($state ?? '') === 'Haryana' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Haryana</option>
                        <option value="Karnataka" <?php echo ($state ?? '') === 'Karnataka' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Karnataka</option>
                        <option value="Kerala" <?php echo ($state ?? '') === 'Kerala' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Kerala</option>
                        <option value="Madhya Pradesh" <?php echo ($state ?? '') === 'Madhya Pradesh' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Madhya Pradesh</option>
                        <option value="Maharashtra" <?php echo ($state ?? '') === 'Maharashtra' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Maharashtra</option>
                        <option value="Odisha" <?php echo ($state ?? '') === 'Odisha' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Odisha</option>
                        <option value="Punjab" <?php echo ($state ?? '') === 'Punjab' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Punjab</option>
                        <option value="Rajasthan" <?php echo ($state ?? '') === 'Rajasthan' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Rajasthan</option>
                        <option value="Tamil Nadu" <?php echo ($state ?? '') === 'Tamil Nadu' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Tamil Nadu</option>
                        <option value="Telangana" <?php echo ($state ?? '') === 'Telangana' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Telangana</option>
                        <option value="Uttar Pradesh" <?php echo ($state ?? '') === 'Uttar Pradesh' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">Uttar Pradesh</option>
                        <option value="West Bengal" <?php echo ($state ?? '') === 'West Bengal' ? 'selected' : ''; ?> style="background-color: var(--bg-color); color: var(--input-text);">West Bengal</option>
                    </select>
                </div>
                
                <!-- Mobile Input -->
                <div class="floating-group">
                    <input type="tel" id="mobile" name="mobile" required placeholder=" " 
                           value="<?php echo htmlspecialchars($mobile ?? ''); ?>"
                           pattern="\d{10}" title="10-digit mobile number"
                           class="floating-input">
                    <span class="floating-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </span>
                    <label for="mobile" class="floating-label">Mobile Number</label>
                </div>
                
                <!-- Password Input -->
                <div class="floating-group">
                    <input type="password" id="password" name="password" required placeholder=" " 
                           class="floating-input pr-10"
                           autocomplete="new-password">
                    <span class="floating-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <label for="password" class="floating-label">Password</label>
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

                <!-- Confirm Password Input -->
                <div class="floating-group">
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder=" " 
                           class="floating-input pr-10"
                           autocomplete="new-password">
                    <span class="floating-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <label for="confirm_password" class="floating-label">Confirm Password</label>
                    <button type="button" onclick="togglePassword('confirm_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-500 transition-colors p-1" tabindex="-1">
                        <svg class="h-5 w-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="h-5 w-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858-.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                
                <!-- Passwords match helper -->
                <div id="password-match-helper" class="text-xs font-semibold hidden"></div>
                
                <!-- Terms & Conditions checkbox -->
                <div class="flex items-start mt-2">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required 
                               class="h-4.5 w-4.5 text-emerald-500 focus:ring-emerald-500 border-[var(--input-border)] bg-[var(--input-bg)] rounded cursor-pointer">
                    </div>
                    <label for="terms" class="ml-2 block text-xs text-[var(--card-text-body)] cursor-pointer select-none">
                        I agree to the <a href="#" class="text-emerald-400 font-bold hover:underline">Terms of Service</a> and <a href="#" class="text-emerald-400 font-bold hover:underline">Privacy Policy</a>
                    </label>
                </div>
                
                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit" id="registerBtn"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-2xl transition duration-150 transform hover:-translate-y-[1px] active:translate-y-0 shadow-lg shadow-emerald-600/15 flex items-center justify-center space-x-2">
                        <span>Register Account</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-[var(--card-text-body)]">
                    Already have an account? 
                    <a href="login.php" class="text-emerald-500 font-bold hover:text-emerald-400 transition">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Mobile input restriction (only numeric up to 10 digits)
const mobileInput = document.getElementById('mobile');
if (mobileInput) {
    mobileInput.addEventListener('input', function (e) {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length > 10) {
            val = val.substring(0, 10);
        }
        e.target.value = val;
    });
    mobileInput.addEventListener('keydown', restrictNonNumeric);
}

function restrictNonNumeric(e) {
    if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
        (e.keyCode === 65 && e.ctrlKey === true) ||
        (e.keyCode === 67 && e.ctrlKey === true) ||
        (e.keyCode === 86 && e.ctrlKey === true) ||
        (e.keyCode === 88 && e.ctrlKey === true) ||
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}

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

// Password matching validation
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm_password');
const matchHelper = document.getElementById('password-match-helper');

function validatePasswords() {
    const p1 = password.value;
    const p2 = confirmPassword.value;
    
    if (p1 && p2) {
        matchHelper.classList.remove('hidden');
        if (p1 === p2) {
            matchHelper.textContent = '✓ Passwords match';
            matchHelper.className = 'text-xs font-semibold text-emerald-500 mt-1';
            confirmPassword.classList.remove('border-red-400');
            confirmPassword.classList.add('border-emerald-400');
        } else {
            matchHelper.textContent = '✗ Passwords do not match';
            matchHelper.className = 'text-xs font-semibold text-red-400 mt-1';
            confirmPassword.classList.remove('border-emerald-400');
            confirmPassword.classList.add('border-red-400');
        }
    } else {
        matchHelper.classList.add('hidden');
        confirmPassword.classList.remove('border-red-400', 'border-emerald-400');
    }
}

if (password && confirmPassword) {
    password.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
}
</script>

<?php include 'includes/footer.php'; ?>