<?php
session_start(); // Start the session
include "connect.php";
// Check if the user is logged in
$is_logged_in = isset($_SESSION['fName']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FutureLearn - Transform Your Future</title>
    <link rel="stylesheet" href="homestyle.css">
</head>

<body>
    <header class="gradient-header">
        <nav class="navbar">
            <div class="container">
                <div class="logo-container">
                    <h1>LearnSphere</h1>
                </div>
                
                <div class="nav-menu">
                    <a href="#featured_courses" class="nav-link">Explore Courses</a>
                    <a href="my_courses.php" class="nav-link">My Courses</a>
                    <a href="user_path.php" class="nav-link">Learning Paths</a>
                    <a href="#content" class="nav-link">About</a>
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
                        <a href="register.html" class="btn btn-primary">Start Learning</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Unlock Your Potential, Anywhere</h1>
                    <p>Learn from world-class universities and industry experts. Transform your career with flexible, online courses.</p>
                    <div class="search-box">
                        <input type="text" placeholder="What skill do you want to learn?">
                        <button class="search-btn">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                <div class="hero-illustration">
                    <img src="https://kgrinstitutes.com/assets/Images/welcome-img.png" alt="Online Learning">
                </div>
            </div>
        </section>

        <section class="course-categories">
    <div class="container">
        <h2>Explore top subjects</h2>
        <div class="subject-tabs">
            <div class="subject-tab"><i class="fas fa-briefcase"></i> Business & Management</div>
            <div class="subject-tab"><i class="fas fa-heartbeat"></i> Healthcare & Medicine</div>
            <div class="subject-tab"><i class="fas fa-chalkboard-teacher"></i> Teaching</div>
            <div class="subject-tab"><i class="fas fa-laptop-code"></i> Tech & IT</div>
            <div class="subject-tab"><i class="fas fa-brain"></i> Psychology & Mental Health</div>
            <div class="subject-tab"><i class="fas fa-flask"></i> Science, Engineering & Maths</div>
        </div>

        <div class="subject-details">
            <div class="subject-description">
                <h3>Business & Management</h3>
                <p>Boss it in business with our specialist upskilling courses, industry certifications and high-flying degrees.</p>
                <blockquote>
                    "The course was beautifully conceptualised, and well presented. The videos were lucid, clear, articulate and informative."
                    <footer>- Charles, UK</footer>
                </blockquote>
                <a href="#" class="btn btn-primary">Explore courses</a>
            </div>
            <div class="subject-courses">
                <div class="course-card">
                    <div class="course-badge">Short Course</div>
                    <img src="https://via.placeholder.com/350x200" alt="Business Course">
                    <h4>Starting a Business 5: Managing Finances</h4>
                    <p>Find out how forecasting and managing your finances can lead to a profitable and sustainable business.</p>
                </div>
                <div class="course-card">
                    <div class="course-badge">Short Course</div>
                    <img src="https://via.placeholder.com/350x200" alt="Leadership Course">
                    <h4>Exploring Instructional Leadership in Education</h4>
                    <p>Learn how instructional leadership can motivate staff, transform teaching and learning, and help students to succeed.</p>
                </div>
            </div>
        </div>
    </div>
</section>


        

        <section class="featured-courses" id="featured_courses">
            <div class="container">
                <h1 style="font-size:40px">Trending Courses</h1>
                <div class="course-slider">
                    <div class="course-card">
                        <div class="course-image">
                            <div class="course-badge">New</div>
                        </div>
                        <div class="course-details">
                        
                            <div class="course-meta">
                            </div>
                        </div>
                   
            </div>
        </section>
        <section class="how-it-works">
            <h2 style="font-size: 40px;">How does it work?</h2>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" data-tab="short-courses">Short courses</button>
                <button class="tab" data-tab="expert-tracks">ExpertTracks</button>
                <button class="tab" data-tab="microcredentials">Microcredentials</button>
                <button class="tab" data-tab="online-degrees">Online degrees</button>
            </div>
    
            <!-- Content Box -->
            <div class="content-box" id="content">
                <div class="content active" id="short-courses">
                    <div class="step">
                        <h3>1</h3>
                        <h4>Choose a short course</h4>
                        <p>From introductory to advanced, you'll find high-quality courses across every subject, designed and taught by academic and industry experts.</p>
                    </div>
                    <div class="step">
                        <h3>2</h3>
                        <h4>Subscribe or upgrade</h4>
                        <p>Join FutureLearn Unlimited for long-term access to your course and a CV-ready certificate, or upgrade individually on each course.</p>
                    </div>
                    <div class="step">
                        <h3>3</h3>
                        <h4>Learn, connect and discuss</h4>
                        <p>Courses are divided into weeks and steps. You'll be able to connect with other learners throughout your learning journey.</p>
                    </div>
                    <div class="step">
                        <h3>4</h3>
                        <h4>Find your next course</h4>
                        <p>Now you've caught the bug, what will you learn next?</p>
                    </div>
                </div>
    
                <div class="content" id="expert-tracks">
                    <p>ExpertTracks content goes here...</p>
                </div>
                <div class="content" id="microcredentials">
                    <p>Microcredentials content goes here...</p>
                </div>
                <div class="content" id="online-degrees">
                    <p>Online degrees content goes here...</p>
                </div>
            </div>
        </section>
        <section class="university-partners">
            <div class="container">
                <h2>Learn with <span class="highlight">200+</span> world-class institutions and educators</h2>
                <div class="partners-logo-grid">
                    <div class="partner-logo">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvLqBw_MaWSW4ycrn4UHo4dlkiw972sMEdGA&s" alt="King's College London">
                    </div>
                    <div class="partner-logo">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSJiamh-6m7DTyINUsTgjq215A_dsKGk9Rvtw&s" alt="Cambridge">
                    </div>
                    <div class="partner-logo">
                        <img src="https://spaceology.io/wp-content/uploads/2021/09/UCL.png" alt="UCL">
                    </div>
                    <div class="partner-logo">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlJEV_NxpeqzEiHz0Dsu4QstykPSxjdqUktg&s" alt="University of Leeds">
                    </div>
                    <div class="partner-logo">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShMVWO1Occl4nWt7IhoFi-gf1lVP0UsVY24A&s" alt="CIPD">
                    </div>
                    <div class="partner-logo">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT062RJLV64LssTbOZfYVOhlU8zxxlg4_rF7w&s" alt="Bloomsbury">
                    </div>
                    <div class="partner-logo">
                        <img src="https://1000logos.net/wp-content/uploads/2021/12/Accenture-logo.jpg" alt="Accenture">
                    </div>
                    <div class="partner-logo">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSohs5hlMFIPtgkTEFwL86vp7NNwjiU7LwzFw&s" alt="NHS Health Education England">
                    </div>
                </div>
            </div>
        </section>
    </main>

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
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('.search-box input');
            const searchButton = document.querySelector('.search-btn');
        
            function performSearch() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    // Enhanced search functionality
                    console.log(`Searching for courses related to: ${searchTerm}`);
                    // You could add more sophisticated search logic here
                    showSearchResults(searchTerm);
                }
            }
        
            searchButton.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        
            function showSearchResults(term) {
                // Placeholder for search result display
                alert(`Exploring courses related to "${term}"`);
            }
        
            // Hover and interaction effects
            const categoryCards = document.querySelectorAll('.category-card');
            categoryCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-15px)';
                    card.style.boxShadow = '0 15px 40px rgba(0,0,0,0.15)';
                });
        
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                    card.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
                });
            });
        
            // Smooth scrolling for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        
            // Responsive menu toggle (basic implementation)
            const navToggle = document.createElement('button');
            navToggle.innerHTML = '<i class="fas fa-bars"></i>';
            navToggle.classList.add('nav-toggle');
            
            navToggle.addEventListener('click', () => {
                const navMenu = document.querySelector('.nav-menu');
                navMenu.classList.toggle('show-mobile');
            });
        
            // Add responsive considerations
            window.addEventListener('resize', () => {
                const navMenu = document.querySelector('.nav-menu');
                if (window.innerWidth > 768) {
                    navMenu.classList.remove('show-mobile');
                }
            });
        });
        document.addEventListener('DOMContentLoaded', () => {
            const subjectTabs = document.querySelectorAll('.subject-tab');
            const subjectDescription = document.querySelector('.subject-description');
            const subjectCourses = document.querySelector('.subject-courses');
        
            // Subject data
            const subjectContent = {
                'Business & Management': {
                    description: 'Boss it in business with our specialist upskilling courses, industry certifications and high-flying degrees.',
                    quote: '"The course was beautifully conceptualised, and well presented. The videos were lucid, clear, articulate and informative."',
                    quoteAuthor: '- Charles, UK',
                    courses: [
                        {
                            title: 'Starting a Business 5: Managing Finances',
                            description: 'Find out how forecasting and managing your finances can lead to a profitable and sustainable business.',
                            badge: 'Short Course',
                            image: 'https://view.vzaar.com/5654116/image'
                        },
                        {
                            title: 'Exploring Instructional Leadership in Education',
                            description: 'Learn how instructional leadership can motivate staff, transform teaching and learning, and help students to succeed.',
                            badge: 'Short Course',
                            image: 'https://media.istockphoto.com/id/1783743772/photo/female-speaker-giving-a-presentation-during-business-seminar-at-convention-center.jpg?s=612x612&w=0&k=20&c=T0Sit9sSbrafPXlY0vjadvEf-dyI8-t4uTY5W1TFzWU='
                        }
                    ]
                },
                'Healthcare & Medicine': {
                    description: 'Advance your healthcare knowledge with expert-led courses from leading medical professionals and institutions.',
                    quote: '"An incredible learning experience that deepened my understanding of modern medical practices."',
                    quoteAuthor: '- Sarah, Nurse Practitioner',
                    courses: [
                        {
                            title: 'Mental Health First Aid',
                            description: 'Learn essential skills to support and recognize mental health challenges in your community.',
                            badge: 'Popular Course',
                            image: 'https://media.istockphoto.com/id/149344746/photo/mental-health-first-aid.jpg?s=612x612&w=0&k=20&c=ofU7i1u3BVelaPAjI-6KDrzUCUCzQ3uZdWkQr_SWZdY='
                        },
                        {
                            title: 'Nutrition and Wellness Fundamentals',
                            description: 'Explore comprehensive approaches to nutrition, diet, and holistic health management.',
                            badge: 'New Course',
                            image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTjM6O1ZmDUpWxka-kixiPKac8vQQcMEQrPvQ&s'
                        }
                    ]
                },
                'Teaching': {
                    description: 'Empower your teaching career with cutting-edge pedagogical techniques and educational technologies.',
                    quote: '"These courses transformed my approach to education and classroom management."',
                    quoteAuthor: '- Michael, Educator',
                    courses: [
                        {
                            title: 'Digital Learning Strategies',
                            description: 'Master online and hybrid teaching methods for the modern educational landscape.',
                            badge: 'Best Seller',
                            image: 'https://kajabi-storefronts-production.kajabi-cdn.com/kajabi-storefronts-production/file-uploads/blogs/20044/images/545caba-24-ed1c-4e38-d8a7571c7e_pexels-mikael-blomkvist-6476582.jpg'
                        },
                        {
                            title: 'Inclusive Classroom Practices',
                            description: 'Learn strategies to create supportive and inclusive learning environments for all students.',
                            badge: 'Short Course',
                            image: 'https://inclusive-solutions.com/wp-content/uploads/2021/08/pexels-photo-4019754.jpeg'
                        }
                    ]
                },
                'Tech & IT': {
                    description: 'Stay ahead in the fast-evolving world of technology with cutting-edge programming and IT courses.',
                    quote: '"These courses provided practical skills that immediately enhanced my professional capabilities."',
                    quoteAuthor: '- Alex, Software Developer',
                    courses: [
                        {
                            title: 'Full Stack Web Development',
                            description: 'Comprehensive training in modern web development technologies and frameworks.',
                            badge: 'Intensive Course',
                            image: 'https://d2ms8rpfqc4h24.cloudfront.net/Guide_to_Full_Stack_Development_000eb0b2d0.jpg'
                        },
                        {
                            title: 'Machine Learning Fundamentals',
                            description: 'Introduction to machine learning algorithms and practical applications.',
                            badge: 'Advanced Course',
                            image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYG1Y4iDl6qyTHO4ZogVk5aT-d1qlru1xreg&s'
                        }
                    ]
                },
                'Psychology & Mental Health': {
                    description: 'Explore the complexities of human behavior and mental well-being through expert-led courses.',
                    quote: '"A transformative journey into understanding human psychology and mental health."',
                    quoteAuthor: '- Emma, Counselor',
                    courses: [
                        {
                            title: 'Cognitive Behavioral Therapy Basics',
                            description: 'Learn fundamental techniques of CBT for personal and professional growth.',
                            badge: 'Recommended',
                            image: 'https://www.alphaacademy.org/wp-content/uploads/2020/04/Cognitive-Behavioural-Therapy-CBT-Techniques.png'
                        },
                        {
                            title: 'Emotional Intelligence Mastery',
                            description: 'Develop advanced skills in understanding and managing emotions.',
                            badge: 'Short Course',
                            image: 'https://process.fs.teachablecdn.com/ADNupMnWyR7kCWRvm76Laz/resize=width:705/https://www.filepicker.io/api/file/VgU1IWkSPJJITg8oygOw'
                        }
                    ]
                },
                'Science, Engineering & Maths': {
                    description: 'Dive deep into scientific discovery, engineering innovations, and mathematical reasoning.',
                    quote: '"Rigorous, challenging, and incredibly insightful courses that push the boundaries of knowledge."',
                    quoteAuthor: '- David, Research Scientist',
                    courses: [
                        {
                            title: 'Advanced Data Science',
                            description: 'Comprehensive training in statistical analysis and data interpretation.',
                            badge: 'Advanced Course',
                            image: 'https://anexas.net/wp-content/uploads/2024/09/data-sicence-adv-min.png'
                        },
                        {
                            title: 'Engineering Design Principles',
                            description: 'Learn cutting-edge design methodologies and innovative problem-solving.',
                            badge: 'Professional Development',
                            image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT01YI49hPAL3ifbunmGNlRGssHkvuF1DQXA-cyRhwF8cRqb0kexPO7t7TavGHq3nZ-tvU&usqp=CAU'
                        }
                    ]
                }
            };
        
            // Update content for a subject
            function updateSubjectContent(subject) {
                const content = subjectContent[subject];
                
                // Update description section
                subjectDescription.querySelector('h3').textContent = subject;
                subjectDescription.querySelector('p').textContent = content.description;
                
                const blockquote = subjectDescription.querySelector('blockquote');
                blockquote.innerHTML = `"${content.quote}"<footer>${content.quoteAuthor}</footer>`;
        
                // Update courses section
                subjectCourses.innerHTML = content.courses.map(course => `
                    <div class="course-card">
                        <div class="course-badge">${course.badge}</div>
                        <img src="${course.image}" alt="${course.title}">
                        <h4>${course.title}</h4>
                        <p>${course.description}</p>
                       <a href="explore_courses.php"><button class="start-course-btn">Explore Now</button></a>
                    </div>
                `).join('');
            }
        
            // Add click event to tabs
            subjectTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    subjectTabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    tab.classList.add('active');
                    
                    // Update content based on selected subject
                    updateSubjectContent(tab.textContent.trim());
                });
        
                // Set Business & Management as default active tab
                if (tab.textContent.trim() === 'Business & Management') {
                    tab.classList.add('active');
                }
            });
        
            // Initial content load
            updateSubjectContent('Business & Management');
        });

        document.addEventListener('DOMContentLoaded', () => {
            const courseSlider = document.querySelector('.course-slider');
            
            // Course data
            const coursesData = [
        <?php
        include "connect.php";
        $sql = "SELECT * FROM courses";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $courses = [];
            while ($course = $result->fetch_assoc()) {
                $courses[] = json_encode([
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'image' => $course['image'],
                    'badge' => $course['badge'],
                    'duration' => $course['duration'],
                    'enrolled' => $course['enrolled']
                ]);
            }
            echo implode(',', $courses);
        }
        $conn->close();
        ?>
    ]
            // Function to create course card HTML
            function createCourseCard(course) {
    return `
        <div class="course-card">
            <div class="course-image">
                <img src="${course.image}" alt="${course.title}">
                <div class="course-badge">${course.badge}</div>
            </div>
            <div class="course-details">
                <h3>${course.title}</h3>
                <p>${course.description}</p>
                <div class="course-meta">
                    <span><i class="fas fa-clock"></i> ${course.duration}</span>
                    <span><i class="fas fa-user-graduate"></i> ${course.enrolled} Enrolled</span>
                </div>
                <a href="start_course.php?course_id=${course.id}" class="start-course-btn">Start Course</a>
            </div>
        </div>
    `;
}
            function renderCourses(courses) {
                courseSlider.innerHTML = courses.map(createCourseCard).join('');
            }
        
            renderCourses(coursesData);
        
            const filterButtons = document.createElement('div');
            filterButtons.classList.add('course-filters');
            filterButtons.innerHTML = `
                <button data-filter="all" class="active">All Courses</button>
                <button data-filter="New">New</button>
                <button data-filter="Popular">Popular</button>
                <button data-filter="Advanced">Advanced</button>
            `;
        
            courseSlider.parentNode.insertBefore(filterButtons, courseSlider);
            filterButtons.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    filterButtons.querySelectorAll('button').forEach(btn => 
                        btn.classList.remove('active')
                    );
                    
                    e.target.classList.add('active');
        
                    const filter = e.target.dataset.filter;
                    const filteredCourses = filter === 'all' 
                        ? coursesData 
                        : coursesData.filter(course => course.badge === filter);
        
                    renderCourses(filteredCourses);
                }
            });
        
            const searchContainer = document.createElement('div');
            searchContainer.classList.add('course-search');
            searchContainer.innerHTML = `
                <input type="text" placeholder="Search courses..." id="courseSearch">
            `;
        
            // Insert search input before course slider
            courseSlider.parentNode.insertBefore(searchContainer, filterButtons);
        
            // Search event listener
            document.getElementById('courseSearch').addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const searchedCourses = coursesData.filter(course => 
                    course.title.toLowerCase().includes(searchTerm) || 
                    course.description.toLowerCase().includes(searchTerm)
                );
        
                renderCourses(searchedCourses);
            });
        
            // Add some basic styling for filters and search
            const style = document.createElement('style');
            style.textContent = `
                .course-filters {
                    display: flex;
                    justify-content: center;
                    gap: 15px;
                    margin-bottom: 30px;
                }
                .course-filters button {
                    padding: 10px 20px;
                    background: #f0f0f0;
                    border: none;
                    border-radius: 25px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }
                .course-filters button.active,
                .course-filters button:hover {
                    background: #ff1493;
                    color: white;
                }
                .course-search {
                    display: flex;
                    justify-content: center;
                    margin-bottom: 30px;
                }
                .course-search input {
                    width: 300px;
                    padding: 10px 20px;
                    border: 1px solid #ddd;
                    border-radius: 25px;
                    font-size: 1rem;
                }
                    .course-slider {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 cards per row */
    gap: 20px; /* Space between cards */
    padding: 20px;
    padding-left:175px;
    justify-content: center;
}

.course-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    padding: 15px;
    text-align: center;
    transition: transform 0.3s ease-in-out;
}

.course-card:hover {
    transform: scale(1.05);
}

.course-image {
    position: relative;
    overflow: hidden;
    border-radius: 10px;s
}

.course-image img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

.course-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #ff1493;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}

.course-meta {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 0.9rem;
    color: #555;
}
.start-course-btn {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    background: #ff1493;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.start-course-btn:hover {
    background: #e60073;
}
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>