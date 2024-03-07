<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ictunitportal";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    session_start();
    $_SESSION['role'] = $row['role'];
    $_SESSION['division'] = $row['division'];

    // Login logic for the login page
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../ictUnitReport.php");
    } elseif ($_SESSION['role'] === 'director') {
        if ($_SESSION['division'] === "Environment Education Training and Special Project" || $_SESSION['division'] === "Additional Secretary Office" || $_SESSION['division'] === "Media" || $_SESSION['division'] === "Secretary Staff" || $_SESSION['division'] === "Minister Office"){
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['give_access'] = true;
            header("Location: ../dirReportICT.php");
        } else {
            header("Location: ../dirReportDivisional.php");
        }
    } else {
        echo "Invalid role!";
    }
} else {
    echo "Invalid username or password!";
}

$stmt->close();
$conn->close();
?>
</body>
</html>
