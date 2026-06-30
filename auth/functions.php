<?php
require_once 'db.php';

// Register a new farmer
function registerFarmer($name, $email, $mobile, $state, $password) {
    global $conn;
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $sql = "INSERT INTO farmers (name, email, mobile, state, password) 
                VALUES (:name, :email, :mobile, :state, :password)";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':mobile' => $mobile,
            ':state' => $state,
            ':password' => $hashed_password
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

// Login farmer using email or mobile number
function loginFarmer($identifier, $password) {
    global $conn;
    
    try {
        $sql = "SELECT id, name, email, mobile, state, password FROM farmers 
                WHERE email = :identifier OR mobile = :identifier";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':identifier' => $identifier]);
        $farmer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($farmer && password_verify($password, $farmer['password'])) {
            // Set session variables
            $_SESSION['farmer_id'] = $farmer['id'];
            $_SESSION['farmer_name'] = $farmer['name'];
            $_SESSION['farmer_email'] = $farmer['email'];
            $_SESSION['farmer_mobile'] = $farmer['mobile'];
            $_SESSION['farmer_state'] = $farmer['state'];
            $_SESSION['logged_in'] = true;
            
            return true;
        }
    } catch (PDOException $e) {
        return false;
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