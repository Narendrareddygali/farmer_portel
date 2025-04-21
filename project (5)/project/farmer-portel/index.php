<?php
require_once 'auth/functions.php';
requireLogin();

$page_title = 'Farmer Dashboard - Krishi Sahay';
include 'includes/header.php';
?>


<div class="py-12   bg-[url('https://t3.ftcdn.net/jpg/06/23/50/12/360_F_623501254_ia54KMU3iq4jD0iLJPXfFo5oV4sil7uX.jpg')] bg-cover bg-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['farmer_name']); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800">Aadhaar Number</h3>
                    <p class="text-gray-700"><?php echo htmlspecialchars($_SESSION['farmer_aadhaar']); ?></p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800">State</h3>
                    <p class="text-gray-700"><?php echo htmlspecialchars($_SESSION['farmer_state']); ?></p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-purple-800">Account Status</h3>
                    <p class="text-gray-700">Verified</p>
                </div>
            </div>
        </div>

        <!-- Farmer Scheme Eligibility Checker Form -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Check Scheme Eligibility</h2>
            
            <?php
            // Define schemes data
            $schemes = [
                // Central Schemes
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
                // State-specific schemes
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

            // Check if form was submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_eligibility'])) {
                // Process form data
                $name = htmlspecialchars($_POST['name'] ?? '');
                $state = htmlspecialchars($_POST['state'] ?? '');
                $income = floatval($_POST['income'] ?? 0);
                $land = floatval($_POST['land'] ?? 0);
                $category = htmlspecialchars($_POST['category'] ?? 'Small');
                
                // Function to check eligibility
                function isEligible($scheme, $state, $income, $land, $category) {
                    // Check state eligibility
                    if (!in_array('all', $scheme['eligibility']) && !in_array($state, $scheme['eligibility'])) {
                        return false;
                    }
                    
                    // Check income limit
                    if ($scheme['income_limit'] !== null && $income > $scheme['income_limit']) {
                        return false;
                    }
                    
                    // Check land limit
                    if ($scheme['land_limit'] !== null && $land > $scheme['land_limit']) {
                        return false;
                    }
                    
                    // Check farmer category
                    if (!in_array($category, $scheme['category'])) {
                        return false;
                    }
                    
                    return true;
                }
                
                // Filter eligible schemes
                $eligibleSchemes = array_filter($schemes, function($scheme) use ($state, $income, $land, $category) {
                    return isEligible($scheme, $state, $income, $land, $category);
                });
                
                // Display results
                ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-green-600 py-4 px-6 rounded-t-lg">
                        <h3 class="text-xl font-bold text-white">Eligibility Results</h3>
                        <p class="text-green-100">Schemes available for <?= $name ?></p>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-lg font-semibold text-blue-800">Farmer Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <p><span class="font-medium">State:</span> <?= $state ?></p>
                                <p><span class="font-medium">Annual Income:</span> ₹<?= number_format($income) ?></p>
                                <p><span class="font-medium">Land Holding:</span> <?= $land ?> acres</p>
                                <p><span class="font-medium">Category:</span> <?= $category ?> farmer</p>
                            </div>
                        </div>
                        
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Available Schemes (<?= count($eligibleSchemes) ?>)</h4>
                        
                        <?php if (empty($eligibleSchemes)): ?>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <p class="text-yellow-700">No schemes found matching your criteria. You may still be eligible for some central schemes - please check with your local agriculture office.</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($eligibleSchemes as $scheme): ?>
                                    <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded hover:bg-green-100 transition duration-200">
                                        <h5 class="font-bold text-green-800"><?= $scheme['name'] ?></h5>
                                        <p class="text-gray-700 mt-1"><?= $scheme['description'] ?></p>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p><span class="font-medium">Eligibility:</span> 
                                                <?= in_array('all', $scheme['eligibility']) ? 'All India' : implode(', ', $scheme['eligibility']) ?>
                                            </p>
                                            <?php if ($scheme['income_limit']): ?>
                                                <p><span class="font-medium">Income Limit:</span> Up to ₹<?= number_format($scheme['income_limit']) ?></p>
                                            <?php endif; ?>
                                            <?php if ($scheme['land_limit']): ?>
                                                <p><span class="font-medium">Land Limit:</span> Up to <?= $scheme['land_limit'] ?> acres</p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($scheme['link'])): ?>
                                            <div class="mt-3">
                                                <a href="<?= $scheme['link'] ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    Official Website &rarr;
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            ?>

            <form method="post" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="name" name="name" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
                               value="<?= htmlspecialchars($_SESSION['farmer_name'] ?? '') ?>">
                    </div>
                    
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                        <select id="state" name="state" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border">
                            <option value="">Select your state</option>
                            <option value="Andhra Pradesh" <?= ($_SESSION['farmer_state'] ?? '') === 'Andhra Pradesh' ? 'selected' : '' ?>>Andhra Pradesh</option>
                            <option value="Bihar" <?= ($_SESSION['farmer_state'] ?? '') === 'Bihar' ? 'selected' : '' ?>>Bihar</option>
                            <option value="Gujarat" <?= ($_SESSION['farmer_state'] ?? '') === 'Gujarat' ? 'selected' : '' ?>>Gujarat</option>
                            <option value="Madhya Pradesh" <?= ($_SESSION['farmer_state'] ?? '') === 'Madhya Pradesh' ? 'selected' : '' ?>>Madhya Pradesh</option>
                            <option value="Maharashtra" <?= ($_SESSION['farmer_state'] ?? '') === 'Maharashtra' ? 'selected' : '' ?>>Maharashtra</option>
                            <option value="Odisha" <?= ($_SESSION['farmer_state'] ?? '') === 'Odisha' ? 'selected' : '' ?>>Odisha</option>
                            <option value="Telangana" <?= ($_SESSION['farmer_state'] ?? '') === 'Telangana' ? 'selected' : '' ?>>Telangana</option>
                            <option value="West Bengal" <?= ($_SESSION['farmer_state'] ?? '') === 'West Bengal' ? 'selected' : '' ?>>West Bengal</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="income" class="block text-sm font-medium text-gray-700">Annual Income (₹)</label>
                        <input type="number" id="income" name="income" required min="0" step="1000"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border">
                    </div>
                    
                    <div>
                        <label for="land" class="block text-sm font-medium text-gray-700">Land Holding (acres)</label>
                        <input type="number" id="land" name="land" required min="0" step="0.1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Farmer Category</label>
                        <div class="mt-2 flex flex-wrap gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="category" value="Small" class="text-green-600" checked>
                                <span class="ml-2">Small (0-2 acres)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="category" value="Medium" class="text-green-600">
                                <span class="ml-2">Medium (2-5 acres)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="category" value="Large" class="text-green-600">
                                <span class="ml-2">Large (5+ acres)</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit" name="check_eligibility"
                            class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-150">
                        Check Eligibility
                    </button>
                </div>
            </form>
        </div>

        <h2 class="text-2xl font-bold text-white mb-6">Available Schemes for You</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- PM Kisan Samman Nidhi -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-500">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">PM Kisan Samman Nidhi</h3>
                    <p class="text-gray-600 mb-4">₹6,000 per year in three installments</p>
                    <div class="flex justify-between items-center">
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Central Scheme</span>
                        <a href="https://www.india.gov.in/farmers-portal" class="text-green-600 hover:text-green-800 font-medium">Apply Now</a>
                    </div>
                </div>
            </div>
            
            <!-- State-specific scheme -->
            <?php if ($_SESSION['farmer_state'] === 'Andhra Pradesh'): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">YSR Rythu Bharosa</h3>
                    <p class="text-gray-600 mb-4">₹13,500 per year financial aid</p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Andhra Pradesh</span>
                        <a href="https://www.india.gov.in/farmers-portal" class="text-blue-600 hover:text-blue-800 font-medium">Apply Now</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- PM Fasal Bima Yojana -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-500">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">PM Fasal Bima Yojana</h3>
                    <p class="text-gray-600 mb-4">Crop insurance against weather risks</p>
                    <div class="flex justify-between items-center">
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Central Scheme</span>
                        <a href="https://www.india.gov.in/farmers-portal" class="text-green-600 hover:text-green-800 font-medium">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-12 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="form.html" class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition">
                    <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Check Eligibility</span>
                </a>
                <a href="market.html" class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition">
                    <div class="mx-auto w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Market place</span>
                </a>
                <a href="#" class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition">
                    <div class="mx-auto w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Payment Status</span>
                </a>
                <a href="#" class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition">
                    <div class="mx-auto w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Help Center</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    // Basic client-side validation
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