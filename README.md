# Krishi Sahay (कृषि सहाय) 🌾
### Smart Farmer Facilitation & Direct Crop Marketplace Portal

**Krishi Sahay** is a premium, nature-themed web application designed to empower Indian farmers. It simplifies access to central and state government agricultural schemes, monitors weather and soil health parameters, tracks mandi commodity prices, and provides a direct-to-buyer crop marketplace.

---

## 🌟 Key Features (A-Z)

1. **Adaptive Earthy Themes (Light / Dark):**
   - Features a floating glassmorphic navbar with custom-designed farmer-themed SVG toggles.
   - **Sunrise Sprout (Light Mode):** Soft cream-sage backdrop, sun-kissed sand orbs, and deep forest focus highlights.
   - **Moon Sprout (Dark Mode):** Moss green and deep forest gradients, frosted dark-glass cards, and mint green focuses.
   - Uses local storage for theme persistence and an anti-flash loading script to prevent flickering.

2. **Crop Marketplace (Add & Sell Crops):**
   - A direct farmer-to-buyer trade directory backed by SQLite persistence.
   - Farmers can fill out a form to list their own harvests (Commodity Name, Variety, Mandi Location, Price, Quantity, Contact Phone, and Category).
   - Category selection automatically renders high-quality visual thumbnails for paddy, wheat, cotton, fruits, and vegetables.
   - Includes a **real-time client-side search box** to filter crop listings dynamically.

3. **Dynamic SPA Navigation (Single Page Application):**
   - The home page groups components into dynamic tab panels (`#home`, `#schemes`, `#marketplace`, `#about-us`).
   - Restructures layout clutter: Schemes checker and Marketplace tables are hidden by default, displaying dynamically through JavaScript hash routing when clicked.
   - Active navbar links display a custom animated frosted capsule and a sliding gradient gleam animation.

4. **Government News Alerts Bulletin:**
   - Displays real-time critical announcements, meteorological forecasts, and cooperative fertilizer subsidies directly on the home dashboard.

5. **Personalized Weather & Soil Health Monitor:**
   - Detects the farmer's registered State and displays local weather temperatures, soil moisture content, rain probabilities, and NPK health indicators.

6. **Simplified Authentication:**
   - Replaced complex Aadhaar inputs with standard **Email** and **Mobile Number** parameters.
   - Supports **dual-identifier login**, allowing users to log in using either their registered email or mobile number.

7. **Sticky Frosted Glass Navbar:**
   - A floating sticky header containing frosted backdrop-blur effects and nature animations that sits at the top of the page.

---

## 🛠️ Technology Stack

- **Frontend:** HTML5, TailwindCSS (for responsive utility layouts), CSS Variables (for adaptive themes), Vanilla JavaScript (for SPA routing, search filtration, and theme handlers).
- **Backend:** PHP 8.x (session management, form parsing, validation, database queries).
- **Database:** SQLite 3 (lightweight, zero-config local storage for user credentials and marketplace listings).

---

## 📁 Directory Structure

```text
├── assets/
│   └── css/
│       └── styles.css          # Modern inputs, tab switchers, and layout overrides
├── auth/
│   ├── db.php                  # Database connection & automated SQLite table migrations
│   └── functions.php           # Authentication helper functions & login checkers
├── includes/
│   ├── header.php              # Navbar, SVG toggles, CSS variables & ambient wallpaper orbs
│   └── footer.php              # Redesigned glassmorphic footer
├── database_setup.sql          # Standard baseline SQL schema
├── farmer_portal.db            # SQLite database file (locally generated)
├── index.php                   # Dashboard landing (Home widgets, Schemes SPA, Marketplace)
├── login.php                   # Frosted glass login portal with dual-identifier inputs
├── register.php                # Simple registration page with peer-label inputs
├── logout.php                  # Destroys sessions and redirects
└── .gitignore                  # Prevents committing local binary database files
```

---

## 🚀 Setup & Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Narendrareddygali/farmer_portel.git
   cd farmer_portel
   ```

2. **Initialize Local SQLite Database:**
   - The database file (`farmer_portal.db`) and all required tables (`farmers`, `marketplace_products`) are created **automatically** the first time any page is loaded inside a PHP environment. No manual SQL import is required.

3. **Run Dev Server Locally:**
   ```bash
   php -S localhost:8000
   ```

4. **Access the App:**
   - Open your browser and go to `http://localhost:8000/register.php` to create a test farmer profile, then log in to explore the dashboard.

---

## ⚖️ License & Copyright
Developed as part of the Krishi Sahay portal. All rights reserved &copy; 2026.
