<?php
require_once 'auth/functions.php';
requireGuest();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aadhaar = trim($_POST['aadhaar']);
    $password = $_POST['password'];
    
    if (empty($aadhaar) || empty($password)) {
        $error = 'Please enter both Aadhaar and password';
    } elseif (!preg_match('/^\d{12}$/', $aadhaar)) {
        $error = 'Aadhaar must be 12 digits';
    } else {
        if (loginFarmer($aadhaar, $password)) {
            header("Location: index.php");
            exit();
        } else {
            $error = 'Invalid Aadhaar or password';
        }
    }
}

$page_title = 'Login - Krishi Sahay';
include 'includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white max-w-md w-full p-8 rounded-xl shadow-2xl">
        <div class="text-center mb-8">
            <img src="https://cdn-icons-png.flaticon.com/512/3069/3069172.png" alt="Logo" class="h-16 mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mt-4">Welcome Back</h1>
            <p class="text-gray-600">Login to access your farmer dashboard</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="aadhaar" class="block text-sm font-medium text-gray-700 mb-1">Aadhaar Number</label>
                <input type="text" id="aadhaar" name="aadhaar" required 
                       value="<?php echo htmlspecialchars($aadhaar ?? ''); ?>"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       pattern="\d{12}" title="12-digit Aadhaar number">
            </div>
            
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <a href="#" class="text-sm text-green-600 hover:text-green-800">Forgot password?</a>
                </div>
                <input type="password" id="password" name="password" required 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" 
                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                Login
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">New to Krishi Sahay? 
                <a href="register.php" class="text-green-600 font-semibold hover:text-green-800">Create account</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>