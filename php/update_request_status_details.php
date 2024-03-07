<?php

$date = date('Y-m-d');

$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

// Check if record_id is provided via POST
if (isset($_POST['request_id']) && isset($_POST['table_source'])) {
    // Assign data to variables
    $requestId = $_POST['request_id'];
    $tableSource = $_POST['table_source'];
    $updateRequestStatus = $_POST['update_request_status'];
    //  Assign null if there is no remarks
    if ($_POST['request_remarks'] == ""){
        $requestRemarks = null;
        $requestRemarkDate = null;
    } else {
        $requestRemarks = $_POST['request_remarks'];
        $requestRemarkDate = $date;
    }

    $requestRemarks = trim(nl2br(htmlspecialchars($requestRemarks)));

    // Determine the table name based on the table source
//    $tableName = ($tableSource === 'repair') ? 'repair' : 'service';

    $stmt = '';
    // Update the database
    if ($updateRequestStatus == 1 || $updateRequestStatus == 9) {
        $stmt = $pdo->prepare("UPDATE $tableSource SET status = :request_status, completed_date = :completed_date, remarks = :request_remarks, remark_date = :remark_date WHERE request_id = :request_id");
        $stmt->bindParam(':completed_date', $date, PDO::PARAM_STR);
    } elseif ($updateRequestStatus == 0) {
        $stmt = $pdo->prepare("UPDATE $tableSource SET status = :request_status, is_finished = $updateRequestStatus, completed_date = :completed_date, remarks = :request_remarks, remark_date = :remark_date WHERE request_id = :request_id");
        $stmt->bindParam(':completed_date', $date, PDO::PARAM_STR);
    } else {
        $stmt = $pdo->prepare("UPDATE $tableSource SET status = :request_status, completed_date = null, remarks = :request_remarks, remark_date = :remark_date WHERE request_id = :request_id");
    }

//    $stmt = $pdo->prepare("UPDATE repair SET status = :request_status, remarks = :request_remarks WHERE request_id = :request_id");

    $stmt->bindParam(':request_id', $requestId, PDO::PARAM_STR);
    $stmt->bindParam(':remark_date', $requestRemarkDate, PDO::PARAM_STR);
    $stmt->bindParam(':request_status', $updateRequestStatus, PDO::PARAM_INT);
    $stmt->bindParam(':request_remarks', $requestRemarks, PDO::PARAM_STR);
//    $stmt->bindParam(':status', $approvalValue, PDO::PARAM_INT);

    // Execute the update
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    // record_id not provided in POST data
    echo 'error';
}
?>
