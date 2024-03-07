<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ictunitportal";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

// Query the database to check user credentials
$sql = "SELECT * FROM user WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    // Verify the entered password against the stored hash
    if (password_verify($password, $hashed_password)) {
        // Start a session
        session_start();

        // Store the user's role in a session variable
        $_SESSION['role'] = $row['role'];

        // Redirect based on user role
        if ($_SESSION['role'] === 'admin') {
            header("Location: ../dirReportICT.php");
        } elseif ($_SESSION['role'] === 'director') {
            header("Location: ../dirReportDivisional.php");
        } else {
            echo "Invalid role!";
        }
    } else {
        echo "Invalid password!";
    }
} else {
    echo "Invalid username!";
}

// Close the database connection
$conn->close();
?>


</body>
</html>
