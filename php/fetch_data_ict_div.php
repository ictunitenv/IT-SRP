<?php
// Start a session
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

// Check if the user is logged in and has a division assigned
if (isset($_SESSION['role']) && $_SESSION['role'] === 'director' && isset($_SESSION['division']) && $_SESSION['give_access']) {
    $stmt = $pdo->query("
        SELECT 
            repair.record_id,
            CASE
                WHEN repair.device_type IS NOT NULL 
                AND repair.serial_no IS NOT NULL 
                THEN Concat(
                    repair.issue, '<br/><br/><p><b>Device type:- </b>',
                    repair.device_type, '<br/><b>Serial no:- </b>',
                    repair.serial_no, '</p>'
                    )
                ELSE 
                    repair.issue 
                END 
                AS service_issue,
                    repair.date,
                    repair.emp_id,
                    repair.approval_div_dir,
                    repair.approval_ict_dir,
                    employee.name_initials,
                    employee.emp_division,
                    'repair' AS table_source
        FROM 
            repair JOIN employee 
            ON repair.emp_id = employee.emp_id
        WHERE 
            (repair.approval_div_dir = 1 OR (repair.approval_div_dir IS NULL AND employee.emp_division IN ('Environment Education Training and Special Project', 'Additional Secretary Office', 'Media', 'Secretary Staff', 'Minister Office')))
            
        
        UNION ALL
        
        SELECT 
            service.record_id,
            service.service AS service_issue,
            service.date,
            service.emp_id,
            service.approval_div_dir,
            service.approval_ict_dir,
            employee.name_initials,
            employee.emp_division,
            'service' AS table_source
        FROM 
            service JOIN  employee 
            ON service.emp_id = employee.emp_id
        WHERE 
            (service.approval_div_dir = 1 OR (service.approval_div_dir IS NULL AND employee.emp_division IN ('Environment Education Training and Special Project', 'Additional Secretary Office', 'Media', 'Secretary Staff', 'Minister Office')))
        ORDER BY date;
    ");

    $data = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(array()); // Return an empty JSON array if the user is not authorized
    echo "error";
}
?>