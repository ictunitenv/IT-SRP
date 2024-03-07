<?php

$date = date('Y-m-d');

// Assuming you have already established a database connection
$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

// Check if record_id is provided via POST
if (isset($_POST['record_id']) && isset($_POST['action']) && isset($_POST['table_source'])) {
    $recordId = $_POST['record_id'];
    $action = $_POST['action']; //action passed from the button click
    $tableSource = $_POST['table_source']; //table source passed from the button click

    // Determine the table name based on the table source
    $tableName = ($tableSource === 'repair') ? 'repair' : 'service';

    // Update the approval_div_dir column based on the action
    $approvalValue = ($action === 'approve') ? 1 : 0;

    // Update the database using prepared statements
    $stmt = $pdo->prepare("UPDATE $tableName SET approval_div_dir = :approval_value, div_dir_approval_date = :div_approval_date WHERE record_id = :record_id");
    $stmt->bindParam(':record_id', $recordId, PDO::PARAM_INT);
    $stmt->bindParam(':approval_value', $approvalValue, PDO::PARAM_INT);
    $stmt->bindParam(':div_approval_date', $date, PDO::PARAM_STR);

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
