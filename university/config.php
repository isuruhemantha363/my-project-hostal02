<?php
$servername = "localhost";
$username = "root";     // XAMPP default username
$password = "";         // XAMPP default password (හිස්තැනක්)
$dbname = "university_db"; // ඔයා phpMyAdmin එකේ හදපු Database එකේ නම

// Connection එක සෑදීම
$conn = new mysqli($servername, $username, $password, $dbname);

// Connection එකේ අවුලක්ද කියා බැලීම
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>