<?php 
include "connect.php";
session_start(); // Start the session
$is_logged_in = isset($_SESSION['fName']);
if(isset($_POST['submit'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $badge = trim($_POST['badge']);
    $image = trim($_POST['image']);
    $duration = trim($_POST['duration']);
    $enrolled = trim($_POST['enrolled']);

    $stmt = $conn->prepare("INSERT INTO courses (title, description, badge, image, duration, enrolled) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $description, $badge, $image, $duration, $enrolled);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "New Course Added Successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add course"]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch courses from the database
if(isset($_GET['fetch'])) {
    $sql = "SELECT * FROM courses";
    $result = $conn->query($sql);

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    echo json_encode($courses);
    exit;
}
function resetCourseIds($conn) {
    // Temporarily disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    
    // Create a temporary table with new sequential IDs
    $conn->query("CREATE TEMPORARY TABLE temp_courses AS 
                  SELECT 
                    @row_number:=@row_number+1 AS new_id, 
                    id AS old_id, 
                    title, 
                    description, 
                    badge, 
                    image, 
                    duration, 
                    enrolled
                  FROM 
                    courses, 
                    (SELECT @row_number:=0) r
                  ORDER BY id");
    
    // Truncate original table
    $conn->query("TRUNCATE TABLE courses");
    
    // Insert data back with new sequential IDs
    $conn->query("INSERT INTO courses 
                  (id, title, description, badge, image, duration, enrolled)
                  SELECT 
                    new_id, 
                    title, 
                    description, 
                    badge, 
                    image, 
                    duration, 
                    enrolled
                  FROM temp_courses");
    
    // Reset auto-increment
    $conn->query("ALTER TABLE courses AUTO_INCREMENT = 1");
    
    // Drop temporary table
    $conn->query("DROP TEMPORARY TABLE temp_courses");
    
    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
}

// Delete Course
if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Course Deleted"]);
    exit;
}

// Update Course
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $badge = trim($_POST['badge']);
    $image = trim($_POST['image']);
    $duration = trim($_POST['duration']);
    $enrolled = trim($_POST['enrolled']);

    $stmt = $conn->prepare("UPDATE courses SET title=?, description=?, badge=?, image=?, duration=?, enrolled=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $description, $badge, $image, $duration, $enrolled, $id);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Course Updated"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    
    <style>
        body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    background:linear-gradient(to right,white,pink,white);
    margin: 0;
    padding: 40px;
    color: #333;
}
.gradient-header {
    background: linear-gradient(to right, #6a11cb, #2575fc);
    color: white;
    position: sticky;
    top: 0;
    width: 100%;
    z-index: 1000;
    padding: 15px 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.navcontainer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Logo */
.logo {
    height: 50px;
    filter: brightness(0) invert(1);
}

/* Navigation Menu */
.nav-menu {
    display: flex;
    gap: 30px;
}

.nav-link {
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: underline;
}

/* Header Buttons */
.nav-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

/* Outline Button */
.btn-outline {
    border: 2px solid white;
    color: white;
    background: transparent;
}

.btn-outline:hover {
    background-color: white;
    color: #6a11cb;
}

/* Primary Button */
.btn-primary {
    background-color: orange;
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
}

.btn-primary:hover {
    background-color: #ff8c42;
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.6);
}

/* Responsive Design */
@media (max-width: 768px) {
    .navcontainer {
        flex-direction: column;
        text-align: center;
    }

    .nav-menu {
        flex-direction: column;
        gap: 15px;
        margin-top: 10px;
    }

    .nav-actions {
        margin-top: 10px;
        flex-direction: column;
        gap: 10px;
    }
}
.container {
    width: 100%;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 30px;
    font-weight: 700;
}

#addCourseForm {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 30px;
    background-color: #f1f3f5;
    padding: 20px;
    border-radius: 8px;
}

#addCourseForm input {
    flex: 1;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 15px;
    transition: all 0.3s ease;
}

#addCourseForm input:focus {
    outline: none;
    border-color: #3182ce;
    box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
}

table {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

th {
    background-color: #3182ce;
    color: white;
    padding: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
}

td {
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}

tr:nth-child(even) {
    background-color: #f8fafc;
}

tr:hover {
    background-color: #f1f5f9;
    transition: background-color 0.3s ease;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    margin: 0 5px;
}

.edit-btn, button[onclick^="editCourse"] {
    background-color: #3182ce;
    color: white;
}

.delete-btn, button[onclick^="deleteCourse"] {
    background-color: #e53e3e;
    color: white;
}

button:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

td img {
    max-width: 100px;
    height: auto;
    border-radius: 6px;
    object-fit: cover;
}

@media (max-width: 768px) {
    #addCourseForm {
        flex-direction: column;
    }
    
    #addCourseForm input {
        width: 100%;
    }
    
    table {
        font-size: 14px;
    }
}
#openAddCourseModal {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 20px;
    background-color: #3182ce;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 20px;
}

#openAddCourseModal:hover {
    background-color: #2c5282;
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

#openAddCourseModal:active {
    transform: translateY(1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Optional: Add a plus icon */
#openAddCourseModal::before {
    content: '+';
    font-size: 20px;
    line-height: 1;
}
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            border-radius: 12px;
            padding: 30px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
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
        .modal-close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-close:hover {
            color: #333;
        }

        #addCourseForm {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        #addCourseForm input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        #addCourseModal .modal-content button[type="submit"] {
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            background-color: #3182ce;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #addCourseModal .modal-content button[type="submit"]:hover {
            background-color: #2c5282;
        }
        

        #editCourseModal input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        #editCourseModal .modal-content button[type="submit"] {
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            background-color: #3182ce;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #editCourseModal .modal-content button[type="submit"]:hover {
            background-color: #2c5282;
        }
    </style>
