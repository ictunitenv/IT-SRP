<?php
// Start a session
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");

// Check if the user is logged in and has a division assigned
if (isset($_SESSION['role']) && $_SESSION['role'] === 'director' && isset($_SESSION['division'])) {
    $division = $_SESSION['division'];

    $stmt = $pdo->prepare("
        SELECT
            repair.record_id,
            CASE
                WHEN repair.device_type IS NOT NULL 
                AND repair.serial_no IS NOT NULL 
                THEN CONCAT(
                    repair.issue, '<br/><br/><p><b>Device type:- </b>',
                    repair.device_type, '<br/><b>Serial no:- </b>', 
                    repair.serial_no,'</p>'
                )
                ELSE 
                    repair.issue END AS service_issue,
                    repair.date,
                    repair.emp_id,
                    repair.approval_div_dir,
                    employee.name_initials,
                    employee.emp_division,
                    'repair' AS table_source
        FROM 
            repair JOIN employee 
            ON repair.emp_id = employee.emp_id
        WHERE
            repair.approval_div_dir IS NULL
            AND employee.emp_division = :division
        
        UNION ALL
        
        SELECT
            service.record_id,
            service.service AS service_issue,
            service.date,
            service.emp_id,
            service.approval_div_dir,
            employee.name_initials,
            employee.emp_division,
            'service' AS table_source
        FROM 
            service JOIN employee 
            ON service.emp_id = employee.emp_id
        WHERE
            service.approval_div_dir IS NULL
            AND employee.emp_division = :division
        ORDER BY date;
    ");

    $stmt->bindParam(':division', $division, PDO::PARAM_STR);
    $stmt->execute();

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
