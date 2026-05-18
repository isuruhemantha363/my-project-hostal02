<?php
// 1. පිටුවේ ඉහළින්ම කිසිම හිස්තැනක් (Space) නොතබා ලියන්න.
include 'config.php';
session_start(); 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = trim($_POST['studentNumber']);

    // Admin ද කියා පරීක්ෂා කිරීම
    if ($student_number === "Admin7788") {
        $_SESSION['admin_logged_in'] = true;
        header("Location:order_food1.html"); 
        exit();
    }

    // ශිෂ්‍යයෙක්දැයි පරීක්ෂා කිරීම
    $sql = "SELECT id, full_name FROM students WHERE student_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_name'] = $row['full_name'];
        
        $stmt->close();
        
        // සාර්ථකව ලොග් වූ පසු order_food.html වෙත රැගෙන යාම
        header("Location:order_food.html"); 
        exit(); // රීඩිරෙක්ට් එකෙන් පසු කෝඩ් එක නතර කිරීමට මේක අනිවාර්යයි
    } else {
        $error = "වැරදි ශිෂ්‍ය අංකයක්! කරුණාකර නැවත උත්සාහ කරන්න.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student & Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #007bff;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 15px;
            text-align: center;
        }

        .register-link {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .register-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <p>ඇතුළු වීම සඳහා ඔබේ ශිෂ්‍ය අංකය ඇතුළත් කරන්න.</p>
    
    <form id="loginForm" action="login.php" method="POST">
        <div class="input-group">
            <label for="studentNumber">Student Number</label>
            <input type="text" id="studentNumber" name="studentNumber" placeholder="SC/2023/12345" required>
        </div>
        <button type="submit" class="btn-login">Log In</button>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
    </form>

    <div class="register-link">
        New Student? <a href="register.php">Register Here</a>
    </div>
</div>

</body>
</html>