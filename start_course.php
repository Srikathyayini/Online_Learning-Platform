<?php
session_start();
include "connect.php";

// Check if course_id is passed
if (!isset($_GET['course_id'])) {
    die("No course selected");
}

$course_id = intval($_GET['course_id']);

// Fetch course details with specific fields
$sql = "SELECT title, description, image, badge, duration, enrolled FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Course not found");
}

$course = $result->fetch_assoc();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['fName']);
$is_enrolled = false;

// Check if the user is already enrolled in this course
if ($is_logged_in && isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $check_enrollment = "SELECT * FROM user_enrollments WHERE username = ? AND course_id = ?";
    $stmt = $conn->prepare($check_enrollment);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $enrollment_result = $stmt->get_result();
    $is_enrolled = ($enrollment_result->num_rows > 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - FutureLearn</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="homestyle.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .course-header {
            background: linear-gradient(135deg, #ff1493, #8a2be2);
            color: white;
            padding: 50px 0;
            text-align: center;
        }
        .course-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        .course-details {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .course-sidebar {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .course-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .section-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid #f1f1f1;
        }
        .section-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .section-tab.active {
            border-bottom-color: #ff1493;
            color: #ff1493;
        }
        .course-section {
            display: none;
        }
        .course-section.active {
            display: block;
        }
        .enroll-now-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #ff1493;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
        }
        .continue-learning-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #8a2be2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
        }
        .enroll-now-btn:hover {
            background: #e60073;
        }
        .continue-learning-btn:hover {
            background: #7a1ec1;
        }
        .course-badge-display {
            display: inline-block;
            background: #ff1493;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            flex: 1;
            margin: 0 5px;
        }
        .stat-item i {
            font-size: 24px;
            color: #ff1493;
            margin-bottom: 10px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
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
                        <img src="futurelearn-logo-white.svg" alt="FutureLearn Logo" class="logo">
                    </a>
                </div>
                <div class="nav-menu">
                    <a href="home2.php" class="nav-link">Explore Courses</a>
                    <?php if ($is_logged_in): ?>
                        <a href="my_courses.php" class="nav-link">My Courses</a>
                    <?php else: ?>
                        <a href="#" class="nav-link">Learning Paths</a>
                    <?php endif; ?>
                    <a href="#" class="nav-link">For Business</a>
                    <a href="#" class="nav-link">Enterprise</a>
                </div>
                <div class="nav-actions">
                    <a href="#" class="search-trigger">
                        <i class="fas fa-search"></i>
                    </a>

                    <?php if ($is_logged_in): ?>
                        <!-- Show Username if logged in -->
                        <span class="username">Welcome,<b> <?php echo htmlspecialchars($_SESSION['fName']); ?>!</b></span>
                        <a href="logout.php" class="btn btn-outline">Logout</a>
                    <?php else: ?>
                        <!-- Show Login button if not logged in -->
                        <a href="register.html" class="btn btn-outline">Log In</a>
                        <a href="register.php" class="btn btn-primary">Start Learning</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- Course Header -->
    <div class="course-header">
        <h1><?php echo htmlspecialchars($course['title']); ?></h1>
        <p><?php echo htmlspecialchars($course['description']); ?></p>
    </div>

    <!-- Course Content -->
    <div class="course-container">
        <div class="course-details">
            <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-image">
            
            <div class="section-tabs">
                <div class="section-tab active" data-section="overview">Overview</div>
                <div class="section-tab" data-section="what-youll-learn">What You'll Learn</div>
                <div class="section-tab" data-section="requirements">Requirements</div>
            </div>

            <div id="overview" class="course-section active">
                <h2>Course Overview</h2>
                <p><?php echo htmlspecialchars($course['description']); ?></p>
                
                <div class="stats-container">
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <div class="stat-value"><?php echo htmlspecialchars($course['enrolled']); ?>+</div>
                        <div class="stat-label">Students Enrolled</div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-clock"></i>
                        <div class="stat-value"><?php echo htmlspecialchars($course['duration']); ?></div>
                        <div class="stat-label">Course Duration</div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-certificate"></i>
                        <div class="stat-value">Yes</div>
                        <div class="stat-label">Certificate</div>
                    </div>
                </div>
                
                <h3>About This Course</h3>
                <p>This comprehensive course is designed to provide you with practical knowledge and skills that you can apply immediately. Whether you're a beginner or looking to refresh your skills, this course offers valuable insights and hands-on experience.</p>
            </div>

            <div id="what-youll-learn" class="course-section">
                <h2>What You'll Learn</h2>
                <ul>
                    <li>Master the core concepts and principles of <?php echo htmlspecialchars($course['title']); ?></li>
                    <li>Develop practical skills through hands-on exercises and real-world projects</li>
                    <li>Learn best practices and industry standards</li>
                    <li>Gain confidence to apply your knowledge in professional settings</li>
                    <li>Understand how to solve common problems and challenges in this field</li>
                </ul>
            </div>

            <div id="requirements" class="course-section">
                <h2>Requirements</h2>
                <ul>
                    <li>No prior experience required - this course is suitable for beginners</li>
                    <li>Basic computer literacy and internet connection</li>
                    <li>Dedication and willingness to learn new concepts</li>
                    <li>Approximately 2-3 hours per week to complete course materials</li>
                </ul>
            </div>
        </div>

        <div class="course-sidebar">
            <div class="course-badge-display"><?php echo htmlspecialchars($course['badge']); ?></div>
            
            <h3>Course Details</h3>
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
            <p><strong>Enrolled Students:</strong> <?php echo htmlspecialchars($course['enrolled']); ?>+</p>
            <p><strong>Level:</strong> Beginner to Intermediate</p>
            <p><strong>Certificate:</strong> Available upon completion</p>

            <?php if ($is_logged_in): ?>
                <?php if ($is_enrolled): ?>
                    <a href="course_module.php?course_id=<?php echo $course_id; ?>" class="continue-learning-btn">Continue Learning</a>
                <?php else: ?>
                    <a href="enroll.php?course_id=<?php echo $course_id; ?>" class="enroll-now-btn">Enroll Now</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="register.php?redirect=start_course.php?course_id=<?php echo $course_id; ?>" class="enroll-now-btn">Register to Enroll</a>
            <?php endif; ?>
            
            <div style="margin-top: 30px;">
                <h4>Share This Course</h4>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <a href="#" style="color: #3b5998;"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" style="color: #1da1f2;"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" style="color: #0077b5;"><i class="fab fa-linkedin fa-lg"></i></a>
                    <a href="#" style="color: #ff4500;"><i class="fab fa-reddit fa-lg"></i></a>
                </div>
            </div>
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
                <p>&copy; 2025 FutureLearn. All Rights Reserved.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Tab switching logic
            const tabs = document.querySelectorAll('.section-tab');
            const sections = document.querySelectorAll('.course-section');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs and sections
                    tabs.forEach(t => t.classList.remove('active'));
                    sections.forEach(s => s.classList.remove('active'));

                    // Add active class to clicked tab and corresponding section
                    tab.classList.add('active');
                    document.getElementById(tab.dataset.section).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>