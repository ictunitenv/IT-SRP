<?php
// Start a session
$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

if (isset($_GET['request_id']) && isset($_GET['table_source'])) {
    $requestId = $_GET['request_id'];
    $tableSource = $_GET['table_source'];

    if ($_GET['table_source'] === 'repair') {

        $stmt = $pdo->prepare("
        SELECT 
            repair.record_id, 
            repair.device_type, 
            repair.serial_no, 
            repair.issue AS service_issue, 
            repair.date, 
            repair.emp_id, 
            repair.request_id, 
            repair.approval_div_dir, 
            repair.div_dir_approval_date, 
            repair.approval_ict_dir, 
            repair.ict_dir_approval_date, 
            repair.status, 
            repair.completed_date, 
            repair.remarks, 
            repair.is_finished, 
            employee.name_initials, 
            employee.emp_division, 
            employee.emp_designation,
            'Repair' as table_source
        FROM 
            repair 
            JOIN employee ON repair.emp_id = employee.emp_id 
        WHERE 
            repair.request_id = :request_id");

        // Bind the request ID parameter
        $stmt->bindParam(':request_id', $requestId, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } else {
            echo json_encode(array());
            echo "error";
        }
    } else if ($_GET['table_source'] === 'service') {
        $stmt = $pdo->prepare("
        SELECT  
            service.record_id , 
            service.service as service_issue, 
            service.date, 
            service.emp_id, 
            service.request_id,
            service.approval_div_dir, 
            service.div_dir_approval_date, 
            service.approval_ict_dir, 
            service.ict_dir_approval_date, 
            service.status, 
            service.completed_date,
            service.remarks, 
            service.is_finished, 
            employee.name_initials, 
            employee.emp_division,
            employee.emp_designation, 
            'Service' as table_source
        FROM 
            service 
            JOIN employee ON service.emp_id = employee.emp_id 
        WHERE 
            service.request_id = :request_id");

        // Bind the request ID parameter
        $stmt->bindParam(':request_id', $requestId, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } else {
            echo json_encode(array());
            echo "error";
        }
    } else {
        echo json_encode(array()); // Return an empty JSON array if the table source is not valid
        echo "error";
    }
} else {
    echo json_encode(array()); // Return an empty JSON array if the user is not authorized
    echo "error";
}
?>