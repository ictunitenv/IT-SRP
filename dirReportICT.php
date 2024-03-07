<?php
// Start a session
session_start();
$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];
// Check if the user is logged in as a director
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'director' || !$_SESSION['give_access'])) {
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
    <h1 class="title">Computer Repair Requests</h1><br><span class="title_division" id="titleDivision">dummy div</span>
</div>

<div class="logout_btn" onclick="window.location.href='php/logout.php';">
    <p class="logout_link">Logout</p>
</div>

<div class="report_table">
    <table id="data-table">
        <thead>
        <tr>
            <th style="width:5%;text-align: center;">No.</th>
            <th style="width:35%">Service / Issue</th>
            <th style="width:15%;text-align:center">Date</th>
            <th style="width:15%">Requested by</th>
            <th style="width:fit-content;text-align:center">Approval</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>

<div id="showHistoryBtn" class="history_button" onclick="showHistoryTable()"><span>Show Approval History</span></div>

<div class="report_table history_table" id="history_report_table">
    <br>
    <h2 style="margin-left: auto;margin-right:auto; margin-bottom: 30px;">Approval History</h2>
    <table id="history-table">
        <thead>
        <tr>
            <th style="width:5%;text-align: center;">No.</th>
            <th style="width:35%">Service / Issue</th>
            <th style="width:15%;text-align:center">Date</th>
            <th style="width:15%">Requested by</th>
            <th style="width:fit-content;text-align:center">Approval</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>

<div class="footer"></div>

</body>
</html>

<script>
    let showHistoryBtnClickedCount = 0;    // To determine the show/hide state of the history button
    let directorName = '<?php echo $name;?>';
    let userID = '<?php echo $user_id;?>';
    document.getElementById("titleDivision").textContent = "(" + directorName + ")";

    function updateTable() {
        $.ajax({
            url: 'php/fetch_data_ict_div.php',
            method: 'GET',
            dataType: 'json', // Expect JSON data
            success: function (data) {
                // Clear existing table data
                $('#data-table tbody').empty();
                $('#history-table tbody').empty();

                // Populate the table with the new data
                if (data.length === 0) {
                    // If there are no records, display a message in the table body
                    $('#data-table tbody').append('<tr><td colspan="5" class="empty_table">No records available</td></tr>');
                    $('#history-table tbody').append('<tr><td colspan="5" class="empty_table">No records available</td></tr>');
                } else {
                    // If there are records available, but no approved records, then show a msg in the 'History' table
                    let count = 0;
                    let approvalBgClr;

                    data.forEach(function (item) {
                        if (item.approval_ict_dir !== null) {
                            count += 1;
                        }
                        if (count === 0) {
                            $('#history-table tbody').replaceWith('<tr><td colspan="5" class="empty_table">No records available</td></tr>');
                        }
                    });

                    // Populate requests and history tables with the new data
                    data.forEach(function (item, index) {
                        const tableSource = item.table_source; // Assuming there's a field named table_source in data

                        if ((item.approval_div_dir === 1 || (item.emp_division === "Environment Education Training and Special Project" && item.approval_div_dir === null)) && item.approval_ict_dir === null) {
                            $("#data-table tbody").append(`
                            <tr>
                                <td style="text-align: center;">${index + 1}</td>
                                <td width=30%>${item.service_issue}</td>
                                <td class="report_column_align">${item.date}</td>
                                <td style="vertical-align: middle;">${item.name_initials} <br> <p><b>Division: </b>${item.emp_division}</p></td>
                                <td class=approval_column>
                                    <button class="approval_option approve" data-record-id="${item.record_id}" onclick="approveOrDecline(this, 'approve', '${tableSource}',${userID})">Approve</button>
                                    <button class="approval_option decline" data-record-id="${item.record_id}" onclick="approveOrDecline(this, 'decline', '${tableSource}',${userID})">Decline</button>
                                </td>
                            </tr>
                            `);
                        }

                        // Populating systory table
                        if (item.approval_div_dir === 1 && item.approval_ict_dir === 1) {
                            approvalBgClr = "rgba(8,168,0,0.05)";
                            $("#history-table tbody").append(`
                            <tr>
                                <td style="text-align: center;">${index + 1}</td>
                                <td width=30%>${item.service_issue}</td>
                                <td class="report_column_align">${item.date}</td>
                                <td style="vertical-align: middle;">${item.name_initials} <br> <p><b>Division: </b>${item.emp_division}</p></td>
                                <td class=approval_column style="background-color: ${approvalBgClr}">
                                    <span class="approval_option approve"><b>Approved</b></span>
                                </td>
                            </tr>
                            `);
                        }

                        if (item.approval_div_dir === 1 && item.approval_ict_dir === 0) {
                            approvalBgClr = "rgba(255,0,0,0.03)";
                            $("#history-table tbody").append(`
                            <tr>
                                <td style="text-align: center;">${index + 1}</td>
                                <td width=30%>${item.service_issue}</td>
                                <td class="report_column_align">${item.date}</td>
                                <td style="vertical-align: middle;">${item.name_initials} <br> <p><b>Division: </b>${item.emp_division}</p></td>
                                <td class=approval_column style="background-color: ${approvalBgClr}">
                                    <span class="approval_option approve"><b>Declined</b></span>
                                </td>
                            </tr>
                            `);
                        }
                        else {
                            console.log(item.issue)
                        }
                    });
                }
            },
            error: function () {
                console.log("Failed to fetch new data");
            }
        });
    }


    updateTable();


    function approveOrDecline(button, action, tableSource, userID) {
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
                    url: "php/update_approval_ict_div.php",
                    method: "POST",
                    data: {
                        record_id: recordId,
                        action: action, // Pass the action (approve or decline)
                        table_source: tableSource, // Pass the table source (repair or service)
                        user_id: userID, // Pass the director's ID
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

    // Show & hide the History table when the "Show History" button clicked
    function showHistoryTable() {
        showHistoryBtnClickedCount += 1;
        if (showHistoryBtnClickedCount % 2 === 1) {
            document.getElementById("history_report_table").style.display = "flex";
        } else {
            document.getElementById("history_report_table").style.display = "none";
        }

    }

</script>