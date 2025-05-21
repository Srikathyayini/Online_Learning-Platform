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

// Check if user is enrolled in this course
$check_enrollment = "SELECT progress FROM user_enrollments WHERE user_name = ? AND course_id = ?";
$stmt = $conn->prepare($check_enrollment);
$stmt->bind_param("si", $username, $course_id);
$stmt->execute();
$enrollment_result = $stmt->get_result();

if ($enrollment_result->num_rows === 0) {
    header("Location: start_course.php?course_id=" . $course_id);
    exit();
}

$enrollment = $enrollment_result->fetch_assoc();
$current_progress = $enrollment['progress'];

// Fetch course details
$sql = "SELECT title, description, image FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Course not found");
}

$course = $result->fetch_assoc();

// Sample modules with descriptions
$modules = [
    [
        'id' => 1,
        'title' => 'Introduction',
        'duration' => '15 minutes',
        'completed' => ($current_progress >= 20),
        'description' => 'This module provides an overview of the course, outlining the key objectives and what you will learn throughout the journey. It sets the foundation for understanding the subject matter.',
        'progress_value' => 20
    ],
    [
        'id' => 2,
        'title' => 'Core Concepts',
        'duration' => '45 minutes',
        'completed' => ($current_progress >= 40),
        'description' => 'Dive into the fundamental concepts that form the backbone of this course. This module covers essential theories and principles needed for mastery.',
        'progress_value' => 40
    ],
    [
        'id' => 3,
        'title' => 'Practical Applications',
        'duration' => '60 minutes',
        'completed' => ($current_progress >= 60),
        'description' => 'Learn how to apply the concepts you’ve studied to real-world scenarios. This module includes examples and case studies to solidify your understanding.',
        'progress_value' => 60
    ],
    [
        'id' => 4,
        'title' => 'Advanced Techniques',
        'duration' => '50 minutes',
        'completed' => ($current_progress >= 80),
        'description' => 'Take your skills to the next level with advanced methods and techniques. This module explores complex topics and strategies for deeper expertise.',
        'progress_value' => 80
    ],
    [
        'id' => 5,
        'title' => 'Project & Assessment',
        'duration' => '90 minutes',
        'completed' => ($current_progress >= 100),
        'description' => 'Complete a practical project to demonstrate your skills. This module includes a detailed assessment to evaluate your learning outcomes.',
        'progress_value' => 100
    ]
];

// Get current active module
$active_module_index = 0;
if (isset($_GET['module'])) {
    $active_module_index = intval($_GET['module']);
} else {
    foreach ($modules as $index => $module) {
        if (!$module['completed']) {
            $active_module_index = $index;
            break;
        }
    }
}

// Handle module completion
if (isset($_POST['complete_module'])) {
    $module_index = intval($_POST['module_index']);
    $new_progress = $modules[$module_index]['progress_value'];

    if ($new_progress > $current_progress) {
        $update_sql = "UPDATE user_enrollments SET progress = ? WHERE user_name = ? AND course_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("isi", $new_progress, $username, $course_id);

        if ($stmt->execute()) {
            $current_progress = $new_progress;
            foreach ($modules as $index => &$module) {
                $module['completed'] = ($current_progress >= $module['progress_value']);
            }
            $success_message = "Module marked as complete. Progress updated to " . $current_progress . "%";
        } else {
            $error_message = "Failed to update progress: " . $conn->error;
        }
    }
}

