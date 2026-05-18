<?php
include 'config.php'; // Database එකට සම්බන්ධ ෆයිල් එක ලින්ක් කිරීම

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['fullName']);
    $student_number = trim($_POST['studentNumber']); // උදා: SC/2023/12345
    $faculty = $_POST['faculty'];

    // 1. එකම Student Number එක පද්ධතියේ දැනටමත් තියෙනවාදැයි Prepared Statement එකකින් වේගවත්ව පරීක්ෂා කිරීම
    $check_sql = "SELECT id FROM students WHERE student_number = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $student_number);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // දැනටමත් තිබේ නම් දෝෂ පණිවිඩයක් පෙන්වීම
        $message = "<p style='color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align:center; margin-bottom: 15px;'>මෙම ශිෂ්‍ය අංකය දැනටමත් පද්ධතියේ ලියාපදිංචි වී ඇත!</p>";
        $stmt_check->close();
    } else {
        $stmt_check->close();

        // 2. ශිෂ්‍ය අංකය හැෂ් කරන්නේ නැතිව කෙලින්ම සේව් කිරීම (SQL Injection වලින් ආරක්ෂා වීමට Prepared Statements භාවිතා කරයි)
        $insert_sql = "INSERT INTO students (full_name, student_number, faculty) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $full_name, $student_number, $faculty);

        if ($stmt->execute()) {
            $message = "<p style='color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; text-align:center; margin-bottom: 15px;'>ලියාපදිංචිය සාර්ථකයි! දැන් ලොගින් විය හැක.</p>";
        } else {
            $message = "<p style='color: red; text-align:center; margin-bottom: 15px;'>දෝෂයක් සිදු වුණා: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
            text-align: center;
        }

        p.subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s;
            background-color: #fff;
        }

        input[type="text"]:focus, select:focus {
            border-color: #28a745;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            background-color: #218838;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>
    <p class="subtitle">පද්ධතියට ලියාපදිංචි වීම සඳහා තොරතුරු ඇතුළත් කරන්න.</p>
    
    <?php echo $message; ?>
    
    <form action="register.php" method="POST">
        <div class="input-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" placeholder="John Doe" required>
        </div>

        <div class="input-group">
            <label for="studentNumber">Student Number</label>
            <input type="text" id="studentNumber" name="studentNumber" placeholder="eg: SC/2023/12345" required>
        </div>

        <div class="input-group">
            <label for="faculty">Faculty</label>
            <select id="faculty" name="faculty" required>
                <option value="" disabled selected>Select your faculty</option>
                <option value="art">Art</option>
                <option value="management">Management</option>
                <option value="science">Science</option>
                <option value="agriculture">Fisheries and Marine Sciences</option>
            </select>
        </div>

        <button type="submit" class="btn-register">Register</button>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </form>
</div>

</body>
</html>