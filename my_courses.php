<?php
session_start();
include "connect.php";

// Check if user is logged in
if (!isset($_SESSION['fName']) ) {
    header("Location: register.html");
    exit();
}

$userid = intval($_SESSION['fName']); // Use user_id from session, not fName

// Handle course deletion
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $course_id = intval($_POST['id']);
    
    $delete_sql = "DELETE FROM user_enrollments WHERE user_name = ? AND course_id = ?";
    $stmt = $conn->prepare($delete_sql);
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
        exit();
    }
    
    $stmt->bind_param("ii", $userid, $course_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Enrollment deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete enrollment: ' . $stmt->error]);
    }
    
    $stmt->close();
    exit();
}

// Fetch all courses the user is enrolled in
$sql = "SELECT c.id, c.title, c.description, c.image, c.badge, c.duration, 
               ue.enroll_date, ue.progress
        FROM courses c
        JOIN user_enrollments ue ON c.id = ue.course_id
        WHERE ue.user_name = ?
        ORDER BY ue.enroll_date DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$username = $_SESSION['fName']; // Or whatever session variable stores the username
$stmt->bind_param("s", $username); 
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - FutureLearn</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="homestyle.css">
    <style>
        /* Your existing CSS remains unchanged */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .page-header {
            background: linear-gradient(135deg, #ff1493, #8a2be2);
            color: white;
            padding: 50px 0;
            text-align: center;
        }
        .courses-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        .course-card {
            background: white;
            border-radius 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .course-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .course-content {
            padding: 20px;
        }
        .course-badge {
            display: inline-block;
            background: #ff1493;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        .course-title {
            font-size: 1.2rem;
            margin: 0 0 10px 0;
        }
        .course-description {
            color: #666;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .progress-bar {
            height: 8px;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, #ff1493, #8a2be2);
            border-radius: 4px;
        }
        .course-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #666;
        }
        .continue-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #ff1493;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
        }
        .continue-btn:hover {
            background: #e60073;
        }
        .delete-btn {
            background: #e60073;
        }
        .delete-btn:hover {
            background: #c4005f;
        }
        .no-courses {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .no-courses i {
            font-size: 4rem;
            color: #ff1493;
            margin-bottom: 20px;
        }
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
                    <a href="home2.php" class="nav-link">Home</a>
                    <a href="my_courses.php" class="nav-link active">My Courses</a>
                    <a href="#" class="nav-link">For Business</a>
                    <a href="#" class="nav-link">Enterprise</a>
                </div>
                <div class="nav-actions">
                    <a href="#" class="search-trigger">
                        <i class="fas fa-search"></i>
                    </a>
                    <span class="username">Welcome,<b> <?php echo htmlspecialchars($_SESSION['fName']); ?>!</b></span>
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <h1>My Courses</h1>
        <p>Track your learning progress and continue where you left off</p>
    </div>

    <!-- Courses Container -->
    <div class="courses-container">
        <?php if ($result->num_rows > 0): ?>
            <div class="course-grid">
                <?php while($course = $result->fetch_assoc()): ?>
                    <div class="course-card">
                        <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-image">
                        <div class="course-content">
                            <div class="course-badge"><?php echo htmlspecialchars($course['badge']); ?></div>
                            <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                            <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                            
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $course['progress']; ?>%"></div>
                            </div>
                            
                            <div class="course-meta">
                                <span><?php echo $course['progress']; ?>% Complete</span>
                                <span><?php echo htmlspecialchars($course['duration']); ?></span>
                            </div>
                            
                            <a href="course_module.php?course_id=<?php echo $course['id']; ?>" class="continue-btn">Continue Learning</a>
                            <button onclick="deleteCourse(<?php echo $course['id']; ?>)" class="continue-btn delete-btn">Delete Enrollment</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-courses">
                <i class="fas fa-book-reader"></i>
                <h2>You haven't enrolled in any courses yet</h2>
                <p>Explore our course catalog and find the perfect course to start your learning journey</p>
                <a href="home2.php#course_categories" class="continue-btn">Explore Courses</a>
            </div>
        <?php endif; ?>
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


    <script>
        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course enrollment?')) {
                fetch('my_courses.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `delete=1&id=${courseId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Refresh the page to update the course list
                    } else {
                        alert('Delete failed: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('An error occurred: ' + error);
                });
            }
        }
   
       
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>