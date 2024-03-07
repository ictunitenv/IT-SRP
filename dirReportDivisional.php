<?php
// Start a session
session_start();
$division = $_SESSION['division'];

// Check if the user is logged in as a director
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'director' || $_SESSION['division'])) {
    // Redirect to the login page
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Divisional Directors Report - IT Unit Service Request Portal - Ministry of Environment</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="css/font-family.css" rel="stylesheet"/>
    <link href="css/headerSection.css" rel="stylesheet"/>
    <link href="css/report.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/libraries/sweetalert2.css">

    <script src="js/libraries/sweetalert2.all.js"></script>
    <script src="js/libraries/jquery-3.6.0.js"></script>

</head>
<header>
    <div class="logoBx" onclick="window.location.href='index.html';">
        <img alt="image" class="logo_png" src="./img/logo_placeholder.png"/>
        <h1 class="logo_text">IT Unit <br/> Service Request Portal</h1>
    </div>
    <div class="help_link">
        <a href="help.html"><img alt="Help Icon" class="help-png" id="help-icon" src="img/help.png"></a>
        <a href="help.html"><label class="help_label" for="help-icon">Help</label></a>
    </div>
</header>
<body>

<div class="titleBox">
    <h1 class="title">Computer Repair Requests</h1><br><span class="title_division" id="titleDivision">directors-division</span>
</div>

<div class="logout_btn" onclick="window.location.href='php/logout.php';">
    <p class="logout_link">Logout</p>
</div>

<div class="report_table">
    <table id="data-table">
        <thead>
        <tr>
            <th style="width:5%;text-align:center">No.</th>
            <th style="width:35%">Service / Issue</th>
            <th style="width:15%;text-align:center">Date</th>
            <th style="width:15%">Requested by</th>
            <th style="width:fit-content;text-align:center">Approval</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    let division = '<?php echo $division;?>';
    document.getElementById("titleDivision").textContent = "(" + division + " Division)";

    function updateTable() {
        $.ajax({
            url: 'php/fetch_data_divisional.php',
            method: 'GET',
            dataType: 'json', // Expect JSON data
            success: function (data) {
                // Clear existing table data
                $('#data-table tbody').empty();

                // Populate the table with the new data
                if (data.length === 0) {
                    // If there are no records, display a message in the table body
                    $('#data-table tbody').append('<tr><td colspan="5" class="empty_table">No records available</td></tr>');
                } else {
                    // Populate the table with the new data
                    data.forEach(function (item, index) {
                        const tableSource = item.table_source; // Assuming there's a field named table_source in data

                        $("#data-table tbody").append(`
                        <tr>
                            <td style="text-align: center;">${index + 1}</td>
                            <td width=30%>${item.service_issue}</td>
                            <td class="report_column_align">${item.date}</td>
                            <td style="vertical-align: middle;">${item.name_initials} <br> <p><b>Division: </b>${item.emp_division}</p></td>
                            <td class=approval_column>
                                <button class="approval_option approve" data-record-id="${item.record_id}" onclick="approveOrDecline(this, 'approve', '${tableSource}')">Approve</button>
                                <button class="approval_option decline" data-record-id="${item.record_id}" onclick="approveOrDecline(this, 'decline', '${tableSource}')">Decline</button>
                            </td>
                        </tr>
                    `);
                    });
                }
            },
            error: function () {
                console.log("Failed to fetch new data");
            }
        });
    }

    updateTable();


    function approveOrDecline(button, action, tableSource) {
        let recordId = button.getAttribute("data-record-id");
        let confirmationText = action === "approve" ? "Approve!" : "Decline!";

        Swal.fire({
            title: `Are you sure you want to ${action}?`,
            text: "You won't be able to revert this decision!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: action === "approve" ? "#08a800bf" : "#ff0000bf",
            cancelButtonColor: "#808080",
            confirmButtonText: confirmationText,
            backdrop: 'rgba(0,0,0,0.6)',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "php/update_approval_divisional.php",
                    method: "POST",
                    data: {
                        record_id: recordId,
                        action: action, // Pass the action (approve or decline)
                        table_source: tableSource, // Pass the table source (repair or service)
                    },
                    success: function (response) {
                        if (response === "success") {
                            Swal.fire(action === "approve" ? "Approved!" : "Declined!", "", "success").then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Error", "Failed to update approval status", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Failed to update approval status", "error");
                    },
                });
            }
        });
    }


</script>

<div class="footer">
</div>
</body>
</html>