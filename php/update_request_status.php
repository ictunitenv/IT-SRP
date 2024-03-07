<?php

$date = date('Y-m-d');

$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

// Check if record_id is provided via POST
if (isset($_POST['request_id']) && isset($_POST['table_source'])) {
    $requestId = $_POST['request_id'];
    $tableSource = $_POST['table_source'];
    $value = $_POST['value'];
    $requestStatus = $_POST['request_status'];

    if ($value === 'endJob') {
        $value = 1;
    }

    // Determine the table name based on the table source
//    $tableName = ($tableSource === 'repair') ? 'repair' : 'service';

    $stmt = '';
    // Update the database using prepared statements
    if ($requestStatus === 'FINISH') {
        $stmt = $pdo->prepare("UPDATE $tableSource SET is_finished = :value WHERE request_id = :request_id");
    } else {
        $stmt = $pdo->prepare("UPDATE $tableSource SET status = :value WHERE request_id = :request_id");
    }

//    $stmt = $pdo->prepare("UPDATE $tableSource SET status = :value WHERE request_id = :request_id");

    $stmt->bindParam(':request_id', $requestId, PDO::PARAM_STR);
    $stmt->bindParam(':value', $value, PDO::PARAM_INT);
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