</head>
<body>
<header class="gradient-header">
        <nav class="navbar">
            <div class="navcontainer">
                <div class="logo-container">
                    <img src="futurelearn-logo-white.svg" alt="FutureLearn Logo" class="logo">
                </div>
                <div class="nav-menu">
                    <a href="admin_home.php" class="nav-link">Home</a>
                    <a href="learning_path.php" class="nav-link">Users</a>
                </div>
                <div class="nav-actions">
                    <a href="#" class="search-trigger">
                        <i class="fas fa-search"></i>
                    </a>

                    <?php if ($is_logged_in): ?>
                        <!-- Show Username if logged in -->
                        <span class="username">Welcome, <?php echo htmlspecialchars($_SESSION['fName']); ?>!</span>
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

    <div class="container">
        <h1>Course Management</h1>
        
        <button id="openAddCourseModal" class="btn btn-primary mb-3">Add New Course</button>

        <!-- Add Course Modal -->
        <div id="addCourseModal" class="modal">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <h2>Add New Course</h2>
                <form id="addCourseForm">
                    <input type="text" name="title" placeholder="Title" required>
                    <input type="text" name="description" placeholder="Description" required>
                    <input type="text" name="badge" placeholder="Badge" required>
                    <input type="url" name="image" placeholder="Image URL" required>
                    <input type="text" name="duration" placeholder="Duration" required>
                    <input type="text" name="enrolled" placeholder="Enrolled" required>
                    <button type="submit">Add Course</button>
                </form>
            </div>
        </div>

        <!-- Edit Course Modal -->
        <div id="editCourseModal" class="modal">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <h2>Edit Course</h2>
                <form id="editCourseForm">
                    <input type="hidden" name="id" id="editCourseId">
                    <input type="text" name="title" id="editTitle" placeholder="Title" required>
                    <input type="text" name="description" id="editDescription" placeholder="Description" required>
                    <input type="text" name="badge" id="editBadge" placeholder="Badge" required>
                    <input type="url" name="image" id="editImage" placeholder="Image URL" required>
                    <input type="text" name="duration" id="editDuration" placeholder="Duration" required>
                    <input type="text" name="enrolled" id="editEnrolled" placeholder="Enrolled" required>
                    <button type="submit">Update Course</button>
                </form>
            </div>
        </div>

        <!-- Courses Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Badge</th>
                    <th>Image</th>
                    <th>Duration</th>
                    <th>Enrolled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="courseTableBody">
                <!-- Courses will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const courseTableBody = document.getElementById('courseTableBody');
        const addCourseForm = document.getElementById('addCourseForm');
        const editCourseForm = document.getElementById('editCourseForm');
        const addCourseModal = document.getElementById('addCourseModal');
        const editCourseModal = document.getElementById('editCourseModal');
        const openAddCourseModalBtn = document.getElementById('openAddCourseModal');
        const modalCloseBtns = document.querySelectorAll('.modal-close');

        // Local Storage Key
        const COURSES_STORAGE_KEY = 'coursesData';

        // Fetch or Initialize Courses
        function fetchCourses() {
            fetch('add_course.php?fetch=1')
                .then(response => response.json())
                .then(data => {
                    // Save to local storage
                    localStorage.setItem(COURSES_STORAGE_KEY, JSON.stringify(data));
                    renderCourses(data);
                })
                .catch(() => {
                    // Fallback to local storage if fetch fails
                    const storedCourses = JSON.parse(localStorage.getItem(COURSES_STORAGE_KEY) || '[]');
                    renderCourses(storedCourses);
                });
        }

        // Render Courses in Table
        function renderCourses(courses) {
            courseTableBody.innerHTML = '';
            courses.forEach(course => {
                courseTableBody.innerHTML += `
                    <tr>
                        <td>${course.id}</td>
                        <td>${course.title}</td>
                        <td>${course.description}</td>
                        <td>${course.badge}</td>
                        <td><img src="${course.image}" width="100"></td>
                        <td>${course.duration}</td>
                        <td>${course.enrolled}</td>
                        <td>
                            <button onclick="editCourse(${course.id}, '${course.title}', '${course.description}', '${course.badge}', '${course.image}', '${course.duration}', '${course.enrolled}')">Edit</button>
                            <button onclick="deleteCourse(${course.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        }

        // Open Add Course Modal
        openAddCourseModalBtn.addEventListener('click', () => {
            addCourseModal.style.display = 'flex';
        });

        // Close Modals
        modalCloseBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                addCourseModal.style.display = 'none';
                editCourseModal.style.display = 'none';
            });
        });

        // Close modal if clicked outside
        window.addEventListener('click', (event) => {
            if (event.target === addCourseModal || event.target === editCourseModal) {
                addCourseModal.style.display = 'none';
                editCourseModal.style.display = 'none';
            }
        });

        // Edit Course Function
        window.editCourse = function(id, title, description, badge, image, duration, enrolled) {
            document.getElementById('editCourseId').value = id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;
            document.getElementById('editBadge').value = badge;
            document.getElementById('editImage').value = image;
            document.getElementById('editDuration').value = duration;
            document.getElementById('editEnrolled').value = enrolled;

            editCourseModal.style.display = 'flex';
        };

        // Submit Edit Course Form
        editCourseForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(editCourseForm);
            formData.append('update', '1');

            fetch('add_course.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    fetchCourses();
                    editCourseModal.style.display = 'none';
                } else {
                    alert('Update failed');
                }
            });
        });

        // Add Course Form Submit
        addCourseForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(addCourseForm);
            formData.append('submit', '1');

            fetch('add_course.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    fetchCourses();
                    addCourseForm.reset();
                    addCourseModal.style.display = 'none';
                } else {
                    alert('Add course failed');
                }
            });
        });

        // Delete Course Function
        window.deleteCourse = function(id) {
            if (confirm('Are you sure you want to delete this course?')) {
                fetch('add_course.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `delete=1&id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        fetchCourses();
                    } else {
                        alert('Delete failed');
                    }
                });
            }
        };

        // Initial fetch of courses
        fetchCourses();
    });
    </script>
</body>
</html>