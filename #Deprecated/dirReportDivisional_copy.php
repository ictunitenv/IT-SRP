<?php
// Start a session
session_start();

// Check if the user is logged in as a director
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'director') {
    // Redirect to the login page
    header("Location: login.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report - IT Unit Service Request Portal - Ministry of Environment</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="../css/font-family.css" rel="stylesheet"/>
    <link href="../css/headerSection.css" rel="stylesheet"/>
    <link href="../css/report.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/libraries/sweetalert2.css">

    <script src="../js/libraries/sweetalert2.all.js"></script>
    <script src="../js/libraries/jquery-3.6.0.js"></script>
</head>
<header>
    <div class="logoBx" onclick="window.location.href='index.html';">
        <img alt="image" class="logo_png" src="../img/logo_placeholder.png"/>
        <h1 class="logo_text">IT Unit <br/> Service Request Portal</h1>
    </div>
    <div class="help_link">
        <a href="../help.html"><img alt="Help Icon" class="help-png" id="help-icon" src="../img/help.png"></a>
        <a href="../help.html"><label class="help_label" for="help-icon">Help</label></a>
    </div>
</header>
<body>

<div class="titleBox">
    <h1 class="title">Divisional Director's Approval</h1>
</div>

<div class="logout_btn" onclick="window.location.href='../php/logout.php';">
    <p class="logout_link">Logout</p>
</div>

<div class="report_table">
    <table>
        <thead>
        <tr>
            <th style="width:5%">No.</th>
            <th style="width:35%">Service / Issue</th>
            <th style="width:15%">Date</th>
            <th style="width:15%">Requested by</th>
            <th style="width:fit-content">Approval</th>
        </thead>
        <tbody>
        <?php
        $pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");
        $stmt = $pdo->query("(SELECT repair.record_id, 
            CASE 
                WHEN repair.device_type IS NOT NULL AND repair.serial_no IS NOT NULL THEN CONCAT(repair.issue, '<br/><p>Device type:- ', repair.device_type, '<br/>Serial no:- ', repair.serial_no,'</p>')
                ELSE repair.issue
            END as service_issue, 
            repair.date, 
            repair.emp_id, 
            repair.approval_div_dir, 
            employee.name_initials
    FROM repair
    JOIN employee ON repair.emp_id = employee.emp_id
    WHERE repair.approval_div_dir IS NULL)
    UNION ALL
    (SELECT service.record_id, 
            service.service as service_issue, 
            service.date, 
            service.emp_id, 
            service.approval_div_dir, 
            employee.name_initials
    FROM service
    JOIN employee ON service.emp_id = employee.emp_id
    WHERE service.approval_div_dir IS NULL)
    ORDER BY date
");
        $count = 1;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$count}</td>";
            echo "<td width='30%'>{$row['service_issue']}</td>";
            echo "<td>{$row['date']}</td>";
            echo "<td>{$row['name_initials']}</td>";
            echo "<td class='approval_column'>";
            echo "<button class='approval_option approve' onclick='approve_alert(this)' data-record-id='{$row['record_id']}'>Approve</button>\n";
            echo "<button class='approval_option decline'>Decline</button>";
            echo "</td>";
            echo "</tr>";
            $count++;
        }

        ?>

        </tbody>
    </table>

    <!--    <button type="submit" name="submit">Submit</button>-->
</div>

<!--<button class="learn-more">Print</button>-->
<script>
    function approve_alert(button) {
        const recordId = button.getAttribute('data-record-id');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, approve it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Send an AJAX request to update the approval_div_dir column
                // Replace 'your_update_script.php' with the actual script that updates the database.
                $.ajax({
                    url: 'php/your_update_script.php',
                    method: 'POST',
                    data: {record_id: recordId},
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire("Approved!", "", "success").then(() => {
                                // Reload the current page after the user clicks "Ok" in the success alert
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Error", "Failed to update approval status", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Failed to update approval status", "error");
                    }
                });
            }
        });
    }

</script>

<div class="footer">
</div>
</body>
</html>