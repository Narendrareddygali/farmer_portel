<?php
require_once 'auth/functions.php';
requireLogin();

// Connect to SQLite database to handle crop listing submissions
$db_path = __DIR__ . '/farmer_portal.db';
try {
    $conn = new PDO("sqlite:" . $db_path);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$success_msg = "";
$error_msg = "";

// Handle Selling Crop Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sell_crop'])) {
    $farmer_id = $_SESSION['farmer_id'] ?? 0;
    $farmer_name = $_SESSION['farmer_name'] ?? 'Unknown Farmer';
    $commodity = htmlspecialchars(trim($_POST['commodity'] ?? ''));
    $variety = htmlspecialchars(trim($_POST['variety'] ?? ''));
    $location = htmlspecialchars(trim($_POST['location'] ?? ''));
    $price = htmlspecialchars(trim($_POST['price'] ?? ''));
    $quantity = htmlspecialchars(trim($_POST['quantity'] ?? ''));
    $contact = htmlspecialchars(trim($_POST['contact'] ?? ''));
    $image_type = htmlspecialchars(trim($_POST['image_type'] ?? 'paddy'));
    
    if (!empty($commodity) && !empty($price) && !empty($contact) && !empty($location)) {
        try {
            $stmt = $conn->prepare("INSERT INTO marketplace_products (farmer_id, farmer_name, commodity, variety, location, price, quantity, contact, image_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$farmer_id, $farmer_name, $commodity, $variety, $location, $price, $quantity, $contact, $image_type]);
            $success_msg = "Crop listing posted successfully!";
        } catch (Exception $e) {
            $error_msg = "Error posting listing: " . $e->getMessage();
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}

$page_title = 'Farmer Dashboard - Krishi Sahay';
include 'includes/header.php';

// Mock agricultural weather data based on registered state
$state_weather = [
    'Andhra Pradesh' => ['temp' => '34°C', 'desc' => 'Mostly Sunny', 'moisture' => '38%', 'rain' => '10%'],
    'Bihar' => ['temp' => '31°C', 'desc' => 'Scattered Clouds', 'moisture' => '44%', 'rain' => '30%'],
    'Gujarat' => ['temp' => '36°C', 'desc' => 'Sunny & Dry', 'moisture' => '32%', 'rain' => '0%'],
    'Haryana' => ['temp' => '37°C', 'desc' => 'Hot & Dry', 'moisture' => '30%', 'rain' => '5%'],
    'Karnataka' => ['temp' => '30°C', 'desc' => 'Pleasant, Clear', 'moisture' => '45%', 'rain' => '15%'],
    'Kerala' => ['temp' => '28°C', 'desc' => 'Light Showers', 'moisture' => '65%', 'rain' => '80%'],
    'Madhya Pradesh' => ['temp' => '35°C', 'desc' => 'Sunny', 'moisture' => '35%', 'rain' => '10%'],
    'Maharashtra' => ['temp' => '32°C', 'desc' => 'Partly Cloudy', 'moisture' => '42%', 'rain' => '25%'],
    'Odisha' => ['temp' => '31°C', 'desc' => 'Humid, Cloudy', 'moisture' => '48%', 'rain' => '40%'],
    'Punjab' => ['temp' => '38°C', 'desc' => 'Warm Harvest Sky', 'moisture' => '28%', 'rain' => '5%'],
    'Rajasthan' => ['temp' => '41°C', 'desc' => 'Hot, Sunny', 'moisture' => '20%', 'rain' => '0%'],
    'Tamil Nadu' => ['temp' => '33°C', 'desc' => 'Clear Sky', 'moisture' => '40%', 'rain' => '10%'],
    'Telangana' => ['temp' => '34°C', 'desc' => 'Mostly Sunny', 'moisture' => '37%', 'rain' => '15%'],
    'Uttar Pradesh' => ['temp' => '36°C', 'desc' => 'Scattered Clouds', 'moisture' => '35%', 'rain' => '20%'],
    'West Bengal' => ['temp' => '31°C', 'desc' => 'Partly Cloudy', 'moisture' => '52%', 'rain' => '35%'],
];

$user_state = $_SESSION['farmer_state'] ?? 'Delhi';
$weather = $state_weather[$user_state] ?? ['temp' => '32°C', 'desc' => 'Mostly Sunny', 'moisture' => '40%', 'rain' => '15%'];

// Fetch all database listed products
try {
    $stmt = $conn->query("SELECT * FROM marketplace_products ORDER BY id DESC");
    $db_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $db_products = [];
}

// Crop visual presets
$crop_presets = [
    'paddy' => [
        'img' => 'https://images.unsplash.com/photo-1536304997881-a372c179924b?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
        'emoji' => '🌾'
    ],
    'wheat' => [
        'img' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
        'emoji' => '🌾'
    ],
    'cotton' => [
        'img' => 'https://images.unsplash.com/photo-1594900711581-229ad1ef29b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
        'emoji' => '🌿'
    ],
    'vegetable' => [
        'img' => 'https://images.unsplash.com/photo-1566385101042-1a010c129fa6?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
        'emoji' => '🥔'
    ],
    'fruit' => [
        'img' => 'https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
        'emoji' => '🍎'
    ],
];
?>

<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success/Error alert feeds -->
        <?php if (!empty($success_msg)): ?>
            <div class="mb-6 p-4 bg-emerald-950/40 border border-emerald-500/30 text-emerald-400 rounded-2xl flex items-center shadow-lg">
                <span class="mr-3 text-lg">✓</span> <?= $success_msg ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_msg)): ?>
            <div class="mb-6 p-4 bg-red-950/40 border border-red-500/30 text-red-400 rounded-2xl flex items-center shadow-lg">
                <span class="mr-3 text-lg">⚠️</span> <?= $error_msg ?>
            </div>
        <?php endif; ?>

        <!-- SECTION 1: HOME (Hero Website Banner, Visual Tips, Weather, News, Quick Actions) -->
        <div id="section-home" class="space-y-8">
            
            <!-- Elegant Website-Style Hero Banner -->
            <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl min-h-[380px] flex items-center bg-cover bg-center" style="background-image: linear-gradient(to right, rgba(8,13,9,0.92) 0%, rgba(8,13,9,0.5) 100%), url('https://images.unsplash.com/photo-1464226184884-fa280b87c399?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                <div class="p-8 sm:p-12 max-w-2xl relative z-10 space-y-5 text-white">
                    <span class="bg-emerald-500 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-lg border border-emerald-400/25">Digital Agriculture Portal</span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                        Empowering Farmers <br><span class="text-emerald-400">Directly & Simply</span>
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base leading-relaxed">
                        Krishi Sahay simplifies access to government agricultural schemes, monitors soil metrics, and allows local farmers to list commodities directly for sale in our live mandi marketplace.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="#schemes" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-emerald-600/20 transition duration-150">
                            Check Scheme Eligibility
                        </a>
                        <a href="#marketplace" class="bg-white/10 hover:bg-white/15 border border-white/20 text-white font-bold py-3 px-6 rounded-2xl transition duration-150">
                            Trade Crops & Products
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Info Panel -->
            <div id="welcome" class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)] scroll-mt-24">
                <h2 class="text-2xl font-bold text-[var(--card-text-title)] mb-5">Verified Farmer Profile</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-[var(--input-bg)] border border-[var(--input-border)] p-5 rounded-2xl">
                        <h3 class="font-bold text-emerald-500 text-sm tracking-wider uppercase mb-1">Registered Email</h3>
                        <p class="text-lg font-medium text-[var(--card-text-title)] overflow-x-auto select-all"><?php echo htmlspecialchars($_SESSION['farmer_email']); ?></p>
                    </div>
                    <div class="bg-[var(--input-bg)] border border-[var(--input-border)] p-5 rounded-2xl">
                        <h3 class="font-bold text-blue-400 text-sm tracking-wider uppercase mb-1">State Area</h3>
                        <p class="text-lg font-medium text-[var(--card-text-title)]"><?php echo htmlspecialchars($_SESSION['farmer_state']); ?></p>
                    </div>
                    <div class="bg-[var(--input-bg)] border border-[var(--input-border)] p-5 rounded-2xl flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-purple-400 text-sm tracking-wider uppercase mb-1">Portal Account</h3>
                            <p class="text-lg font-medium text-[var(--card-text-title)]">Verified Farmer</p>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-sm font-semibold">
                            ✓
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agricultural Image Tips Grid -->
            <div>
                <h2 class="text-2xl font-bold text-[var(--card-text-title)] mb-6">Sustainable Farming Methods</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tip 1 -->
                    <div class="bg-[var(--card-bg)] border border-[var(--card-border)] rounded-[1.75rem] overflow-hidden shadow-lg group hover:-translate-y-1 transition duration-200">
                        <div class="h-44 overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1592417817098-8f3d6eb19675?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Drip Irrigation" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute bottom-3 left-3 bg-emerald-500 text-white font-bold text-xs px-2.5 py-1 rounded-lg">Irrigation</span>
                        </div>
                        <div class="p-5 space-y-2">
                            <h3 class="text-lg font-bold text-[var(--card-text-title)]">Modern Drip Irrigation</h3>
                            <p class="text-xs text-[var(--card-text-body)] leading-relaxed">Save up to 60% water while feeding organic nutrients straight to root zones. Ideal for arid climates.</p>
                        </div>
                    </div>
                    <!-- Tip 2 -->
                    <div class="bg-[var(--card-bg)] border border-[var(--card-border)] rounded-[1.75rem] overflow-hidden shadow-lg group hover:-translate-y-1 transition duration-200">
                        <div class="h-44 overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Soil Health" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute bottom-3 left-3 bg-blue-500 text-white font-bold text-xs px-2.5 py-1 rounded-lg">Nutrients</span>
                        </div>
                        <div class="p-5 space-y-2">
                            <h3 class="text-lg font-bold text-[var(--card-text-title)]">Soil Testing & Fertilizers</h3>
                            <p class="text-xs text-[var(--card-text-body)] leading-relaxed">Periodic NPK testing ensures correct dosage of nitrogen and potash, avoiding crop failures.</p>
                        </div>
                    </div>
                    <!-- Tip 3 -->
                    <div class="bg-[var(--card-bg)] border border-[var(--card-border)] rounded-[1.75rem] overflow-hidden shadow-lg group hover:-translate-y-1 transition duration-200">
                        <div class="h-44 overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Harvest" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute bottom-3 left-3 bg-purple-500 text-white font-bold text-xs px-2.5 py-1 rounded-lg">Machinery</span>
                        </div>
                        <div class="p-5 space-y-2">
                            <h3 class="text-lg font-bold text-[var(--card-text-title)]">Advanced Harvester Tools</h3>
                            <p class="text-xs text-[var(--card-text-body)] leading-relaxed">Utilizing small tractor modifications cuts harvest times in half, boosting post-monsoon crop yields.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Agricultural Widgets Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Weather & Soil Health Widget -->
                <div class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)] flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-[var(--card-text-title)] flex items-center">
                                <span class="mr-2">⛅</span> Weather & Soil Health
                            </h2>
                            <span class="bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-xs px-3 py-1 rounded-full font-semibold uppercase tracking-wider">
                                <?= htmlspecialchars($user_state) ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center space-x-6 mb-6">
                            <div class="text-5xl font-extrabold text-[var(--card-text-title)]"><?= $weather['temp'] ?></div>
                            <div>
                                <div class="text-lg font-bold text-[var(--card-text-title)]"><?= $weather['desc'] ?></div>
                                <div class="text-sm text-[var(--card-text-body)]">Crop moisture levels are optimal</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 border-t border-[var(--card-border)] pt-6">
                            <div class="text-center">
                                <span class="text-gray-400 text-xs uppercase font-semibold block mb-1">Soil Moisture</span>
                                <span class="text-lg font-bold text-[var(--card-text-title)]"><?= $weather['moisture'] ?></span>
                            </div>
                            <div class="text-center border-x border-[var(--card-border)]">
                                <span class="text-gray-400 text-xs uppercase font-semibold block mb-1">NPK Status</span>
                                <span class="text-lg font-bold text-emerald-500">Good</span>
                            </div>
                            <div class="text-center">
                                <span class="text-gray-400 text-xs uppercase font-semibold block mb-1">Rain Prob.</span>
                                <span class="text-lg font-bold text-[var(--card-text-title)]"><?= $weather['rain'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 text-xs text-gray-400 flex items-center">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                        <span>Live crop health readings active</span>
                    </div>
                </div>
                
                <!-- Mandi Crop Price Tracker Widget -->
                <div class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)]">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[var(--card-text-title)] flex items-center">
                            <span class="mr-2">📈</span> Mandi Market Prices
                        </h2>
                        <a href="index.php#marketplace" class="text-emerald-500 hover:underline text-xs font-bold transition">View Full Mandi &rarr;</a>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Paddy -->
                        <div class="flex items-center justify-between p-3 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 transition">
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">🌾</span>
                                <div>
                                    <h4 class="font-bold text-[var(--card-text-title)]">Paddy (Rice)</h4>
                                    <span class="text-xs text-gray-400">Super fine grade</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-[var(--card-text-title)]">₹2,180 <span class="text-xs font-normal text-gray-400">/qt</span></div>
                                <span class="text-xs text-emerald-500 font-semibold">▲ +2.4%</span>
                            </div>
                        </div>
                        
                        <!-- Wheat -->
                        <div class="flex items-center justify-between p-3 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 transition">
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">🌾</span>
                                <div>
                                    <h4 class="font-bold text-[var(--card-text-title)]">Wheat</h4>
                                    <span class="text-xs text-gray-400">Kalyan sona</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-[var(--card-text-title)]">₹2,275 <span class="text-xs font-normal text-gray-400">/qt</span></div>
                                <span class="text-xs text-red-400 font-semibold">▼ -0.8%</span>
                            </div>
                        </div>
                        
                        <!-- Cotton -->
                        <div class="flex items-center justify-between p-3 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 transition">
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">🌿</span>
                                <div>
                                    <h4 class="font-bold text-[var(--card-text-title)]">Cotton</h4>
                                    <span class="text-xs text-gray-400">Medium staple</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-[var(--card-text-title)]">₹6,800 <span class="text-xs font-normal text-gray-400">/qt</span></div>
                                <span class="text-xs text-emerald-500 font-semibold">▲ +4.2%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Government Updates & News Section -->
            <div id="news" class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)]">
                <h2 class="text-2xl font-bold text-[var(--card-text-title)] mb-6 flex items-center">
                    <span class="mr-2">📢</span> Government Updates & News Alerts
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4 p-4 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 transition">
                        <span class="text-2xl mt-0.5">🔔</span>
                        <div>
                            <h4 class="font-bold text-[var(--card-text-title)]">Kharif Crop MSP Hiked by 5-10%</h4>
                            <p class="text-sm text-[var(--card-text-body)] mt-1">The Union Cabinet has approved an increase in Minimum Support Prices (MSP) for all mandated Kharif crops for marketing season 2026-27, ensuring higher returns for paddy, pulses, and oilseed growers.</p>
                            <span class="text-xs text-emerald-500 font-semibold block mt-2">Published: June 28, 2026</span>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 p-4 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 transition">
                        <span class="text-2xl mt-0.5">🗓️</span>
                        <div>
                            <h4 class="font-bold text-[var(--card-text-title)]">PMFBY Crop Insurance Extension</h4>
                            <p class="text-sm text-[var(--card-text-body)] mt-1">The last date for registration under Pradhan Mantri Fasal Bima Yojana (PMFBY) has been extended to July 31, 2026. Farmers are advised to insure their crops against weather discrepancies.</p>
                            <span class="text-xs text-emerald-500 font-semibold block mt-2">Published: June 25, 2026</span>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 p-4 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 transition">
                        <span class="text-2xl mt-0.5">🧪</span>
                        <div>
                            <h4 class="font-bold text-[var(--card-text-title)]">Subsidized Fertilizer Distribution Active</h4>
                            <p class="text-sm text-[var(--card-text-body)] mt-1">Farmers possessing a valid Soil Health Card can claim a 20% subsidy on organic compost and micronutrients at licensed cooperative depots starting this week.</p>
                            <span class="text-xs text-emerald-500 font-semibold block mt-2">Published: June 20, 2026</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)]">
                <h2 class="text-xl font-bold text-[var(--card-text-title)] mb-6">Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <a href="index.php#schemes" class="bg-white/5 hover:bg-white/10 border border-white/5 p-5 rounded-2xl text-center transition duration-150 flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-500/15 rounded-full flex items-center justify-center mb-3 text-emerald-400 border border-emerald-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-[var(--text-color)]">Check Eligibility</span>
                    </a>
                    <a href="index.php#marketplace" class="bg-white/5 hover:bg-white/10 border border-white/5 p-5 rounded-2xl text-center transition duration-150 flex flex-col items-center">
                        <div class="w-12 h-12 bg-blue-500/15 rounded-full flex items-center justify-center mb-3 text-blue-400 border border-blue-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-[var(--text-color)]">Marketplace</span>
                    </a>
                    <a href="#" class="bg-white/5 hover:bg-white/10 border border-white/5 p-5 rounded-2xl text-center transition duration-150 flex flex-col items-center">
                        <div class="w-12 h-12 bg-purple-500/15 rounded-full flex items-center justify-center mb-3 text-purple-400 border border-purple-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-[var(--text-color)]">Payment Status</span>
                    </a>
                    <a href="#" class="bg-white/5 hover:bg-white/10 border border-white/5 p-5 rounded-2xl text-center transition duration-150 flex flex-col items-center">
                        <div class="w-12 h-12 bg-amber-500/15 rounded-full flex items-center justify-center mb-3 text-amber-400 border border-amber-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-[var(--text-color)]">Help Center</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- SECTION 2: SCHEMES (Checker Form & Available Schemes list) -->
        <div id="section-schemes" class="hidden space-y-8 scroll-mt-24">
            <!-- Schemes Eligibility Form -->
            <div class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)]">
                <h2 class="text-2xl font-bold text-[var(--card-text-title)] mb-6">Check Scheme Eligibility</h2>
                
                <?php
                // Schemes list mapping
                $schemes = [
                    [
                        'name' => 'PM Kisan Samman Nidhi',
                        'description' => '₹6,000 per year in three installments',
                        'eligibility' => ['all'],
                        'income_limit' => null,
                        'land_limit' => null,
                        'category' => ['Small', 'Medium', 'Large'],
                        'link' => 'https://pmkisan.gov.in/'
                    ],
                    [
                        'name' => 'Pradhan Mantri Fasal Bima Yojana (PMFBY)',
                        'description' => 'Crop insurance against weather risks',
                        'eligibility' => ['all'],
                        'income_limit' => null,
                        'land_limit' => null,
                        'category' => ['Small', 'Medium', 'Large'],
                        'link' => 'https://pmfby.gov.in/'
                    ],
                    [
                        'name' => 'YSR Rythu Bharosa (Andhra Pradesh)',
                        'description' => '₹13,500 per year financial aid',
                        'eligibility' => ['Andhra Pradesh'],
                        'income_limit' => 500000,
                        'land_limit' => 5,
                        'category' => ['Small', 'Medium'],
                        'link' => 'https://ysrrythubharosa.ap.gov.in/'
                    ],
                    [
                        'name' => 'Rythu Bandhu (Telangana)',
                        'description' => '₹10,000 per acre investment support',
                        'eligibility' => ['Telangana'],
                        'income_limit' => null,
                        'land_limit' => null,
                        'category' => ['Small', 'Medium', 'Large'],
                        'link' => 'https://rythubandhu.telangana.gov.in/'
                    ],
                    [
                        'name' => 'KALIA Scheme (Odisha)',
                        'description' => 'Financial aid for small and marginal farmers',
                        'eligibility' => ['Odisha'],
                        'income_limit' => 300000,
                        'land_limit' => 2,
                        'category' => ['Small'],
                        'link' => 'https://kalia.co.in/'
                    ],
                    [
                        'name' => 'Krishak Bandhu (West Bengal)',
                        'description' => '₹10,000 per acre + death benefit',
                        'eligibility' => ['West Bengal'],
                        'income_limit' => 500000,
                        'land_limit' => 5,
                        'category' => ['Small', 'Medium'],
                        'link' => 'https://krishakbandhu.net/'
                    ],
                    [
                        'name' => 'Mahatma Jyotiba Phule Shetkari Karja Mukti Yojana (Maharashtra)',
                        'description' => 'Loan waiver for farmers',
                        'eligibility' => ['Maharashtra'],
                        'income_limit' => 500000,
                        'land_limit' => 5,
                        'category' => ['Small', 'Medium'],
                        'link' => 'https://mahafss.maharashtra.gov.in/'
                    ],
                    [
                        'name' => 'Bhavantar Bhugtan Yojana (Madhya Pradesh)',
                        'description' => 'Price difference payment for crops',
                        'eligibility' => ['Madhya Pradesh'],
                        'income_limit' => 600000,
                        'land_limit' => null,
                        'category' => ['Small', 'Medium', 'Large'],
                        'link' => 'https://mpeuparjan.nic.in/'
                    ]
                ];

                // Process Form Post within the active SPA wrapper
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_eligibility'])) {
                    $name = htmlspecialchars($_POST['name'] ?? '');
                    $state = htmlspecialchars($_POST['state'] ?? '');
                    $income = floatval($_POST['income'] ?? 0);
                    $land = floatval($_POST['land'] ?? 0);
                    $category = htmlspecialchars($_POST['category'] ?? 'Small');
                    
                    function isEligible($scheme, $state, $income, $land, $category) {
                        if (!in_array('all', $scheme['eligibility']) && !in_array($state, $scheme['eligibility'])) {
                            return false;
                        }
                        if ($scheme['income_limit'] !== null && $income > $scheme['income_limit']) {
                            return false;
                        }
                        if ($scheme['land_limit'] !== null && $land > $scheme['land_limit']) {
                            return false;
                        }
                        if (!in_array($category, $scheme['category'])) {
                            return false;
                        }
                        return true;
                    }
                    
                    $eligibleSchemes = array_filter($schemes, function($scheme) use ($state, $income, $land, $category) {
                        return isEligible($scheme, $state, $income, $land, $category);
                    });
                    ?>
                    <div class="bg-[var(--card-bg)] border border-[var(--card-border)] rounded-2xl overflow-hidden mb-8 shadow-md">
                        <div class="bg-emerald-600/90 py-4 px-6 border-b border-[var(--card-border)]">
                            <h3 class="text-xl font-bold text-white">Eligibility Results</h3>
                            <p class="text-emerald-100 text-sm">Schemes available for <?= $name ?></p>
                        </div>
                        <div class="p-6">
                            <div class="mb-6 p-5 bg-white/5 border border-white/10 rounded-2xl">
                                <h4 class="text-lg font-bold text-emerald-400 mb-2">Farmer Profile</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mt-2">
                                    <p><span class="text-gray-400 block">State</span> <span class="font-medium"><?= $state ?></span></p>
                                    <p><span class="text-gray-400 block">Annual Income</span> <span class="font-medium">₹<?= number_format($income) ?></span></p>
                                    <p><span class="text-gray-400 block">Land Holding</span> <span class="font-medium"><?= $land ?> acres</span></p>
                                    <p><span class="text-gray-400 block">Category</span> <span class="font-medium"><?= $category ?> farmer</span></p>
                                </div>
                            </div>
                            <h4 class="text-lg font-bold text-[var(--card-text-title)] mb-4">Available Schemes (<?= count($eligibleSchemes) ?>)</h4>
                            
                            <?php if (empty($eligibleSchemes)): ?>
                                <div class="bg-yellow-950/30 border border-yellow-500/20 p-4 rounded-xl">
                                    <p class="text-yellow-200 text-sm">No schemes found matching your criteria. You may still be eligible for some central schemes.</p>
                                </div>
                            <?php else: ?>
                                <div class="space-y-4">
                                    <?php foreach ($eligibleSchemes as $scheme): ?>
                                        <div class="border border-[var(--card-border)] bg-white/5 p-5 rounded-2xl hover:bg-white/10 transition duration-200">
                                            <h5 class="font-bold text-emerald-400 text-lg"><?= $scheme['name'] ?></h5>
                                            <p class="text-[var(--card-text-body)] mt-1.5 text-sm"><?= $scheme['description'] ?></p>
                                            <div class="mt-3 text-xs text-gray-400 space-y-1">
                                                <p><span class="font-semibold text-gray-300">Eligibility:</span> <?= in_array('all', $scheme['eligibility']) ? 'All India' : implode(', ', $scheme['eligibility']) ?></p>
                                                <?php if ($scheme['income_limit']): ?>
                                                    <p><span class="font-semibold text-gray-300">Income Limit:</span> Up to ₹<?= number_format($scheme['income_limit']) ?></p>
                                                <?php endif; ?>
                                                <?php if ($scheme['land_limit']): ?>
                                                    <p><span class="font-semibold text-gray-300">Land Limit:</span> Up to <?= $scheme['land_limit'] ?> acres</p>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!empty($scheme['link'])): ?>
                                                <div class="mt-4">
                                                    <a href="<?= $scheme['link'] ?>" target="_blank" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 text-sm font-semibold transition">
                                                        <span>Official Website</span>
                                                        <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <script>
                        window.location.hash = '#schemes';
                    </script>
                    <?php
                }
                ?>

                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-400 mb-1.5">Full Name</label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
                                   value="<?= htmlspecialchars($_SESSION['farmer_name'] ?? '') ?>">
                        </div>
                        
                        <div>
                            <label for="state" class="block text-sm font-semibold text-gray-400 mb-1.5">State</label>
                            <select id="state" name="state" required 
                                    class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition appearance-none bg-no-repeat pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%239CA3AF%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-position: right 1rem center; background-size: 1.25rem;">
                                <option value="" disabled>Select state</option>
                                <option value="Andhra Pradesh" <?= ($_SESSION['farmer_state'] ?? '') === 'Andhra Pradesh' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">Andhra Pradesh</option>
                                <option value="Bihar" <?= ($_SESSION['farmer_state'] ?? '') === 'Bihar' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">Bihar</option>
                                <option value="Gujarat" <?= ($_SESSION['farmer_state'] ?? '') === 'Gujarat' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">Gujarat</option>
                                <option value="Madhya Pradesh" <?= ($_SESSION['farmer_state'] ?? '') === 'Madhya Pradesh' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">Madhya Pradesh</option>
                                <option value="Maharashtra" <?= ($_SESSION['farmer_state'] ?? '') === 'Maharashtra' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">Maharashtra</option>
                                <option value="Odisha" <?= ($_SESSION['farmer_state'] ?? '') === 'Odisha' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">Odisha</option>
                                <option value="Telangana" <?= ($_SESSION['farmer_state'] ?? '') === 'Telangana' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">Telangana</option>
                                <option value="West Bengal" <?= ($_SESSION['farmer_state'] ?? '') === 'West Bengal' ? 'selected' : '' ?> style="background-color: var(--bg-color); color: var(--input-text);">West Bengal</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="income" class="block text-sm font-semibold text-gray-400 mb-1.5">Annual Income (₹)</label>
                            <input type="number" id="income" name="income" required min="0" step="1000"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                        
                        <div>
                            <label for="land" class="block text-sm font-semibold text-gray-400 mb-1.5">Land Holding (acres)</label>
                            <input type="number" id="land" name="land" required min="0" step="0.1"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-400 mb-2">Farmer Category</label>
                            <div class="mt-2 flex flex-wrap gap-6">
                                <label class="inline-flex items-center cursor-pointer select-none">
                                    <input type="radio" name="category" value="Small" class="h-4.5 w-4.5 text-emerald-600 focus:ring-emerald-500 border-[var(--input-border)] bg-[var(--input-bg)]" checked>
                                    <span class="ml-2.5 text-sm font-medium">Small (0-2 acres)</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer select-none">
                                    <input type="radio" name="category" value="Medium" class="h-4.5 w-4.5 text-emerald-600 focus:ring-emerald-500 border-[var(--input-border)] bg-[var(--input-bg)]">
                                    <span class="ml-2.5 text-sm font-medium">Medium (2-5 acres)</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer select-none">
                                    <input type="radio" name="category" value="Large" class="h-4.5 w-4.5 text-emerald-600 focus:ring-emerald-500 border-[var(--input-border)] bg-[var(--input-bg)]">
                                    <span class="ml-2.5 text-sm font-medium">Large (5+ acres)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-[var(--card-border)]">
                        <button type="submit" name="check_eligibility"
                                class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-2xl transition duration-150 shadow-lg shadow-emerald-600/15">
                            Check Eligibility
                        </button>
                    </div>
                </form>
            </div>

            <!-- Available Schemes List -->
            <div>
                <h2 id="schemes" class="text-2xl font-bold text-[var(--card-text-title)] mb-6 scroll-mt-24">Available Schemes for You</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-[var(--card-bg)] border border-[var(--card-border)] rounded-3xl shadow-md overflow-hidden border-l-4 border-emerald-500 p-6 flex flex-col justify-between min-h-[180px]">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--card-text-title)] mb-2">PM Kisan Samman Nidhi</h3>
                            <p class="text-[var(--card-text-body)] text-sm mb-4">₹6,000 per year in three installments</p>
                        </div>
                        <div class="flex justify-between items-center mt-4">
                            <span class="bg-emerald-500/10 text-emerald-400 text-xs font-semibold px-2.5 py-1 border border-emerald-500/20 rounded-lg">Central Scheme</span>
                            <a href="https://www.india.gov.in/farmers-portal" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-semibold text-sm transition">Apply Now</a>
                        </div>
                    </div>
                    
                    <?php if ($_SESSION['farmer_state'] === 'Andhra Pradesh'): ?>
                    <div class="bg-[var(--card-bg)] border border-[var(--card-border)] rounded-3xl shadow-md overflow-hidden border-l-4 border-blue-500 p-6 flex flex-col justify-between min-h-[180px]">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--card-text-title)] mb-2">YSR Rythu Bharosa</h3>
                            <p class="text-[var(--card-text-body)] text-sm mb-4">₹13,500 per year financial aid</p>
                        </div>
                        <div class="flex justify-between items-center mt-4">
                            <span class="bg-blue-500/10 text-blue-400 text-xs font-semibold px-2.5 py-1 border border-blue-500/20 rounded-lg">Andhra Pradesh</span>
                            <a href="https://www.india.gov.in/farmers-portal" target="_blank" class="text-blue-400 hover:text-blue-300 font-semibold text-sm transition">Apply Now</a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="bg-[var(--card-bg)] border border-[var(--card-border)] rounded-3xl shadow-md overflow-hidden border-l-4 border-emerald-500 p-6 flex flex-col justify-between min-h-[180px]">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--card-text-title)] mb-2">PM Fasal Bima Yojana</h3>
                            <p class="text-[var(--card-text-body)] text-sm mb-4">Crop insurance against weather risks</p>
                        </div>
                        <div class="flex justify-between items-center mt-4">
                            <span class="bg-emerald-500/10 text-emerald-400 text-xs font-semibold px-2.5 py-1 border border-emerald-500/20 rounded-lg">Central Scheme</span>
                            <a href="https://www.india.gov.in/farmers-portal" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-semibold text-sm transition">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: MARKETPLACE (List Crops, Product Cards Grid) -->
        <div id="section-marketplace" class="hidden space-y-8 scroll-mt-24">
            
            <!-- Seller Listing Form -->
            <div class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)]">
                <h2 class="text-2xl font-bold text-[var(--card-text-title)] mb-4 flex items-center">
                    <span class="mr-2.5">👨‍🌾</span> List Your Crop for Sale
                </h2>
                <p class="text-sm text-[var(--card-text-body)] mb-6">List your fresh harvest directly so other buyers or traders can contact you directly.</p>
                
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="commodity" class="block text-sm font-semibold text-gray-400 mb-1.5">Crop / Commodity Name*</label>
                            <input type="text" id="commodity" name="commodity" required placeholder="e.g. Basmati Paddy, Organic Tomatoes"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                        
                        <div>
                            <label for="variety" class="block text-sm font-semibold text-gray-400 mb-1.5">Variety / Grade</label>
                            <input type="text" id="variety" name="variety" placeholder="e.g. Grade A, Pusa 1509"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                        
                        <div>
                            <label for="image_type" class="block text-sm font-semibold text-gray-400 mb-1.5">Product Category*</label>
                            <select id="image_type" name="image_type" required 
                                    class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition appearance-none bg-no-repeat pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%239CA3AF%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-position: right 1rem center; background-size: 1.25rem;">
                                <option value="paddy">Rice / Paddy</option>
                                <option value="wheat">Wheat / Grains</option>
                                <option value="cotton">Cotton / Fiber</option>
                                <option value="vegetable">Vegetables / Roots</option>
                                <option value="fruit">Fruits / Orchard</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="location" class="block text-sm font-semibold text-gray-400 mb-1.5">Mandi / Location*</label>
                            <input type="text" id="location" name="location" required placeholder="e.g. Karnal, Haryana"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                        
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-400 mb-1.5">Price per Quintal (₹)*</label>
                            <input type="number" id="price" name="price" required min="1" placeholder="e.g. 2300"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                        
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-gray-400 mb-1.5">Total Quantity Available*</label>
                            <input type="text" id="quantity" name="quantity" required placeholder="e.g. 50 Quintals, 500 Kg"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                        
                        <div class="md:col-span-3">
                            <label for="contact" class="block text-sm font-semibold text-gray-400 mb-1.5">Contact Number / WhatsApp*</label>
                            <input type="text" id="contact" name="contact" required placeholder="e.g. +91 98765 43210"
                                   class="w-full px-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] text-[var(--input-text)] rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-[var(--card-border)]">
                        <button type="submit" name="sell_crop"
                                class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-2xl transition duration-150 shadow-lg shadow-emerald-600/15">
                            Post Crop Listing
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Live Marketplace Grid -->
            <div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-[var(--card-text-title)]">Direct Farmer Marketplace</h2>
                        <p class="text-sm text-[var(--card-text-body)] mt-1">Buy directly from verified local producers without middlemen commissions.</p>
                    </div>
                    
                    <!-- Marketplace Search -->
                    <div class="relative w-full sm:w-80">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" placeholder="Search commodities or locations..." onkeyup="filterCropCards(this)"
                               class="w-full pl-10 pr-4 py-3 bg-[var(--input-bg)] border border-[var(--input-border)] rounded-2xl text-sm focus:outline-none focus:border-emerald-500 transition text-[var(--card-text-title)]">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="cropGrid">
                    
                    <!-- Dynamic database products -->
                    <?php if (!empty($db_products)): ?>
                        <?php foreach ($db_products as $product): 
                            $preset = $crop_presets[$product['image_type']] ?? $crop_presets['paddy'];
                            ?>
                            <div class="crop-card bg-[var(--card-bg)] border border-[var(--card-border)] rounded-[2rem] overflow-hidden shadow-lg hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">
                                <div>
                                    <div class="h-48 overflow-hidden relative">
                                        <img src="<?= $preset['img'] ?>" alt="<?= htmlspecialchars($product['commodity']) ?>" class="w-full h-full object-cover">
                                        <span class="absolute top-3 right-3 bg-emerald-600/90 text-white font-bold text-xs px-3 py-1 rounded-full border border-emerald-400/20">
                                            <?= $preset['emoji'] ?> <?= htmlspecialchars(ucfirst($product['image_type'])) ?>
                                        </span>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-[var(--card-text-title)] tracking-tight truncate"><?= htmlspecialchars($product['commodity']) ?></h3>
                                            <p class="text-xs text-emerald-400 font-semibold mt-1">Variety: <?= htmlspecialchars($product['variety'] ?: 'Regular') ?></p>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-3 text-xs bg-white/5 border border-white/5 p-3.5 rounded-xl text-[var(--card-text-body)]">
                                            <div>
                                                <span class="text-gray-400 block mb-0.5">Location</span>
                                                <span class="font-semibold text-[var(--card-text-title)] truncate block"><?= htmlspecialchars($product['location']) ?></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 block mb-0.5">Stock Quantity</span>
                                                <span class="font-semibold text-[var(--card-text-title)] block"><?= htmlspecialchars($product['quantity']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 pb-6 pt-2 border-t border-[var(--card-border)]/50 flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-gray-400 text-xs block">Expected Price</span>
                                        <span class="text-2xl font-extrabold text-[var(--card-text-title)]">₹<?= htmlspecialchars($product['price']) ?><span class="text-xs font-normal text-gray-400">/qt</span></span>
                                    </div>
                                    <a href="tel:<?= htmlspecialchars($product['contact']) ?>" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition flex items-center">
                                        📞 Call Seller
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <!-- Static preset crop card 1 (Paddy) -->
                    <div class="crop-card bg-[var(--card-bg)] border border-[var(--card-border)] rounded-[2rem] overflow-hidden shadow-lg hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="h-48 overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1536304997881-a372c179924b?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Paddy Basmati" class="w-full h-full object-cover">
                                <span class="absolute top-3 right-3 bg-emerald-600/90 text-white font-bold text-xs px-3 py-1 rounded-full border border-emerald-400/20">
                                    🌾 Rice / Paddy
                                </span>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <h3 class="text-xl font-bold text-[var(--card-text-title)] tracking-tight truncate">Basmati Paddy</h3>
                                    <p class="text-xs text-emerald-400 font-semibold mt-1">Variety: Basmati 1121</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 text-xs bg-white/5 border border-white/5 p-3.5 rounded-xl text-[var(--card-text-body)]">
                                    <div>
                                        <span class="text-gray-400 block mb-0.5">Location</span>
                                        <span class="font-semibold text-[var(--card-text-title)] truncate block">Karnal, HR</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block mb-0.5">Stock Quantity</span>
                                        <span class="font-semibold text-[var(--card-text-title)] block">120 Quintals</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6 pt-2 border-t border-[var(--card-border)]/50 flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-gray-400 text-xs block">Expected Price</span>
                                <span class="text-2xl font-extrabold text-[var(--card-text-title)]">₹3,450<span class="text-xs font-normal text-gray-400">/qt</span></span>
                            </div>
                            <a href="tel:+919876543210" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition flex items-center">
                                📞 Call Seller
                            </a>
                        </div>
                    </div>

                    <!-- Static preset crop card 2 (Wheat) -->
                    <div class="crop-card bg-[var(--card-bg)] border border-[var(--card-border)] rounded-[2rem] overflow-hidden shadow-lg hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="h-48 overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Wheat Kalyan" class="w-full h-full object-cover">
                                <span class="absolute top-3 right-3 bg-emerald-600/90 text-white font-bold text-xs px-3 py-1 rounded-full border border-emerald-400/20">
                                    🌾 Wheat / Grains
                                </span>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <h3 class="text-xl font-bold text-[var(--card-text-title)] tracking-tight truncate">Wheat Lok-1</h3>
                                    <p class="text-xs text-emerald-400 font-semibold mt-1">Variety: Kalyan Sona</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 text-xs bg-white/5 border border-white/5 p-3.5 rounded-xl text-[var(--card-text-body)]">
                                    <div>
                                        <span class="text-gray-400 block mb-0.5">Location</span>
                                        <span class="font-semibold text-[var(--card-text-title)] truncate block">Indore, MP</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block mb-0.5">Stock Quantity</span>
                                        <span class="font-semibold text-[var(--card-text-title)] block">85 Quintals</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6 pt-2 border-t border-[var(--card-border)]/50 flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-gray-400 text-xs block">Expected Price</span>
                                <span class="text-2xl font-extrabold text-[var(--card-text-title)]">₹2,280<span class="text-xs font-normal text-gray-400">/qt</span></span>
                            </div>
                            <a href="tel:+919876543210" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition flex items-center">
                                📞 Call Seller
                            </a>
                        </div>
                    </div>

                    <!-- Static preset crop card 3 (Cotton) -->
                    <div class="crop-card bg-[var(--card-bg)] border border-[var(--card-border)] rounded-[2rem] overflow-hidden shadow-lg hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="h-48 overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1594900711581-229ad1ef29b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Cotton" class="w-full h-full object-cover">
                                <span class="absolute top-3 right-3 bg-emerald-600/90 text-white font-bold text-xs px-3 py-1 rounded-full border border-emerald-400/20">
                                    🌿 Cotton / Fiber
                                </span>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <h3 class="text-xl font-bold text-[var(--card-text-title)] tracking-tight truncate">Organic Cotton</h3>
                                    <p class="text-xs text-emerald-400 font-semibold mt-1">Variety: Desi Medium</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 text-xs bg-white/5 border border-white/5 p-3.5 rounded-xl text-[var(--card-text-body)]">
                                    <div>
                                        <span class="text-gray-400 block mb-0.5">Location</span>
                                        <span class="font-semibold text-[var(--card-text-title)] truncate block">Rajkot, GJ</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block mb-0.5">Stock Quantity</span>
                                        <span class="font-semibold text-[var(--card-text-title)] block">60 Quintals</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6 pt-2 border-t border-[var(--card-border)]/50 flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-gray-400 text-xs block">Expected Price</span>
                                <span class="text-2xl font-extrabold text-[var(--card-text-title)]">₹6,800<span class="text-xs font-normal text-gray-400">/qt</span></span>
                            </div>
                            <a href="tel:+919876543210" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition flex items-center">
                                📞 Call Seller
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- SECTION 4: ABOUT (Information & Mission Grid) -->
        <div id="section-about" class="hidden scroll-mt-24">
            <div class="bg-[var(--card-bg)] backdrop-blur-md border border-[var(--card-border)] rounded-[2.25rem] p-6 sm:p-8 shadow-xl text-[var(--text-color)]">
                <h2 class="text-2xl font-bold text-[var(--card-text-title)] mb-6 flex items-center">
                    <span class="mr-2">ℹ️</span> About Krishi Sahay
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-emerald-500">Our Mission</h3>
                        <p class="text-sm text-[var(--card-text-body)] leading-relaxed">
                            Krishi Sahay is a state-of-the-art agricultural facilitation portal dedicated to bridging the information gap for local farmers. Our core mission is to provide seamless, centralized access to various government-sponsored crop subsidies, insurances, and land schemes.
                        </p>
                        <p class="text-sm text-[var(--card-text-body)] leading-relaxed">
                            By removing multi-layer intermediaries, we ensure that verification details, eligibility queries, and market wholesale metrics are directly accessible to rural farmers.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                            <h4 class="font-bold text-[var(--card-text-title)] mb-1">Direct Benefit</h4>
                            <p class="text-xs text-[var(--card-text-body)]">Linking farmers straight to official department scheme registries.</p>
                        </div>
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                            <h4 class="font-bold text-[var(--card-text-title)] mb-1">Live Mandi Prices</h4>
                            <p class="text-xs text-[var(--card-text-body)]">Real-time agricultural commodity tracking across regional trade points.</p>
                        </div>
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                            <h4 class="font-bold text-[var(--card-text-title)] mb-1">Soil Analysis Guidance</h4>
                            <p class="text-xs text-[var(--card-text-body)]">Delivering state-specific suggestions based on crop category and holding limits.</p>
                        </div>
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                            <h4 class="font-bold text-[var(--card-text-title)] mb-1">24/7 Support</h4>
                            <p class="text-xs text-[var(--card-text-body)]">Dedicated support translating complex scheme filings into native languages.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Filter Marketplace Crop Cards in Real Time
function filterCropCards(input) {
    const filter = input.value.toLowerCase();
    const cards = document.querySelectorAll(".crop-card");
    cards.forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(filter) ? "" : "none";
    });
}

