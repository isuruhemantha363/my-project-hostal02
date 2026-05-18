<?php
session_start();
session_destroy(); // Session එක මකා දැමීම
header("Location: login.php");
exit();
?>