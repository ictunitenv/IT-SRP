<?php
// Start a session
session_start();

// Check if the user is logged in as a director
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ICT Unit Admin Panel - IT Unit Service Request Portal - Ministry of Environment</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="css/font-family.css" rel="stylesheet"/>
    <link href="css/headerSection.css" rel="stylesheet"/>
    <link href="css/report.css" rel="stylesheet"/>
    <link href="css/report-2.css" rel="stylesheet"/>
    <!--    <link href="css/originals/report-2-original.css" rel="stylesheet"/>-->
    <link rel="stylesheet" href="css/libraries/sweetalert2.css">

    <script src="js/libraries/sweetalert2.all.js"></script>
    <script src="js/libraries/jquery-3.6.0.js"></script>
    <script src="js/http_kit.fontawesome.com_9ff8af40aa.js" crossorigin="anonymous"></script>
</head>
<header>
    <div class="logoBx sticky" id="pageHeader" onclick="window.location.href='index.html';">
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
    <h1 class="title">ICT Unit Admin Panel</h1>
<!--    <br><span class="title_division" id="titleDivision">(ICT Unit)</span>-->
</div>

<div class="logout_btn" onclick="window.location.href='php/logout.php';">
    <p class="logout_link">Logout</p>
</div>

<div class="report_table">
    <table id="data-table">
        <thead>
        <tr>
            <th style="width:5%;text-align:center">No.</th>
            <th style="width:40%">Service / Issue</th>
            <th style="width:13%;text-align:center">Date Submitted</th>
            <th style="width:13%;text-align:center">Date Approved</th>
            <th style="width:15%;text-align:center">Director's Approval</th>
            <th style="width:fit-content;text-align:center">Options</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    function updateTable() {
        $.ajax({
            url: 'php/fetch_data_ictUnit.php',
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
                        const tableSource = item.table_source;
                        const tableSourceCaps = capitalizeFirstLetter(tableSource);
                        let approvalState = item.approval_ict_dir;
                        let requestId = item.request_id;
                        let status = item.status;
                        let approvalText;
                        let approvalBgClr;
                        let statusButton;

                        if (approvalState === null) {
                            approvalText = "Not yet approved by the director";
                            approvalBgClr = "hsla(0, 0%, 60%, 0.06)";
                        } else if (approvalState === 1) {
                            approvalText = "Approved";
                            approvalBgClr = "rgba(8,168,0,0.05)";
                        } else {
                            approvalText = "Declined";
                            approvalBgClr = "rgba(255,0,0,0.03)";
                        }

                        console.log(requestId + " " + approvalText);

                        // TODO : Service and repair should have 2 different start buttons
                        // TODO : For services : After clicked on 'Start Service' it should update to 'Service Complete'. Then it can be disabled.


                        // if (status === null && approvalState === 1) {
                        //     statusButton = `<button class="options enabled startRepair" id="startRepairBtn"  onclick="updateRequestStatus('${item.request_id}','${item.table_source}');"><i class="fa-solid fa-pen-to-square"></i>Start Repair</button>`;
                        //
                        // } else if (status === null && approvalState === null || approvalState === 0) {
                        //     statusButton = `<button class="options disabled" id="disabledStatusBtn" disabled><i class="fa-solid fa-pen-to-square"></i>Start Repair</button>`;
                        //
                        // } else {
                        //     statusButton = `<button class="options enabled updateStatus" id="statusBtnBtn"><i class="fa-solid fa-pen-to-square"></i>Update Status</button>`;
                        // }


                        if (status === null && approvalState === 1) {
                            if (tableSource === 'repair') {
                                // Brown color 'Start Repair' button
                                statusButton = `<button class="options enabled startRepair" id="startRepairBtn" onclick="updateRequestStatus('${requestId}','${tableSource}',2,'${item.name_initials}','${item.emp_division}');"><i class="fa-solid fa-screwdriver-wrench"></i>Start Repair</button>`;
                            }
                            else {
                                // Blue color 'Start Service' button
                                statusButton = `<button class="options enabled startService" id="startServiceBtn" onclick="updateRequestStatus('${requestId}','${tableSource}',2,'${item.name_initials}','${item.emp_division}');"><i class="fa-solid fa-screwdriver-wrench"></i>Start Service</button>`;
                            }

                        } else if (status === null && (approvalState === null || approvalState === 0)) {
                            statusButton = `<button class="options disabled" id="disabledStatusBtn" disabled><i class="fa-solid fa-screwdriver-wrench"></i>Unavailable</button>`;

                        } else if (status === 1 && item.is_finished === null) {
                                statusButton = `<button class="options enabled finishService" id="startServiceBtn" onclick="updateRequestStatus('${requestId}','${tableSource}','endJob');"><i class="fa-solid fa-check"></i>Finish the ${tableSource}</button>`;

                        } else if (status === 9 && item.is_finished === null) {
                                statusButton = `<button class="options enabled finishService outsourced" id="startServiceBtn" onclick="updateRequestStatus('${requestId}','${tableSource}','endJob');"><i class="fa-solid fa-circle-exclamation"></i>Finish the ${tableSource}<span>${tableSourceCaps} Outsourced.</span></button>`;

                        } else if (status === 9 && item.is_finished === 1) {
                            statusButton = `<button class="options disabled requestCompl outsourced" id="disabledStatusBtn" disabled><i class="fa-solid fa-check big"></i>Finished<span>Failed ${tableSource}.</span></button>`;

                        } else if (status === 1 && item.is_finished === 1) {
                                statusButton = `<button class="options disabled requestCompl" id="disabledStatusBtn" disabled><i class="fa-solid fa-check big"></i>Finished</button>`;

                        } else if (status === 0) {
                                statusButton = `<button class="options disabled declined" id="disabledStatusBtn" disabled><i class="fa-solid fa-xmark big"></i>Declined</button>`;

                        } else {
                            // statusButton = `<button class="options enabled updateStatus" id="statusButton"><i class="fa-solid fa-pen-to-square"></i>Update Status</button>`;

                            statusButton = `<button class="options enabled updateStatus" id="statusButton" onclick="updateRequestStatus('${requestId}','${tableSource}',null);"><i class="fa-solid fa-pen-to-square"></i>Update Status</button>`;
                        }

                        $("#data-table tbody").append(`
                        <tr style="background-color: ${approvalBgClr}">
                            <td style="text-align: center">${index + 1}</td>
                            <td width=30%>${item.service_issue}</td>
                            <td class="report_column_align">${item.date}</td>
                            <td class="report_column_align">${item.ict_dir_approval_date}</td>
                            <!-- <td>${item.name_initials}</td> -->
                            <td class="report_column_align">
                                <!-- <button class="approval_option approve" data-record-id="${item.record_id}" onclick="approveOrDecline(this, 'approve', '${tableSource}')">Approve</button>
                                <button class="approval_option decline" data-record-id="${item.record_id}" onclick="approveOrDecline(this, 'decline', '${tableSource}')">Decline</button> -->
                                <p class="default"> ${approvalText}</p>
                            </td>
                            <td class="report_column_align">
                                <div class="options_column">
                                    ${statusButton}
                                    <div class="options_group">
                                        <button class="options enabled" onclick="window.location.href='php/generate_pdf.php?record_id=${requestId}'"><i class="fa-solid fa-file"></i>View</button>
                                        <button class="options enabled"><i class="fa-solid fa-print"></i>Print<br><span style="font-size: 0.7em">(Firefox only)</span> </button>
                                    </div>
                                </div>
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
        // document.getElementById("startRepairBtn").setAttribute('onclick','writeLED(1,1)')
    }

    updateTable();


    function updateRequestStatus(reqId, tableSource, value, name, division) {
        console.log(reqId);
        let capitalTableSource = capitalizeFirstLetter(tableSource);

        let requestStatus = "";
        let requestStatusRest = "";
        let requestContent = "";
        let successMsg = "";
        let alertTitle = "";

        // From HERE....
        if (value === 0) {
            requestStatus = "\'DECLINE\'";
        } else if (value === 1) {
            requestStatusRest = "as \'COMPLETE\'"
        } else if (value === 2) {
            requestStatus = "\'START\'";
            alertTitle = 'Start the ' + tableSource + '?';
            successMsg = capitalTableSource + " Started"
            requestContent = "<span style='font-size:0.8em;display: flex;flex-wrap: nowrap;flex-direction: column;margin-top: 10px;'>" +
                "<b>Request ID: </b>" + reqId +
                "<b>By: </b>" + name +
                "<b>From: </b>" + division + "</span>";
        } else if (value === 9) {
            requestStatusRest = "as \'FAILED\'"
        //  To HERE...
        //  Are kinda useless now :(

        } else if (value === "endJob"){
            requestStatus = "FINISH";
            alertTitle = 'Finish the ' + tableSource + '?';
            successMsg = capitalTableSource + " Finished"
            value = 1;
        }else {
            requestStatus = "update";
        }


        // Separate the request status update based on "requestStatus" value
        if (requestStatus !== 'update') {
            // SweetAlert for other cases except 'update'
            Swal.fire({
                title: alertTitle,
                html: 'Are you sure you wish to <b>' + requestStatus + '</b> this ' + tableSource + requestStatusRest + '?<br>' +
                    requestContent,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#08a800bf",
                cancelButtonColor: "#f54444",
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                backdrop: 'rgba(0,0,0,0.6)',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "php/update_request_status.php",
                        method: "POST",
                        data: {
                            request_id: reqId,
                            table_source: tableSource,
                            value: value,
                            request_status: requestStatus,
                        },
                        success: function (response) {
                            if (response === "success") {
                                Swal.fire(successMsg , "", "success").then(() => {
                                    window.location.reload();
                                    // let test = document.getElementById('checkbox');
                                    // $('#checkbox').val('Hello');
                                });
                            } else {
                                Swal.fire("Error", "Failed to update request status", "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "Failed to update request status", "error");
                        },
                    });
                }
            });
        } else {
            // SweetAlert for 'update' case
            let additionalStatusOption;
            if (tableSource === "repair") {
                additionalStatusOption = '<option value="3">Wait for parts</option>';
            }
            Swal.fire({
                title: 'Update ' + tableSource + ' Details',
                html: '<label for="updateDropdown">Select the ' + tableSource + ' status : </label>' +
                    '<br>' +
                    '<select id="updateDropdown" style="margin-bottom: 15px; width: 250px; height: 35px; border-radius: 7px; padding: 5px;">' +
                    '   <option value="2" selected>' + capitalTableSource + ' started</option>' +
                    additionalStatusOption +
                    '   <option value="9">' + capitalTableSource + ' is being outsourced</option>' +
                    '   <option value="1">' + capitalTableSource + ' complete</option>' +
                    '   <option value="0">Decline ' + tableSource + '</option>' +
                    '</select>' +
                    '<label for="updateTextField" style="display: block; text-align: center;">' + capitalTableSource + ' Remarks</label>' +
                    '<textarea id="updateTextField" style="width: 350px; height: 70px; resize: none; border-radius: 7px; padding: 5px;" type="text" placeholder="Enter remarks"></textarea>',
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#08a800bf",
                cancelButtonColor: "#f54444",
                confirmButtonText: "Update",
                cancelButtonText: "Cancel",
                backdrop: 'rgba(0,0,0,0.6)',
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request for 'update' case
                    let requestStatusValue = $('#updateDropdown').val();
                    let updateRequestStatus = parseInt(requestStatusValue);
                    let requestRemarks = $('#updateTextField').val();
                    $.ajax({
                        url: "php/update_request_status_details.php",
                        method: "POST",
                        data: {
                            request_id: reqId,
                            table_source: tableSource,
                            update_request_status: updateRequestStatus,
                            request_remarks: requestRemarks,
                        },
                        success: function (response) {
                            if (response === "success") {
                                Swal.fire("Updated", "", "success").then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire("Error", "Failed to update request status details", "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "Failed to update request status details", "error");
                        },
                    });
                }
            });
        }
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // TODO : Create this function
    // function updateStatusPopup (){
    //     Swal.fire({
    //         title: 'Start the ' + tableSource + '?',
    //         html: 'Are you sure you wish to <b>' + requestStatus + '</b> this ' + tableSource + requestStatusRest + '?<br>' +
    //             requestContent',
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#08a800bf",
    //         cancelButtonColor: "#f54444",
    //         confirmButtonText: "Yes",
    //         cancelButtonText: "Cancel",
    //         backdrop: 'rgba(0,0,0,0.6)',
    //         preConfirm: function (){
    //             console.log($('#checkbox').val())
    //             return new Promise((resolve,reject) => {
    //                 resolve({
    //                     hey: $('#checkbox').val("hello")
    //                 })
    //             })
    //         }
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: "php/update_request_status.php",
    //                 method: "POST",
    //                 data: {
    //                     request_id: reqId,
    //                     table_source: tableSource,
    //                     value: value,
    //                 },
    //                 success: function (response) {
    //                     if (response === "success") {
    //                         Swal.fire("Updated", "", "success").then(() => {
    //                             window.location.reload();
    //                             // let test = document.getElementById('checkbox');
    //                             $('#checkbox').val('Hello');
    //                         });
    //                     } else {
    //                         Swal.fire("Error", "Failed to update request status", "error");
    //                     }
    //                 },
    //                 error: function () {
    //                     Swal.fire("Error", "Failed to update request status", "error");
    //                 },
    //             });
    //         }
    //     });
    //
    // }


</script>

<div class="footer">
</div>
</body>
</html>