// Check if course is 100% complete
$course_completed = ($current_progress == 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Learning Module</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        /* Header Styles */
        .gradient-header {
            background: linear-gradient(135deg, #ff1493, #8a2be2);
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .logo-container img {
            height: 40px;
            width: auto;
        }
        .nav-menu {
            display: flex;
            gap: 20px;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #ffd700;
        }
        .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #ffd700;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .search-trigger {
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
        }
        .username {
            color: white;
            font-size: 1rem;
        }
        .btn.btn-outline {
            padding: 8px 20px;
            background: transparent;
            border: 1px solid white;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease, color 0.3s ease;
        }
        .btn.btn-outline:hover {
            background: white;
            color: #8a2be2;
        }

        /* Module Content Styles */
        .module-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 30px;
        }
        @media (max-width: 768px) {
            .module-container { grid-template-columns: 1fr; }
        }
        .module-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .module-sidebar {
            background: white;
            border-radius: 10px;
            padding: 20px;
            height: fit-content;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .course-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .course-thumbnail {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 20px;
        }
        .progress-container {
            background: #f1f1f1;
            border-radius: 5px;
            height: 8px;
            overflow: hidden;
            margin: 15px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ff1493, #8a2be2);
            border-radius: 5px;
            transition: width 0.5s ease;
        }
        .module-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .module-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .module-item:hover {
            background-color: #f5f5f5;
        }
        .module-item:last-child {
            border-bottom: none;
        }
        .module-info {
            flex: 1;
        }
        .module-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .module-duration {
            font-size: 0.9rem;
            color: #777;
        }
        .module-status {
            display: flex;
            align-items: center;
            margin-left: 15px;
        }
        .module-status.completed { color: #4CAF50; }
        .module-status.locked { color: #999; }
        .module-btn {
            background: #8a2be2;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .module-btn:hover { background: #7a1ec1; }
        .module-btn:disabled { background: #ccc; cursor: not-allowed; }
        .course-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .return-btn {
            background: #ff1493;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .return-btn:hover { background: #e60073; }
        .description-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: #333;
        }
        .course-complete-banner {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            animation: fadeIn 0.5s ease-in-out;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .module-thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 5px;
            margin-right: 15px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #666;
            flex-shrink: 0;
        }

        /* Footer Styles */
        .site-footer {
            background: #333;
            color: #fff;
            padding: 40px 0;
            margin-top: 40px;
        }
        .site-footer .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .footer-columns {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        @media (max-width: 768px) {
            .footer-columns { grid-template-columns: 1fr; }
        }
        .footer-column h4 {
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .footer-column ul {
            list-style: none;
            padding: 0;
        }
        .footer-column ul li {
            margin-bottom: 10px;
        }
        .footer-column ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer-column ul li a:hover {
            color: #ff1493;
        }
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #444;
        }
        @media (max-width: 768px) {
            .footer-bottom { flex-direction: column; gap: 15px; }
        }
        .footer-bottom p {
            margin: 0;
            font-size: 0.9rem;
        }
        .social-links {
            display: flex;
            gap: 15px;
        }
        .social-links a {
            color: #fff;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        .social-links a:hover {
            color: #ff1493;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <header class="gradient-header">
        <nav class="navbar">
            <div class="container">
                <div class="logo-container">
                    <a href="index.php">
                        <img src="https://www.fenews.co.uk/wp-content/uploads/2020/04/FutureLearn_Apr2020.jpg" alt="FutureLearn Logo" class="logo">
                    </a>
                </div>
                <div class="nav-menu">
                    <a href="home2.php" class="nav-link">Explore Courses</a>
                    <a href="my_courses.php" class="nav-link active">My Courses</a>
                    <a href="#" class="nav-link">Learning Paths</a>
                    <a href="#" class="nav-link">For Business</a>
                </div>
                <div class="nav-actions">
                    <a href="#" class="search-trigger"><i class="fas fa-search"></i></a>
                    <span class="username">Welcome, <b><?php echo htmlspecialchars($_SESSION['fName']); ?>!</b></span>
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Course Module Content -->
    <div class="module-container">
        <div class="module-content">
            <div class="course-header">
                <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-thumbnail">
                <div>
                    <h1><?php echo htmlspecialchars($course['title']); ?></h1>
                    <p><?php echo htmlspecialchars($course['description']); ?></p>
                </div>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($course_completed): ?>
                <div class="course-complete-banner">
                    <h2><i class="fas fa-award"></i> Congratulations!</h2>
                    <p>You have successfully completed <strong><?php echo htmlspecialchars($course['title']); ?></strong>!</p>
                    <a href="#" class="return-btn" style="background: #45a049; margin-top: 10px;">
                        <i class="fas fa-certificate"></i> Download Certificate
                    </a>
                </div>
            <?php endif; ?>

            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo $current_progress; ?>%"></div>
            </div>
            <p><strong><?php echo $current_progress; ?>% Complete</strong></p>

            <!-- Current Module Description -->
            <div class="current-module">
                <h2><?php echo htmlspecialchars($modules[$active_module_index]['title']); ?></h2>
                <div class="description-container">
                    <p><?php echo htmlspecialchars($modules[$active_module_index]['description']); ?></p>
                </div>

                <?php if (!$modules[$active_module_index]['completed']): ?>
                    <form method="post" id="complete-form">
                        <input type="hidden" name="module_index" value="<?php echo $active_module_index; ?>">
                        <button type="submit" name="complete_module" class="module-btn">
                            <i class="fas fa-check"></i> Mark Module as Complete
                        </button>
                    </form>
                <?php else: ?>
                    <div style="color: #4CAF50;">
                        <i class="fas fa-check-circle"></i> You've completed this module
                    </div>
                <?php endif; ?>
            </div>

            <h3 style="margin-top: 30px;">All Modules</h3>
            <div class="module-list">
                <?php foreach ($modules as $index => $module): ?>
                    <div class="module-item" onclick="<?php echo ($index > 0 && !$modules[$index-1]['completed']) ? '' : "loadModule($index)"; ?>">
                        <div class="module-thumbnail">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="module-info">
                            <div class="module-title"><?php echo htmlspecialchars($module['title']); ?></div>
                            <div class="module-duration"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($module['duration']); ?></div>
                        </div>
                        <div class="module-status <?php echo $module['completed'] ? 'completed' : ($index > 0 && !$modules[$index-1]['completed'] ? 'locked' : ''); ?>">
                            <?php if ($module['completed']): ?>
                                <i class="fas fa-check-circle"></i>
                            <?php elseif ($index > 0 && !$modules[$index-1]['completed']): ?>
                                <i class="fas fa-lock"></i>
                            <?php else: ?>
                                <i class="fas fa-arrow-right"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="course-actions">
                <a href="my_courses.php" class="return-btn">Return to My Courses</a>
                <?php if ($current_progress == 100): ?>
                    <a href="#" class="return-btn" style="background: #4CAF50;">Download Certificate</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="module-sidebar">
            <h3>Your Progress</h3>
            <div class="progress-container">
                <div class="progress-bar" id="main-progress-bar" style="width: <?php echo $current_progress; ?>%"></div>
            </div>
            <p><strong><span id="progress-percentage"><?php echo $current_progress; ?></span>% Complete</strong></p>

            <h3>Current Module</h3>
            <p><strong id="current-module-title"><?php echo htmlspecialchars($modules[$active_module_index]['title']); ?></strong></p>
            <p><i class="fas fa-clock"></i> <span id="current-module-duration"><?php echo htmlspecialchars($modules[$active_module_index]['duration']); ?></span></p>

            <h3>Course Resources</h3>
            <ul style="padding-left: 20px;">
                <li><a href="#">Course Materials</a></li>
                <li><a href="#">Supplementary Resources</a></li>
                <li><a href="#">Discussion Forum</a></li>
                <li><a href="#">Help & Support</a></li>
            </ul>

            <h3>Need Help?</h3>
            <p>Contact our support team for assistance with this course.</p>
            <a href="#" style="display: block; text-align: center; margin-top: 10px; color: #8a2be2;">
                <i class="fas fa-headset"></i> Contact Support
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-columns">
                <div class="footer-column">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#">All Courses</a></li>
                        <li><a href="#">Learning Paths</a></li>
                        <li><a href="#">Enterprise Solutions</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 FutureLearn. All Rights Reserved.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <?php if ($course_completed): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvas-confetti/1.4.0/confetti.browser.min.js"></script>
    <script>
        window.onload = function() {
            var duration = 5 * 1000;
            var end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 7,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#ff1493', '#8a2be2', '#4CAF50']
                });
                confetti({
                    particleCount: 7,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#ff1493', '#8a2be2', '#4CAF50']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        };
    </script>
    <?php endif; ?>

    <script>
        function loadModule(index) {
            window.location.href = `course_module.php?course_id=<?php echo $course_id; ?>&module=${index}`;
        }
    </script>
</body>
</html>