// Client-side Router for SPA Tab Navigation
function navigateTab() {
    const hash = window.location.hash || '#home';
    
    const sections = {
        '#home': document.getElementById('section-home'),
        '#schemes': document.getElementById('section-schemes'),
        '#marketplace': document.getElementById('section-marketplace'),
        '#about-us': document.getElementById('section-about')
    };
    
    for (const key in sections) {
        if (sections[key]) {
            if (key === hash) {
                sections[key].classList.remove('hidden');
                sections[key].style.opacity = '0';
                setTimeout(() => {
                    sections[key].style.opacity = '1';
                    sections[key].style.transition = 'opacity 0.25s ease-in-out';
                }, 50);
            } else {
                sections[key].classList.add('hidden');
            }
        }
    }
    
    // Reset active highlights on header links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('nav-link-active');
        link.classList.add('text-[var(--text-color)]', 'hover:text-emerald-500');
    });
    
    let targetNavId = 'nav-home';
    if (hash === '#schemes') targetNavId = 'nav-schemes';
    else if (hash === '#marketplace') targetNavId = 'nav-marketplace';
    else if (hash === '#about-us') targetNavId = 'nav-about';
    
    const activeNav = document.getElementById(targetNavId);
    if (activeNav) {
        activeNav.classList.add('nav-link-active');
        activeNav.classList.remove('text-[var(--text-color)]', 'hover:text-emerald-500');
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.addEventListener('hashchange', navigateTab);
window.addEventListener('DOMContentLoaded', navigateTab);

// Basic client-side validation for schemes eligibility
document.querySelector('form')?.addEventListener('submit', function(event) {
    const state = document.getElementById('state').value;
    const income = document.getElementById('income').value;
    const land = document.getElementById('land').value;
    
    if (!state) {
        alert('Please select your state');
        event.preventDefault();
        return;
    }
    
    if (parseFloat(income) < 0 || isNaN(parseFloat(income))) {
        alert('Please enter a valid income amount');
        event.preventDefault();
        return;
    }
    
    if (parseFloat(land) < 0 || isNaN(parseFloat(land))) {
        alert('Please enter a valid land holding size');
        event.preventDefault();
        return;
    }
});
</script>

<?php include 'includes/footer.php'; ?>