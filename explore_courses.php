<?php
include "connect.php";
session_start();
// Check if user is logged in
if (!isset($_SESSION['fName'])) {
    header("Location: register.php");
    exit();
}

$username = $_SESSION['fName'];
$coursesData = [ 
    'Business & Management' => [
        'description' => 'Boss it in business with our specialist upskilling courses, industry certifications and high-flying degrees.',
        'quote' => '"The course was beautifully conceptualised, and well presented. The videos were lucid, clear, articulate and informative."',
        'quoteAuthor' => '- Charles, UK',
        'courses' => [
            [
                'title' => 'Starting a Business 5: Managing Finances',
                'description' => 'Find out how forecasting and managing your finances can lead to a profitable and sustainable business.',
                'badge' => 'Short Course',
                'image' => 'https://view.vzaar.com/5654116/image'
            ],
            [
                'title' => 'Exploring Instructional Leadership in Education',
                'description' => 'Learn how instructional leadership can motivate staff, transform teaching and learning, and help students to succeed.',
                'badge' => 'Short Course',
                'image' => 'https://media.istockphoto.com/id/1783743772/photo/female-speaker-giving-a-presentation-during-business-seminar-at-convention-center.jpg?s=612x612&w=0&k=20&c=T0Sit9sSbrafPXlY0vjadvEf-dyI8-t4uTY5W1TFzWU='
            ]
        ]
    ],
    'Healthcare & Medicine' => [
        'description' => 'Advance your healthcare knowledge with expert-led courses from leading medical professionals and institutions.',
        'quote' => '"An incredible learning experience that deepened my understanding of modern medical practices."',
        'quoteAuthor' => '- Sarah, Nurse Practitioner',
        'courses' => [
            [
                'title' => 'Mental Health First Aid',
                'description' => 'Learn essential skills to support and recognize mental health challenges in your community.',
                'badge' => 'Popular Course',
                'image' => 'https://media.istockphoto.com/id/149344746/photo/mental-health-first-aid.jpg?s=612x612&w=0&k=20&c=ofU7i1u3BVelaPAjI-6KDrzUCUCzQ3uZdWkQr_SWZdY='
            ],
            [
                'title' => 'Nutrition and Wellness Fundamentals',
                'description' => 'Explore comprehensive approaches to nutrition, diet, and holistic health management.',
                'badge' => 'New Course',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTjM6O1ZmDUpWxka-kixiPKac8vQQcMEQrPvQ&s'
            ]
        ]
    ],
    'Teaching' => [
        'description' => 'Empower your teaching career with cutting-edge pedagogical techniques and educational technologies.',
        'quote' => '"These courses transformed my approach to education and classroom management."',
        'quoteAuthor' => '- Michael, Educator',
        'courses' => [
            [
                'title' => 'Digital Learning Strategies',
                'description' => 'Master online and hybrid teaching methods for the modern educational landscape.',
                'badge' => 'Best Seller',
                'image' => 'https://kajabi-storefronts-production.kajabi-cdn.com/kajabi-storefronts-production/file-uploads/blogs/20044/images/545caba-24-ed1c-4e38-d8a7571c7e_pexels-mikael-blomkvist-6476582.jpg'
            ],
            [
                'title' => 'Inclusive Classroom Practices',
                'description' => 'Learn strategies to create supportive and inclusive learning environments for all students.',
                'badge' => 'Short Course',
                'image' => 'https://inclusive-solutions.com/wp-content/uploads/2021/08/pexels-photo-4019754.jpeg'
            ]
        ]
    ],
    'Tech & IT' => [
        'description' => 'Stay ahead in the fast-evolving world of technology with cutting-edge programming and IT courses.',
        'quote' => '"These courses provided practical skills that immediately enhanced my professional capabilities."',
        'quoteAuthor' => '- Alex, Software Developer',
        'courses' => [
            [
                'title' => 'Full Stack Web Development',
                'description' => 'Comprehensive training in modern web development technologies and frameworks.',
                'badge' => 'Intensive Course',
                'image' => 'https://d2ms8rpfqc4h24.cloudfront.net/Guide_to_Full_Stack_Development_000eb0b2d0.jpg'
            ],
            [
                'title' => 'Machine Learning Fundamentals',
                'description' => 'Introduction to machine learning algorithms and practical applications.',
                'badge' => 'Advanced Course',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYG1Y4iDl6qyTHO4ZogVk5aT-d1qlru1xreg&s'
            ]
        ]
    ],
    'Psychology & Mental Health' => [
        'description' => 'Explore the complexities of human behavior and mental well-being through expert-led courses.',
        'quote' => '"A transformative journey into understanding human psychology and mental health."',
        'quoteAuthor' => '- Emma, Counselor',
        'courses' => [
            [
                'title' => 'Cognitive Behavioral Therapy Basics',
                'description' => 'Learn fundamental techniques of CBT for personal and professional growth.',
                'badge' => 'Recommended',
                'image' => 'https://www.alphaacademy.org/wp-content/uploads/2020/04/Cognitive-Behavioural-Therapy-CBT-Techniques.png'
            ],
            [
                'title' => 'Emotional Intelligence Mastery',
                'description' => 'Develop advanced skills in understanding and managing emotions.',
                'badge' => 'Short Course',
                'image' => 'https://process.fs.teachablecdn.com/ADNupMnWyR7kCWRvm76Laz/resize=width:705/https://www.filepicker.io/api/file/VgU1IWkSPJJITg8oygOw'
            ]
        ]
    ],
    'Science, Engineering & Maths' => [
        'description' => 'Dive deep into scientific discovery, engineering innovations, and mathematical reasoning.',
        'quote' => '"Rigorous, challenging, and incredibly insightful courses that push the boundaries of knowledge."',
        'quoteAuthor' => '- David, Research Scientist',
        'courses' => [
            [
                'title' => 'Advanced Data Science',
                'description' => 'Comprehensive training in statistical analysis and data interpretation.',
                'badge' => 'Advanced Course',
                'image' => 'https://anexas.net/wp-content/uploads/2024/09/data-sicence-adv-min.png'
            ],
            [
                'title' => 'Engineering Design Principles',
                'description' => 'Learn cutting-edge design methodologies and innovative problem-solving.',
                'badge' => 'Professional Development',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT01YI49hPAL3ifbunmGNlRGssHkvuF1DQXA-cyRhwF8cRqb0kexPO7t7TavGHq3nZ-tvU&usqp=CAU'
            ]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Courses - LearnSphere</title>
    <style>
       :root {
    --primary-color: #4a4de6;
    --secondary-color: #ff6b6b;
    --text-primary: #2c3e50;
    --text-secondary: #7f8c8d;
    --background-light: #f4f6f7;
    --white: #ffffff;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    line-height: 1.6;
    color: var(--text-primary);
    background:linear-gradient(to right,white,rgb(255, 197, 207),rgb(253, 236, 204),white);
}

.gradient-header {
    background: linear-gradient(135deg, var(--primary-color), #6a11cb);
    color: white;
    position: sticky;
    top: 0;
    z-index: 100;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 50px;
    max-width: 1300px;
    margin: 0 auto;
}

.logo {
   width: 45px;
   height: 35px;
}

.nav-menu {
    display: flex;
    gap: 30px;
}

.nav-link {
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: rgba(255,255,255,0.7);
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.btn {
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-outline {
    border: 2px solid white;
    color: white;
}

.btn-primary {
    background-color: var(--secondary-color);
    color: white;
    box-shadow: 0 4px 15px rgba(255,107,107,0.4);
}
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 2rem auto;
        }
        .category {
            margin-bottom: 3rem;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .category h2 {
            margin-top: 0;
            color: #2c3e50;
        }
        .quote {
            font-style: italic;
            margin: 1rem 0;
            color: #555;
        }
        .courses {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        .course {
            background: #fafafa;
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 10px;
            transition: transform 0.2s ease-in-out;
        }
        .course:hover {
            transform: scale(1.02);
        }
        .course img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 8px;
        }
        .course h3 {
            margin: 0.5rem 0;
            font-size: 1.1rem;
            color: #333;
        }
        .badge {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
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
<header class="gradient-header">
        <nav class="navbar">
            <div class="container">
                <div class="logo-container">
                        <h1>LearnSphere</h1>
                   
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
<div class="container">
    <?php foreach ($coursesData as $categoryName => $category): ?>
        <div class="category">
            <h2><?= $categoryName ?></h2>
            <p><?= $category['description'] ?></p>
            <p class="quote"><?= $category['quote'] ?><br><strong><?= $category['quoteAuthor'] ?></strong></p>

            <div class="courses">
                <?php foreach ($category['courses'] as $course): ?>
                    <div class="course">
                        <img src="<?= $course['image'] ?>" alt="<?= $course['title'] ?>">
                        <span class="badge"><?= $course['badge'] ?></span>
                        <h3><?= $course['title'] ?></h3>
                        <p><?= $course['description'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
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
</body>
</html>
