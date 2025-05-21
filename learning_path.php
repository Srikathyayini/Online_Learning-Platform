<?php
include "connect.php";


// Query to fetch unique users
$sql = "SELECT DISTINCT user_name FROM user_enrollments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .user-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .user-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .user-icon {
            background-color: #e0e7ff;
            color: #4f46e5;
            font-size: 24px;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            margin: 0 auto 15px;
        }
        .user-name {
            font-weight: 600;
            margin-bottom: 10px;
        }
        .view-btn {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .view-btn:hover {
            background-color: #4338ca;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Users</h1>
        
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Search users...">
        </div>
        
        <div class="user-list" id="userList">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $userName = htmlspecialchars($row["user_name"]);
                    $firstLetter = strtoupper(substr($userName, 0, 1));
                    
                    echo '<div class="user-card">
                            <div class="user-icon">' . $firstLetter . '</div>
                            <div class="user-name">' . $userName . '</div>
                            <a href="user.php?username=' . urlencode($userName) . '" class="view-btn">View Progress</a>
                          </div>';
                }
            } else {
                echo '<p style="text-align: center; grid-column: 1/-1;">No users found</p>';
            }
            ?>
        </div>
    </div>

    <script>
        // Simple search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const input = this.value.toLowerCase();
            const cards = document.querySelectorAll('.user-card');
            
            cards.forEach(card => {
                const username = card.querySelector('.user-name').textContent.toLowerCase();
                if (username.includes(input)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>