<?php
session_start();
require_once('db_connect.php'); // Assuming you have a separate file for database connection

function redirect($url) {
    $url = "https://redisondavidfrank.github.io/Activity_Management_System/"; // Replace with your desired URL

	header("Location: $url");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];

        if (!empty($inputUsername) && !empty($inputPassword)) {
            $query = "SELECT * FROM user WHERE username = ? LIMIT 1";
            $loginStmt = $conn->prepare($query);
            $loginStmt->bind_param("s", $inputUsername);
            $loginStmt->execute();
            $result = $loginStmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $hashedPassword = $user['user_password'];

                if (password_verify($inputPassword, $hashedPassword)) {
                    $_SESSION['user_id'] = $user['id'];

                    if ($user['user_type'] === 'student') {
                        redirect('view.php');
                    } elseif ($user['user_type'] === 'instructor') {
                        redirect('dash.php');
                    }
                } else {
                    echo "Invalid username or password.";
                }
            } else {
                echo "Invalid username or password.";
            }

            $loginStmt->close();
        } else {
            echo "Please enter both username and password.";
        }
    } else {
        echo "Username and password not provided.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        body {
            font-family: Monaco, Monospace;
	        background-image: url("https://img.freepik.com/free-photo/liquid-marbling-paint-texture-background-fluid-painting-abstract-texture-intensive-color-mix-wallpaper_1258-82821.jpg?w=2000");
	        background-repeat: no-repeat;
 	        background-size: 145%;
 	        background-position: center;
	        background-color: #3b3a47;
        }
        
        .login-container {
            background-color: grey;
            border-radius: 20px;
            padding: 20px;
            width: 300px;
        }

        h1, p, label {
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        form {
            margin-top: 10px;
        }

        input[type="text"], input[type="password"] {
            display: block;
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #CCCCCC;
        }

        button[type="submit"] {
            background-color: #355E3B;
            color: #FFFFFF;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
        }

        a {
            color: #FFFFFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <p>Please enter your login credentials.</p>
        <form action="userlogin.php" method="POST">
            <input type="text" placeholder="Username" id="username" name="username" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <button type="submit">Login</button><br>
            <label>Don't have an account yet?</label>
            <a href="usersignup.php" style="color: #007BFF;">Sign Up</a>
        </form>
    </div>
</body>
</html>


