<?php
// getting all values from the HTML form
if (isset($_POST['submit'])) {
    $empID = $_POST["emp_id"];
    $empName = $_POST["emp_name"];
    $designation = $_POST["emp_designation"];
    $empDivision = $_POST["emp_div"];
    $date = date('Y-m-d');
//    $deviceType = $_POST["device_type"];
//    $serialNo = $_POST["device_serial"];
//    $Issue = trim($_POST["issue"]);
    $deviceType = isset($_POST["device_type"]) ? $_POST["device_type"] : "";
    $serialNo = isset($_POST["device_serial"]) ? $_POST["device_serial"] : "";
    $Issue = isset($_POST["issue"]) ? $_POST["issue"] : "";
    $issueTrimmed = trim($Issue);
    $Service = isset($_POST["service"]) ? $_POST["service"] : "";
    $serviceTrimmed = trim($Service);


//$currentDate = date('Y-m-d');
//echo $currentDate;

// database details
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

// using sql to create a data entry query
//$sql1 = "INSERT INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES ('$empID', '$empName', '$designation', '$empDivision')";
//$sql2 = "INSERT INTO repair (device_type, serial_no, issue, date, emp_id) VALUES ('$DeviceType', '$serialNo', '$Issue', '$date', '$empID')";

//$checkRepairEntry = "SELECT * FROM repair WHERE emp_id = ? AND device_type = ? AND serial_no = ? AND issue = ? AND date = ?";
//$checkStmt = mysqli_prepare($con, $checkRepairEntry);
//mysqli_stmt_bind_param($checkStmt, "sssss", $empID, $deviceType, $serialNo, $issueTrimmed, $date);
//mysqli_stmt_execute($checkStmt);
//mysqli_stmt_store_result($checkStmt);

    $result1 = "";
    $result2 = "";

    if ($serviceTrimmed == "") {
        $checkRepairEntry = "SELECT * FROM repair WHERE emp_id = ? AND device_type = ? AND serial_no = ? AND issue = ? AND date = ?";
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
    } else {
        $checkRepairEntry = "SELECT * FROM service WHERE emp_id = ? AND service = ? AND date = ?";
        $checkStmt = mysqli_prepare($con, $checkRepairEntry);
        mysqli_stmt_bind_param($checkStmt, "sss", $empID, $serviceTrimmed, $date);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            // Identical record exists, display a message to the user
            echo "Duplicate entry: This repair record already exists.";
        } else {
            $sql1 = "INSERT IGNORE INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES (?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($con, $sql1);
            mysqli_stmt_bind_param($stmt1, "ssss", $empID, $empName, $designation, $empDivision);

            $sql2 = "INSERT INTO service (service, date, emp_id) VALUES (?, ?, ?)";
            $stmt2 = mysqli_prepare($con, $sql2);
            mysqli_stmt_bind_param($stmt2, "sss", $serviceTrimmed, $date, $empID);

            // send query to the database to add values and confirm if successful
            $result1 = mysqli_stmt_execute($stmt1);
            $result2 = mysqli_stmt_execute($stmt2);
        }
    }

    if ($result1 && $result2) {
        echo "Entries added!";
    } else {
        echo "Error: " . mysqli_error($con);
    }

// close connection
    mysqli_close($con);
}
?>