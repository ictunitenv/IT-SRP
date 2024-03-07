<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ictunitportal';
$con = mysqli_connect($host, $username, $password, $dbname);

if (!$con) {
    die('Connection failed!' . mysqli_connect_error());
}

// Function to generate a random 4-digit number with a '0' at the front
function generateRandomNumber()
{
    return str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
}

// Function to generate a unique request ID
function generateUniqueRequestID($table, $dateColumn, $requestIDColumn)
{
    global $con;

    do {
        // Get records without a request ID
        $sql = "SELECT record_id, $dateColumn FROM $table WHERE $requestIDColumn = ''";
        $result = mysqli_query($con, $sql);

        // Fetch a record
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            // No records without a request ID found
            break;
        }

        // Generate a request ID
        $recordID = $row['record_id'];
        $date = $row[$dateColumn];
        $formattedDate = date('dmY', strtotime($date));
        $randomNumber = generateRandomNumber();
        $requestID = 'REP' . $formattedDate . '-' . $randomNumber;

        // Update the record with the generated request ID
        $updateSQL = "UPDATE $table SET $requestIDColumn = '$requestID' WHERE record_id = $recordID";
        mysqli_query($con, $updateSQL);
    } while (true);
}

// Generate request IDs for the repair table
generateUniqueRequestID('repair', 'date', 'request_id');

// Generate request IDs for the service table
generateUniqueRequestID('service', 'date', 'request_id');

mysqli_close($con);
?>
