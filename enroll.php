<?php
session_start();
include "connect.php";

// Check if user is logged in
if (!isset($_SESSION['fName'])) {
    header("Location: register.php");
    exit();
}

// Check if course_id is passed
if (!isset($_GET['course_id'])) {
    die("No course selected");
}

$course_id = intval($_GET['course_id']);
$username = $_SESSION['fName'];
$check_sql = "SELECT title FROM courses2 WHERE id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result2 = $stmt->get_result();

if ($result2->num_rows == 0) {
    $_SESSION['error_message'] = "Course not found.";
    header("Location: index.php");
    $stmt->close();
    $conn->close();
    exit();
}

$course2 = $result2->fetch_assoc();
$stmt->close();
// Fetch course details
$sql = "SELECT title FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);

// Check if statement preparation was successful
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Course not found");
}

$course = $result->fetch_assoc();
$course_title = $course['title'];

// Check if already enrolled
$check_enrollment = "SELECT * FROM user_enrollments WHERE user_name = ? AND course_id = ?";
$stmt = $conn->prepare($check_enrollment);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("ii", $username, $course_id);
$stmt->execute();
$enrollment_result = $stmt->get_result();

if ($enrollment_result->num_rows > 0) {
    echo "<script>
        alert('You are already enrolled in this course: " . htmlspecialchars($course_title) . "');
        window.location.href = 'my_courses.php';
    </script>";
    exit();
}

// First, check if the user_enrollments table exists
$table_check = "SHOW TABLES LIKE 'user_enrollments'";
$table_result = $conn->query($table_check);

if ($table_result->num_rows == 0) {
    // Table doesn't exist, create it
    $create_table = "CREATE TABLE user_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_name INT NOT NULL,
        course_id INT NOT NULL,
        enrollment_date DATETIME NOT NULL,
        progress INT NOT NULL DEFAULT 0,
        UNIQUE KEY user_course (user_name, course_id)
    )";
    
    if (!$conn->query($create_table)) {
        die("Error creating table: " . $conn->error);
    }
}

// Enroll the user
$enroll_sql = "INSERT INTO user_enrollments (user_name, course_id, enroll_date, progress) VALUES (?, ?, NOW(), 0)";
$stmt = $conn->prepare($enroll_sql);

// Check if statement preparation was successful
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error . " - SQL: " . $enroll_sql);
}

$stmt->bind_param("si", $username, $course_id);

if ($stmt->execute()) {
    // Successful enrollment
    echo "<script>
        alert('Successfully enrolled in: " . htmlspecialchars($course_title) . "');
        window.location.href = 'my_courses.php';
    </script>";
} else {
    // Failed enrollment
    echo "<script>
        alert('Failed to enroll in the course: " . $stmt->error . "');
        window.location.href = 'start_course.php?course_id=" . $course_id . "';
    </script>";
}

$stmt->close();
$conn->close();
?>