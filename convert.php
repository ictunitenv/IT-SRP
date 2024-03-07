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

// Query the database to get all users
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get the user's plain text password
        $plain_password = $row['password'];

        // Hash the password
        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $user_id = $row['user_id'];
        $update_sql = "UPDATE user SET password = '$hashed_password' WHERE user_id = $user_id";
        if ($conn->query($update_sql) === TRUE) {
            echo "User ID $user_id password updated successfully.<br>";
        } else {
            echo "Error updating password for user ID $user_id: " . $conn->error . "<br>";
        }
    }
} else {
    echo "No users found in the database.";
}

// Close the database connection
$conn->close();
?>
