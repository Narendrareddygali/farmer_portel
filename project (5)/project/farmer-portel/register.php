<?php
require_once 'auth/functions.php';
requireGuest();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $aadhaar = trim($_POST['aadhaar']);
    $mobile = trim($_POST['mobile']);
    $state = $_POST['state'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($name) || empty($aadhaar) || empty($mobile) || empty($state) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!preg_match('/^\d{12}$/', $aadhaar)) {
        $error = 'Aadhaar must be 12 digits';
    } elseif (!preg_match('/^\d{10}$/', $mobile)) {
        $error = 'Mobile must be 10 digits';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        // Attempt registration
        if (registerFarmer($name, $aadhaar, $mobile, $state, $password)) {
            $success = 'Registration successful! Please login.';
            // Clear form
            $name = $aadhaar = $mobile = $state = '';
        } else {
            $error = 'Registration failed. Aadhaar may already be registered.';
        }
    }
}

$page_title = 'Register - Krishi Sahay';
include 'includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white max-w-md w-full p-8 rounded-xl shadow-2xl">
        <div class="text-center mb-8">
            <img src="https://cdn-icons-png.flaticon.com/512/3069/3069172.png" alt="Logo" class="h-16 mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mt-4">Create Your Account</h1>
            <p class="text-gray-600">Join thousands of farmers accessing government schemes</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($name ?? ''); ?>"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            
            <div>
                <label for="aadhaar" class="block text-sm font-medium text-gray-700 mb-1">Aadhaar Number</label>
                <input type="text" id="aadhaar" name="aadhaar" required 
                       value="<?php echo htmlspecialchars($aadhaar ?? ''); ?>"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       pattern="\d{12}" title="12-digit Aadhaar number">
            </div>
            
            <div>
                <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                <input type="tel" id="mobile" name="mobile" required 
                       value="<?php echo htmlspecialchars($mobile ?? ''); ?>"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       pattern="\d{10}" title="10-digit mobile number">
            </div>
            
            <div>
                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                <select id="state" name="state" required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Select your state</option>
                    <option value="Andhra Pradesh" <?php echo ($state ?? '') === 'Andhra Pradesh' ? 'selected' : ''; ?>>Andhra Pradesh</option>
                    <option value="Bihar" <?php echo ($state ?? '') === 'Bihar' ? 'selected' : ''; ?>>Bihar</option>
                    <option value="Gujarat" <?php echo ($state ?? '') === 'Gujarat' ? 'selected' : ''; ?>>Gujarat</option>
                    <option value="Haryana" <?php echo ($state ?? '') === 'Haryana' ? 'selected' : ''; ?>>Haryana</option>
                    <option value="Karnataka" <?php echo ($state ?? '') === 'Karnataka' ? 'selected' : ''; ?>>Karnataka</option>
                    <option value="Kerala" <?php echo ($state ?? '') === 'Kerala' ? 'selected' : ''; ?>>Kerala</option>
                    <option value="Madhya Pradesh" <?php echo ($state ?? '') === 'Madhya Pradesh' ? 'selected' : ''; ?>>Madhya Pradesh</option>
                    <option value="Maharashtra" <?php echo ($state ?? '') === 'Maharashtra' ? 'selected' : ''; ?>>Maharashtra</option>
                    <option value="Odisha" <?php echo ($state ?? '') === 'Odisha' ? 'selected' : ''; ?>>Odisha</option>
                    <option value="Punjab" <?php echo ($state ?? '') === 'Punjab' ? 'selected' : ''; ?>>Punjab</option>
                    <option value="Rajasthan" <?php echo ($state ?? '') === 'Rajasthan' ? 'selected' : ''; ?>>Rajasthan</option>
                    <option value="Tamil Nadu" <?php echo ($state ?? '') === 'Tamil Nadu' ? 'selected' : ''; ?>>Tamil Nadu</option>
                    <option value="Telangana" <?php echo ($state ?? '') === 'Telangana' ? 'selected' : ''; ?>>Telangana</option>
                    <option value="Uttar Pradesh" <?php echo ($state ?? '') === 'Uttar Pradesh' ? 'selected' : ''; ?>>Uttar Pradesh</option>
                    <option value="West Bengal" <?php echo ($state ?? '') === 'West Bengal' ? 'selected' : ''; ?>>West Bengal</option>
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            
            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required 
                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-700">
                    I agree to the <a href="#" class="text-green-600">Terms of Service</a> and <a href="#" class="text-green-600">Privacy Policy</a>
                </label>
            </div>
            
            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                    Register
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">Already have an account? 
                <a href="login.php" class="text-green-600 font-semibold hover:text-green-800">Login here</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>