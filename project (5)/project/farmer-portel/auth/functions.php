<?php
require_once 'db.php';

// Register a new farmer
function registerFarmer($name, $aadhaar, $mobile, $state, $password) {
    global $conn;
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare SQL statement
    $sql = "INSERT INTO farmers (name, aadhaar, mobile, state, password) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $aadhaar, $mobile, $state, $hashed_password);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

// Login farmer
function loginFarmer($aadhaar, $password) {
    global $conn;
    
    $sql = "SELECT id, name, aadhaar, state, password FROM farmers WHERE aadhaar = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $aadhaar);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $farmer = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $farmer['password'])) {
            // Set session variables
            $_SESSION['farmer_id'] = $farmer['id'];
            $_SESSION['farmer_name'] = $farmer['name'];
            $_SESSION['farmer_aadhaar'] = $farmer['aadhaar'];
            $_SESSION['farmer_state'] = $farmer['state'];
            $_SESSION['logged_in'] = true;
            
            return true;
        }
    }
    
    return false;
}

// Check if farmer is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Redirect if already logged in
function requireGuest() {
    if (isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}
?>