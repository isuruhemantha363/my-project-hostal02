<?php
session_start();
// ලොග් නොවී කෙලින්ම මේ පිටුවට ඒම වැළැක්වීම
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body style="font-family:sans-serif; text-align:center; padding-top:50px;">

    <h1>ආයුබෝවන්, <?php echo $_SESSION['student_name']; ?>!</h1>
    <p>ඔබ සාර්ථකව පද්ධතියට ඇතුළු වී ඇත.</p>
    <br>
    <a href="logout.php" style="color:red; font-weight:bold;">Log Out</a>

</body>
</html>