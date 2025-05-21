<?php
include "connect.php";



// Get username from URL parameter
if (!isset($_GET['username'])) {
    header("Location: index.php");
    exit();
}

$userName = $_GET['username'];
$sql = "SELECT e.course_id, e.enroll_date, e.progress, c.title, c.badge, c.image, c.duration 
        FROM user_enrollments e
        LEFT JOIN courses c ON e.course_id = c.id
        WHERE e.user_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();

// Get overall progress statistics
$totalCourses = 0;
$completedCourses = 0;
$totalProgress = 0;

$enrollments = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $enrollments[] = $row;
        $totalCourses++;
        $totalProgress += $row["progress"];
        if ($row["progress"] == 100) {
            $completedCourses++;
        }
    }
}


$averageProgress = $totalCourses > 0 ? round($totalProgress / $totalCourses) : 0;
$completionRate = $totalCourses > 0 ? round(($completedCourses / $totalCourses) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($userName); ?>'s Progress</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .progress-dashboard {
            padding: 40px 0;
            background-color: #f9f9f9;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        
        .progress-overview {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .progress-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card:nth-child(2) {
            color: #fff;
            background: linear-gradient(135deg, #42e695, #3bb2b8);
        }
        
        .stat-card:nth-child(3) {
            background: linear-gradient(135deg, #ffb199, #ff0844);
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .overall-progress {
            margin-bottom: 40px;
        }
        
        .progress-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #555;
        }
        
        .progress-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 20px;
            height: 24px;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .progress-bar {
            height: 24px;
            border-radius: 20px;
            text-align: center;
            line-height: 24px;
            color: white;
            font-size: 14px;
            font-weight: bold;
            transition: width 1.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
            position: relative;
        }
        
        .progress-bar::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, 
                        rgba(255,255,255,0.15) 0%, 
                        rgba(255,255,255,0.05) 50%, 
                        rgba(255,255,255,0.15) 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
        }
        
        @keyframes shimmer {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }
        
        .status-completed {
            background: linear-gradient(90deg, #42e695, #3bb2b8);
        }
        
        .status-in-progress {
            background: linear-gradient(90deg, #6e8efb, #a777e3);
        }
        
        .status-not-started {
            background: linear-gradient(90deg, #ffb199, #ff0844);
        }
        
        .courses-title {
            margin: 30px 0 20px;
            color: #333;
        }
        
        .enrolled-courses {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .course-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }
        
        .course-image {
            height: 180px;
            overflow: hidden;
            position: relative;
        }
        
        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .course-card:hover .course-image img {
            transform: scale(1.05);
        }
        
        .course-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .course-details {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .course-title {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #333;
            line-height: 1.4;
        }
        
        .course-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #777;
            font-size: 14px;
        }
        
        .course-duration {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .course-duration svg {
            width: 16px;
            height: 16px;
        }
        
        .course-status {
            font-weight: 600;
        }
        
        .completed {
            color: #42e695;
        }
        
        .in-progress {
            color: #6e8efb;
        }
        
        .not-started {
            color: #ff0844;
        }
        
        .course-progress {
            margin-top: auto;
        }
        
        .course-action {
            text-align: right;
            margin-top: 15px;
        }
        
        .btn-continue {
            background: linear-gradient(45deg, #6e8efb, #a777e3);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .no-courses {
            grid-column: 1/-1;
            text-align: center;
            padding: 50px 0;
            background: white;
            border-radius: 8px;
            color: #777;
        }
        
        .no-courses p {
            margin-bottom: 20px;
        }
        
        .btn-explore {
            background: linear-gradient(45deg, #6e8efb, #a777e3);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-explore:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .progress-stats {
                grid-template-columns: 1fr;
            }
            
            .enrolled-courses {
                grid-template-columns: 1fr;
            }
        }
      
        .back-btn {
            background-color: #f3f4f6;
            color: #4b5563;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            margin-right: 20px;
            display: inline-flex;
            align-items: center;
        }
        .back-btn:hover {
            background-color: #e5e7eb;
        }
        .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .user-avatar {
            background-color: #e0e7ff;
            color: #4f46e5;
            font-size: 30px;
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            text-align: center;
            margin-right: 20px;
        }
        .user-info {
            flex: 1;
        }
        .user-name {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 5px 0;
        }
        
        .progress-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 20px;
            height: 20px;
            margin-bottom: 30px;
        }
        .progress-bar {
            height: 20px;
            border-radius: 20px;
            text-align: center;
            line-height: 20px;
            color: white;
            font-size: 12px;
            font-weight: bold;
            transition: width 1s ease-in-out;
        }
        .enrollment-list {
            margin-top: 30px;
        }
        .enrollment-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            transition: transform 0.3s;
        }
        .enrollment-card:hover {
            transform: translateY(-2px);
        }
        .course-name {
            font-weight: 600;
            margin: 0 0 10px 0;
        }
        .enrollment-meta {
            display: flex;
            justify-content: space-between;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .status-completed {
            background-color: #4CAF50;
        }
        .status-in-progress {
            background-color: #2196F3;
        }
        .status-not-started {
            background-color: #F44336;
        }
        h2 {
            margin-top: 30px;
            color: #4b5563;
            font-weight: 600;
        }
        .no-enrollments {
            text-align: center;
            padding: 40px 0;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="admin_home.php" class="back-btn">← Back to Home</a>
            <h1><?php echo htmlspecialchars($userName); ?>'s Enrollments</h1>
        </div>
        <main>
        <section class="progress-dashboard">
            <div class="container">
                <h1 class="page-title">My Learning Progress</h1>
                
                <div class="progress-overview">
                    <div class="progress-stats">
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $totalCourses; ?></div>
                            <div class="stat-label">Total Courses</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $completedCourses; ?></div>
                            <div class="stat-label">Completed Courses</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $completionRate; ?>%</div>
                            <div class="stat-label">Completion Rate</div>
                        </div>
                    </div>
                    
                    <div class="overall-progress">
                        <h3 class="progress-title">Overall Learning Progress</h3>
                        <div class="progress-container">
                            <?php
                            $progressClass = "status-in-progress";
                            if ($averageProgress == 100) {
                                $progressClass = "status-completed";
                            } elseif ($averageProgress == 0) {
                                $progressClass = "status-not-started";
                            }
                            ?>
                            <div class="progress-bar <?php echo $progressClass; ?>" 
                                 style="width: <?php echo $averageProgress; ?>%">
                                <?php echo $averageProgress; ?>%
                            </div>
                        </div>
                    </div>
                </div>
                
                <h2 class="courses-title">My Enrolled Courses</h2>
                
                <?php if (count($enrollments) > 0): ?>
                    <div class="enrolled-courses">
                        <?php foreach ($enrollments as $enrollment): 
                            $progressValue = $enrollment["progress"];
                            $statusClass = "";
                            $statusText = "";
                            
                            if ($progressValue == 100) {
                                $statusClass = "completed";
                                $statusText = "Completed";
                            } elseif ($progressValue > 0) {
                                $statusClass = "in-progress";
                                $statusText = "In Progress";
                            } else {
                                $statusClass = "not-started";
                                $statusText = "Not Started";
                            }
                            
                            // Format date for better display
                            $date = new DateTime($enrollment["enroll_date"]);
                            $formattedDate = $date->format('M d, Y');
                            
                            // Use course title from database or fallback
                            $courseTitle = !empty($enrollment["title"]) ? 
                                htmlspecialchars($enrollment["title"]) : 
                                "Untitled Course #" . htmlspecialchars($enrollment["course_id"]);
                                
                            // Image handling
                            $courseImage = !empty($enrollment["image"]) ? 
                                htmlspecialchars($enrollment["image"]) : 
                                "https://via.placeholder.com/350x180?text=Course+Image";
                                
                            // Badge handling
                            $courseBadge = !empty($enrollment["badge"]) ? 
                                htmlspecialchars($enrollment["badge"]) : 
                                "";
                                
                            // Duration handling
                            $courseDuration = !empty($enrollment["duration"]) ? 
                                htmlspecialchars($enrollment["duration"]) : 
                                "Self-paced";
                        ?>
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="<?php echo $courseImage; ?>" alt="<?php echo $courseTitle; ?>">
                                    <?php if (!empty($courseBadge)): ?>
                                        <span class="course-badge"><?php echo $courseBadge; ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="course-details">
                                    <h3 class="course-title"><?php echo $courseTitle; ?></h3>
                                    
                                    <div class="course-duration">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <?php echo $courseDuration; ?>
                                    </div>
                                    
                                    <div class="course-meta">
                                        <span>Enrolled: <?php echo $formattedDate; ?></span>
                                        <span class="course-status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </div>
                                    
                                    <div class="course-progress">
                                        <div class="progress-container">
                                            <div class="progress-bar status-<?php echo strtolower(str_replace(' ', '-', $statusText)); ?>" 
                                                 style="width: <?php echo $progressValue; ?>%">
                                                <?php echo $progressValue; ?>%
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="course-action">
                                        <a href="course.php?id=<?php echo urlencode($enrollment['course_id']); ?>" class="btn-continue">
                                            <?php echo ($progressValue == 100) ? 'Review Course' : 'Continue Learning'; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="enrolled-courses">
                        <div class="no-courses">
                            <p>You haven't enrolled in any courses yet.</p>
                            <a href="index.php#featured_courses" class="btn-explore">Explore Courses</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
<?php
$conn->close();
?>