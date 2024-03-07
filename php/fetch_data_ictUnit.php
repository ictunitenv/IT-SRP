<?php
// Start a session
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

// Check if the user is logged in and has a division assigned
//if (isset($_SESSION['role']) && $_SESSION['role'] === 'director' && isset($_SESSION['division']) && $_SESSION['division'] === 'ICT Unit') {
$stmt = $pdo->query("
    SELECT 
        repair.record_id, 
        CASE 
            WHEN repair.device_type IS NOT NULL 
            AND repair.serial_no IS NOT NULL 
            THEN CONCAT(
                repair.issue, '<br/><br/><p><b>Device type:- </b>', 
                repair.device_type, '<br/><b>Serial no:- </b>', 
                repair.serial_no, '<br/><b>Requested by:- </b>', 
                employee.name_initials, ' (', 
                employee.emp_division, ')<br/><b>Approved by:- </b>', 
                user.name, '</p><br/><p style=\"background-color: #00ff3826; width: fit-content;\"><b>Request ID:- </b>',
                repair.request_id, 
                ' </p>'
            ) ELSE 
                repair.issue 
            END 
            AS service_issue, 
                repair.issue,
                repair.date, 
                repair.emp_id, 
                repair.request_id, 
                repair.approval_ict_dir, 
                repair.ict_dir_approval_date, 
                repair.status, 
                repair.remarks,
                repair.is_finished,
                employee.name_initials, 
                employee.emp_division, 
                user.name,
                'repair' AS table_source 
    FROM 
        (repair JOIN employee 
        ON repair.emp_id = employee.emp_id) JOIN user 
            ON repair.approved_by = user.user_id
    WHERE 
        repair.approval_div_dir = 1
    
    UNION ALL
    
    SELECT 
        service.record_id, 
        CONCAT(
            service.service, '<br/><br/><p><b>Requested by:- </b>', 
            employee.name_initials, ' (', 
            employee.emp_division, ')<br/><b>Approved by:- </b>',
            user.name, '</p><br/><p style=\"background-color: #00ff3826; width: fit-content;\"><b>Request ID:- </b>', 
            service.request_id, ' </p>'
            ) as service_issue, 
                service.service,
                service.date, 
                service.emp_id, 
                service.request_id, 
                service.approval_ict_dir, 
                service.ict_dir_approval_date, 
                service.status, 
                service.remarks, 
                service.is_finished, 
                employee.name_initials, 
                employee.emp_division, 
                user.name,
                'service' as table_source 
    FROM 
        (service JOIN employee 
        ON service.emp_id = employee.emp_id) JOIN user 
            ON service.approved_by = user.user_id 
    WHERE 
    service.approval_div_dir = 1
    
    ORDER BY approval_ict_dir DESC,is_finished, ict_dir_approval_date DESC;
");

$data = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}
echo json_encode($data);

?>