<?php
//if (isset($_POST["submit"])) {
//    if (isset($_POST["approve"]) && is_array($_POST["approve"])) {
//        // Replace the database connection details accordingly
//        $conn = mysqli_connect("localhost", "root", "", "ictunitportal");
//
//        if (!$conn) {
//            die("Connection failed: " . mysqli_connect_error());
//        }
//
//        foreach ($_POST["approve"] as $recordId) {
//            $recordId = intval($recordId);
//            $isChecked = isset($_POST['approval_' . $recordId]) ? 1 : 0;
//            if ($isChecked == 1){
//                // Perform the database update to set 'approval' to 1 for approved records
//                $sql = "UPDATE repair SET approval = 1 WHERE record_id = $recordId";
//            } else{
//                // Perform the database update to set 'approval' to 1 for approved records
//                $sql = "UPDATE repair SET approval = 0 WHERE record_id = $recordId";
//            }
//
//
//
//            if (mysqli_query($conn, $sql)) {
//                // Record updated successfully
//            } else {
//                // Handle the update error
//                echo "Error updating record: " . mysqli_error($conn);
//            }
//        }
//
//        mysqli_close($conn);
//    }
//}
//
//// Redirect back to your original page or perform any other actions
//header("Location: dirReportDivisional.php");
////


//    $empID = $_POST["emp_id"];
//    $empName = $_POST["emp_name"];
//    $designation = $_POST["emp_designation"];
//    $empDivision = $_POST["emp_div"];
//    $date = date('Y-m-d');
////    $deviceType = $_POST["device_type"];
////    $serialNo = $_POST["device_serial"];
////    $Issue = trim($_POST["issue"]);
//    $deviceType = isset($_POST["device_type"]) ? $_POST["device_type"] : "";
//    $serialNo = isset($_POST["device_serial"]) ? $_POST["device_serial"] : "";
//    $Issue = isset($_POST["issue"]) ? $_POST["issue"] : "";
//    $issueTrimmed = trim($Issue);
//    $Service = isset($_POST["service"]) ? $_POST["service"] : "";
//    $serviceTrimmed = trim($Service);



$host = "localhost";
$username = "root";
$password = "";
$dbname = "ictunitportal";

// creating a connection
$con = mysqli_connect($host, $username, $password, $dbname);

// to ensure that the connection is made
if (!$con) {
    die("Connection failed!" . mysqli_connect_error());
}
$result1 = "";
$result2 = "";

    $checkRepairEntry = "SELECT * FROM repair WHERE emp_id = ? AND device_type = ? AND serial_no = ? AND issue = ? AND date = ?";
    $checkRepairEntry = "UPDATE repair SET approval_div_dir = 1 WHERE record_id;
";
    $checkStmt = mysqli_prepare($con, $checkRepairEntry);
    mysqli_stmt_bind_param($checkStmt, "sssss", $empID, $deviceType, $serialNo, $issueTrimmed, $date);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        // Identical record exists, display a message to the user
        echo "Duplicate entry: This repair request has already been submitted";
    } else {
        $sql1 = "INSERT IGNORE INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES (?, ?, ?, ?)";
        $stmt1 = mysqli_prepare($con, $sql1);
        mysqli_stmt_bind_param($stmt1, "ssss", $empID, $empName, $designation, $empDivision);

        $sql2 = "INSERT INTO repair (device_type, serial_no, issue, date, emp_id) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($con, $sql2);
        mysqli_stmt_bind_param($stmt2, "sssss", $deviceType, $serialNo, $issueTrimmed, $date, $empID);

        // send query to the database to add values and confirm if successful
        $result1 = mysqli_stmt_execute($stmt1);
        $result2 = mysqli_stmt_execute($stmt2);
    }

?>
