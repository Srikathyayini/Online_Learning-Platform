<?php
session_start();
include 'connect.php';
// Handle Sign Up
if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 for simplicity, use password_hash() in production

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email Address Already Exists!'); window.location='register.html';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $firstName, $email, $password);
        if ($stmt->execute()) {
            echo "<script>alert('Registered Successfully!'); window.location='register.html';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
    $stmt->close();
}

// Handle Sign In
if (isset($_POST['signIn'])) {
    $name = $_POST['fName'];
    $password = $_POST['password'];

    // Admin check (before hashing)
    if ($name === 'admin' && $password === 'admin') {
        $_SESSION['is_admin'] = true;
        $_SESSION['fName'] = 'Admin';
        echo "<script>alert('Admin Login Successful!'); window.location='admin_home.php';</script>";
        exit();
    }

    // Regular user login
    $password = md5($password);
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND password = ?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['fName'] = $name;
        echo "<script>alert('Login Successfully!'); window.location='home2.php';</script>";
        exit();
    } else {
        echo "<script>alert('Not Found, Incorrect Name or Password'); window.location='register.html';</script>";
    }
    $stmt->close();
}

$conn->close();
?>