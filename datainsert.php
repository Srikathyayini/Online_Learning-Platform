<?php
// Database connection

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Subject data as PHP array (converted from JavaScript object)
$subjectContent = [
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

// Begin transaction for data integrity
$conn->begin_transaction();

try {
    // Insert subject data
    foreach ($subjectContent as $subjectName => $data) {
        $stmt = $conn->prepare("INSERT INTO subjects (name, description, quote, quote_author) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $subjectName, $data['description'], $data['quote'], $data['quoteAuthor']);
        $stmt->execute();
        
        // Get the subject ID for course insertion
        $subject_id = $conn->insert_id;
        
        // Insert courses for this subject
        foreach ($data['courses'] as $course) {
            $stmt = $conn->prepare("INSERT INTO courses (subject_id, title, description, badge, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $subject_id, $course['title'], $course['description'], $course['badge'], $course['image']);
            $stmt->execute();
        }
    }
    
    // Commit transaction if all inserts were successful
    $conn->commit();
    echo "Data inserted successfully!";
} catch (Exception $e) {
    // Roll back transaction if any inserts failed
    $conn->rollback();
    echo "Error inserting data: " . $e->getMessage();
}

// Close connection
$conn->close();
?>