<?php
if (isset($_POST['formData'])) {
    $formData = $_POST['formData'];

    $empID = $_POST['formData']['emp_id'];
    $empName = $_POST['formData']['emp_name'];
    $designation = $_POST['formData']['emp_designation'];
    $empDivision = $_POST['formData']['emp_div'];
    $date = date('Y-m-d');

//    $deviceType = isset($_POST['device_type']) ? $_POST['device_type'] : '';
//    $serialNo = isset($_POST['device_serial']) ? $_POST['device_serial'] : '';
//    $Issue = isset($_POST['issue']) ? $_POST['issue'] : '';
    $deviceType = $_POST['formData']['device_type'];
    $serialNo = $_POST['formData']['device_serial'];
    $Issue = $_POST['formData']['service_request'];
    $issueFormatted = nl2br(htmlspecialchars($Issue));
    $issueTrimmed = trim($issueFormatted);
    $tableSource = $_POST['formData']['table_source'];

//    $issueFormatted = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", " <br/>", $Issue);   //Remove blank lines and preserve line separation for viewing in html documents
//    $Service = ['formData']['service'];
//    $serviceFormatted = nl2br(htmlspecialchars($Service));
//    $serviceFormatted = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", " <br/>", $Service);   //Remove blank lines and preserve line separation for viewing in html documents
//    $serviceTextTrimmed = trim($serviceFormatted);


    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'ictunitportal';
    $con = mysqli_connect($host, $username, $password, $dbname);

    if (!$con) {
        die('Connection failed!' . mysqli_connect_error());
    }

    $result1 = false;
    $result2 = false;
    $requestID = '';
    $response = array();

    if ($tableSource == 'repair') {
        $checkRepairEntry = "SELECT repair.request_id FROM repair WHERE emp_id = ? AND device_type = ? AND serial_no = ? AND issue = ? AND date = ?";
        $checkStmt = mysqli_prepare($con, $checkRepairEntry);
        mysqli_stmt_bind_param($checkStmt, 'sssss', $empID, $deviceType, $serialNo, $issueTrimmed, $date);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $response['success'] = false;
            mysqli_stmt_bind_result($checkStmt, $originalRequestId);
            mysqli_stmt_fetch($checkStmt);

        } else {
            do {
                // Generate a random 4-digit number with a '0' at the front and Construct the request ID
                $randomNumber = str_pad(mt_rand(0, 9999), 4, '', STR_PAD_LEFT);
                $requestID = 'REP' . date('dmY') . '-' . $randomNumber;

                // Check if the request ID already exists in the repair table
                $checkRepairEntry = "SELECT record_id FROM repair WHERE request_id = ?";
                $checkStmt = mysqli_prepare($con, $checkRepairEntry);
                mysqli_stmt_bind_param($checkStmt, 's', $requestID);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);
            } while (mysqli_stmt_num_rows($checkStmt) > 0);

            $sql1 = "INSERT IGNORE INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES (?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($con, $sql1);
            mysqli_stmt_bind_param($stmt1, 'ssss', $empID, $empName, $designation, $empDivision);

            $sql2 = "INSERT INTO repair (device_type, serial_no, issue, date, emp_id, request_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($con, $sql2);
            mysqli_stmt_bind_param($stmt2, 'ssssss', $deviceType, $serialNo, $issueTrimmed, $date, $empID, $requestID);

            $result1 = mysqli_stmt_execute($stmt1);
            $result2 = mysqli_stmt_execute($stmt2);
        }
    } elseif ($tableSource == 'service') {
        $checkServiceEntry = "SELECT record_id FROM service WHERE emp_id = ? AND service = ? AND date = ?";
        $checkStmt = mysqli_prepare($con, $checkServiceEntry);
        mysqli_stmt_bind_param($checkStmt, 'sss', $empID, $issueTrimmed, $date);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $response['success'] = false;
        } else {
            do {
                // Generate a random 4-digit number with a '0' at the front and Construct the request ID
                $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $requestID = 'SVC' . date('dmY') . '-' . $randomNumber;

                // Check if the request ID already exists in the repair table
                $checkRepairEntry = "SELECT record_id FROM service WHERE request_id = ?";
                $checkStmt = mysqli_prepare($con, $checkRepairEntry);
                mysqli_stmt_bind_param($checkStmt, 's', $requestID);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);
            } while (mysqli_stmt_num_rows($checkStmt) > 0);

            $sql1 = "INSERT IGNORE INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES (?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($con, $sql1);
            mysqli_stmt_bind_param($stmt1, 'ssss', $empID, $empName, $designation, $empDivision);

            $sql2 = "INSERT INTO service (service, date, emp_id, request_id) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($con, $sql2);
            mysqli_stmt_bind_param($stmt2, 'ssss', $issueTrimmed, $date, $empID, $requestID);

            $result1 = mysqli_stmt_execute($stmt1);
            $result2 = mysqli_stmt_execute($stmt2);
        }
    } else {
        $response = array('success' => false);
        echo json_encode($response);
    }

    if ($result1 && $result2) {
        $response['success'] = true;
        $response['requestID'] = $requestID;
//        $response = array('success' => true, 'requestID' => $requestID);
//        echo json_encode($response);
    } else {
        $response['success'] = false;
        $response['requestID'] = $originalRequestId;
//        $response = array('success' => false);
//        echo json_encode($response);
    }

//    // Return JSON response
    echo json_encode($response);
} else {
    exit();
}
?>
