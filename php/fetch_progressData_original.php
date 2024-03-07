<?php
// Start a session
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

if (isset($_GET['request_id'])) {
    $requestId = $_GET['request_id'];

    $stmt = $pdo->prepare("
        (SELECT repair.record_id, 
                CASE 
                    WHEN repair.device_type IS NOT NULL AND repair.serial_no IS NOT NULL THEN 
                        CONCAT(repair.issue, '<br/><p><b>Device type:- </b>', repair.device_type, '<br/><b>Serial no:- </b>', repair.serial_no,'</p>') 
                    ELSE repair.issue 
                END as service_issue, 
                repair.date, repair.emp_id, repair.approval_div_dir, employee.name_initials, employee.emp_division,
                'repair' as table_source
        FROM repair 
        JOIN employee ON repair.emp_id = employee.emp_id 
        WHERE repair.request_id = :request_id) 
    
        UNION ALL 
    
        (SELECT service.record_id, service.service as service_issue, service.date, service.emp_id, service.approval_div_dir, employee.name_initials, employee.emp_division,
                'service' as table_source
        FROM service 
        JOIN employee ON service.emp_id = employee.emp_id 
        WHERE service.request_id = :request_id) 
        ORDER BY date");

    // Bind the request ID parameter
    $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } else {
        echo json_encode(array());
        echo "error";
    }
} else {
    echo json_encode(array()); // Return an empty JSON array if the user is not authorized
    echo "error";
}
?>