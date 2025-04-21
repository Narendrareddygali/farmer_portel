<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Farmer Scheme Portal'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-10 w-auto" src="https://cdn-icons-png.flaticon.com/512/3069/3069172.png" alt="Logo">
                        <span class="ml-2 text-xl font-bold text-green-600">Krishi Sahay</span>
                    </div>
                </div>
                <div class="hidden md:ml-6 md:flex md:items-center space-x-8">
                    <a href="index.php" class="text-gray-800 hover:text-green-600 px-3 py-2 font-medium">Home</a>
                    <a href="#" class="text-gray-800 hover:text-green-600 px-3 py-2 font-medium">Schemes</a>
                    <a href="#" class="text-gray-800 hover:text-green-600 px-3 py-2 font-medium">About</a>
                    <a href="/farmer-portel/market.html
                    " class="text-gray-800 hover:text-green-600 px-3 py-2 font-medium">Marketplace</a>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <span class="mr-4 text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['farmer_name']); ?></span>
                        <a href="logout.php" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 mr-2">
                            Login
                        </a>
                        <a href="register.php" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>