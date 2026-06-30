<?php
session_start();

// Database configuration
// Using SQLite for seamless local execution
try {
    $db_path = __DIR__ . '/../farmer_portal.db';
    $conn = new PDO("sqlite:" . $db_path);
    
    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Migration: Check if 'farmers' table exists and contains 'aadhaar' column
    try {
        $q = $conn->query("PRAGMA table_info(farmers)");
        if ($q) {
            $cols = $q->fetchAll(PDO::FETCH_COLUMN, 1);
            if (in_array('aadhaar', $cols)) {
                $conn->exec("DROP TABLE farmers");
            }
        }
    } catch (Exception $e) {
        // Table might not exist yet; safe to ignore
    }
    
    // Create tables with email & mobile instead of Aadhaar
    $conn->exec("CREATE TABLE IF NOT EXISTS farmers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        mobile TEXT NOT NULL UNIQUE,
        state TEXT NOT NULL,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create marketplace products table
    $conn->exec("CREATE TABLE IF NOT EXISTS marketplace_products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        farmer_id INTEGER NOT NULL,
        farmer_name TEXT NOT NULL,
        commodity TEXT NOT NULL,
        variety TEXT NOT NULL,
        location TEXT NOT NULL,
        price TEXT NOT NULL,
        quantity TEXT NOT NULL,
        contact TEXT NOT NULL,
        image_type TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>