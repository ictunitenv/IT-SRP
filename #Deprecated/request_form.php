<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Request Form - IT Unit Service Request Portal - Ministry of Environment</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="../css/font-family.css" rel="stylesheet"/>
    <link href="../css/headerSection.css" rel="stylesheet"/>
    <link href="../css/request.css" rel="stylesheet">

    <!-- Include SweetAlert2 CSS and JavaScript -->
    <link rel="stylesheet" href="../css/libraries/sweetalert2.css">
    <script src="../js/libraries/sweetalert2.all.js"></script>
    <script src="../js/libraries/jquery-3.6.0.js"></script>

    <style>
        /*****TAB LAYOUT*****/
        /*==================*/
        .tab {
            display: none;
        }

        .active {
            display: block;
        }

        .tab_bar {
            position: relative;
            z-index: 1;
            display: flex;
            align-content: center;
            align-items: flex-end;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: flex-start;
            width: fit-content;
            margin: -68px 0 0 -23px;
            box-shadow: 0 0 11px 0 rgba(0, 0, 0, 0.1);
        }

        .tab_button {
            flex-direction: column;
            height: 45px;
            margin-right: -1px;
            padding: 10px 20px;
            cursor: pointer;
            text-align: center;
            border-radius: 0;
            background-color: hsl(218 100% 95% / 1);
            box-shadow: inset 0 0 10px 0 rgba(0, 0, 0, 0.1);
        }

        .active_button {
            background-color: hsl(223.33deg 100% 68.97%);
            box-shadow: none;
        }

        .active_button:hover {
            background-color: #333333;
        }

        .tab_button:hover {
            background-color: #ccc;
        }

        .tab_button.active {
            background-color: #ccc;
        }

        @media (max-width: 540px) {
            .tab_bar {
                width: 100%;
                margin: -73px 0 0 0;
            }

            .tab_button {
                width: 50%;
                height: 50px;
                margin-right: unset;
                padding-top: 12px;
            }
        }

    </style>
</head>
<body>
<header>
    <div class="logoBx" onclick="window.location.href='index.html';">
        <img alt="image" class="logo_png" src="../img/logo_placeholder.png"/>
        <h1 class="logo_text">IT Unit <br/> Service Request Portal</h1>
    </div>
    <div class="help_link">
        <a href="../help.html"><img alt="Help Icon" class="help-png" id="help-icon" src="../img/help.png"></a>
        <a href="../help.html"><p class="help_label">Help</p></a>
    </div>
</header>

<!-- Tab content -->
<div class="tab active" id="page1">
    <form action="" id="request_form" method="post" name="request_form">

        <!-- Code for the tab buttons -->
        <div class="tab_bar">
            <div class="tab_button active_button" onclick="showTab('page1')">Repair</div>
            <div class="tab_button" onclick="showTab('page2')">Service</div>
        </div>

        <table width="1024px">
            <tr>
                <td style="width: 28%">Employee ID: <span class="required_star">*</span></td>
                <td style="width: 72%"><input autofocus name="emp_id" placeholder="Enter your ID" required type="text">
                </td>
            </tr>

            <tr>
                <td>Employee Name <br> <span class="small_msg">(with initials)</span>: <span
                            class="required_star">*</span></td>
                <td><input name="emp_name" placeholder="Ex: ABC Rajapakse" required type="text"></td>
            </tr>

            <tr>
                <td>Designation:</td>
                <td><input name="emp_designation" placeholder="Enter your designation" type="text"></td>
            </tr>

            <tr>
                <td>Employee Division: <span class="required_star">*</span></td>
                <td><select name="emp_div">
                        <option value="" disabled selected>-Select your division-</option>
                        <option value="Accounts">Accounts</option>
                        <option value="Additional Secretary Office">Additional Secretary Office</option>
                        <option value="Administration">Administration</option>
                        <option value="Air Resource Management and National Ozone Unit">Air Resource Management and
                            National
                            Ozone Unit
                        </option>
                        <option value="Bio Diversity">Bio Diversity</option>
                        <option value="Climate Change">Climate Change</option>
                        <option value="Environment Education Training, Promotion and Special Project">Environment Education Training, Promotion and Special Project</option>
                        <option value="Environmental Pollution Control and Chemical Management">Environmental Pollution
                            Control
                            and Chemical Management
                        </option>
                        <option value="Environmental Planning and Economic">Environmental Planning and Economic</option>
                        <option value="Human Resource Development">Human Resource Development</option>
                        <option value="ICT Unit">ICT Unit</option>
                        <option value="Internal Audit">Internal Audit</option>
                        <option value="International Relation">International Relation</option>
                        <option value="Investigation">Investigation</option>
                        <option value="Legal">Legal</option>
                        <option value="Media">Media</option>
                        <option value="Ministry Office">Ministry Office</option>
                        <option value="Natural Resource">Natural Resource</option>
                        <option value="Policy Planning and Monitoring">Policy Planning and Monitoring</option>
                        <option value="Secretary Staff">Secretary Staff</option>
                    </select></td>
            </tr>
            <tr>
                <td>Date: <span class="required_star">*</span> <br> <span class="small_msg">(auto-filled)</span></td>
                <td>
                    <input id="datePicker1" name="date" placeholder="" required type="date">
                </td>
            </tr>

            <tr>
                <td>Device Type: <span class="required_star">*</span></td>
                <td><input type="text" name="device_type" list="devices" placeholder="Laptop/Desktop/Other" required>
                    <datalist id="devices">
                        <option value="Desktop"></option>
                        <option value="Laptop"></option>
                        <option value="Printer"></option>
                        <option value="Scanner"></option>
                        <option value="Other"></option>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td>Device's Serial No.:</td>
                <td><input name="device_serial" placeholder="Product serial number" type="text"></td>
            </tr>
            <tr>
                <td><label for="issue">Issue: <span class="required_star">*</span> <br> <span class="small_msg">(in brief)</span></label>
                </td>
                <td rowspan="3"><textarea id="issue" name="issue" placeholder="Your issue/required service" required
                                          rows="6"></textarea></td>
            </tr>
            <tr>
                <td rowspan="2">
                    <pre> </pre>
                </td>
            </tr>
        </table>

        <button class="submit_btn" name="submit" type="submit">Submit</button>
        <button class="cancel_btn" name="clear" onclick="alert('Request cancelled')">Cancel</button>
        <br>
    </form>
</div>

<div class="tab" id="page2">
    <form action="" id="service_form" method="post" name="service_form">

        <!-- Code for the tab buttons -->
        <div class="tab_bar">
            <div class="tab_button" onclick="showTab('page1')">Repair</div>
            <div class="tab_button active_button" onclick="showTab('page2')">Service</div>
        </div>

        <table width="1024px">
            <tr>
                <td style="width: 28%">Employee ID: <span class="required_star">*</span></td>
                <td style="width: 72%"><input autofocus name="emp_id" placeholder="Enter your ID" required type="text">
                </td>
            </tr>
            <tr>
                <td>Employee Name <br> <span class="small_msg">(with initials)</span>: <span
                            class="required_star">*</span></td>
                <td><input name="emp_name" placeholder="Ex: ABC Rajapakse" required type="text"></td>
            </tr>
            <tr>
                <td>Designation:</td>
                <td><input name="emp_designation" placeholder="Enter your designation" type="text"></td>
            </tr>
            <tr>
                <td>Employee Division: <span class="required_star">*</span></td>
                <td><select name="emp_div">
                        <option value="" disabled selected>-Select your division-</option>
                        <option value="Accounts">Accounts</option>
                        <option value="Additional Secretary Office">Additional Secretary Office</option>
                        <option value="Administration">Administration</option>
                        <option value="Air Resource Management and National Ozone Unit">Air Resource Management and
                            National
                            Ozone Unit
                        </option>
                        <option value="Bio Diversity">Bio Diversity</option>
                        <option value="Climate Change">Climate Change</option>
                        <option value="Environment Education Training, Promotion and Special Project">Environment Education Training, Promotion and Special Project</option>
                        <option value="Environmental Pollution Control and Chemical Management">Environmental Pollution
                            Control
                            and Chemical Management
                        </option>
                        <option value="Environmental Planning and Economic">Environmental Planning and Economic</option>
                        <option value="Human Resource Development">Human Resource Development</option>
                        <option value="ICT Unit">ICT Unit</option>
                        <option value="Internal Audit">Internal Audit</option>
                        <option value="International Relation">International Relation</option>
                        <option value="Investigation">Investigation</option>
                        <option value="Legal">Legal</option>
                        <option value="Media">Media</option>
                        <option value="Ministry Office">Ministry Office</option>
                        <option value="Natural Resource">Natural Resource</option>
                        <option value="Policy Planning and Monitoring">Policy Planning and Monitoring</option>
                        <option value="Secretary Staff">Secretary Staff</option>
                    </select></td>
            </tr>
            <tr>
                <td>Date: <span class="required_star">*</span> <br> <span class="small_msg">(auto-filled)</span></td>
                <td><input id="datePicker2" name="date" type="date" width="50px"></td>
            </tr>
            <tr>
                <td>Service: <span class="required_star">*</span> <br> <span class="small_msg">(in brief)</span></td>
                <td rowspan="3"><textarea name="service" placeholder="Your issue/required service" required
                                          rows="8"></textarea></td>
            </tr>
            <tr>
                <td rowspan="2">
                    <pre> </pre>
                </td>
            </tr>
        </table>

        <button class="submit_btn" name="submit" type="submit">Submit</button>
        <button class="cancel_btn" name="clear" onclick="alert('Request cancelled')">Cancel</button>
        <br>
        <br>
    </form>
</div>


<!--Toggling between tabs-->
<script>
    function showTab(tabId) {
        const tabs = document.querySelectorAll('.tab');
        const tabButtons = document.querySelectorAll('.tab_button');
        tabs.forEach(tab => {
            if (tab.id === tabId) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
    }

    // Disabling Datepicker
    document.getElementById("datePicker1").valueAsDate = new Date();
    document.getElementById("datePicker1").disabled = true;
    document.getElementById("datePicker2").valueAsDate = new Date();
    document.getElementById("datePicker2").disabled = true;
</script>
</body>
</html>


<!--** PHP Request submit code **-->
<!-- =========================== -->

<?php
if (isset($_POST['submit'])) {
    $empID = $_POST['emp_id'];
    $empName = $_POST['emp_name'];
    $designation = $_POST['emp_designation'];
    $empDivision = $_POST['emp_div'];
    $date = date('Y-m-d');
    $deviceType = isset($_POST['device_type']) ? $_POST['device_type'] : '';
    $serialNo = isset($_POST['device_serial']) ? $_POST['device_serial'] : '';
    $Issue = isset($_POST['issue']) ? $_POST['issue'] : '';
//    $issueFormatted = nl2br(htmlspecialchars($Issue));
    $issueFormatted = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", " <br/>", $Issue);   //Remove blank lines and preserve line separation for viewing in html documents
    $issueTrimmed = trim($issueFormatted);
    $Service = isset($_POST['service']) ? $_POST['service'] : '';
//    $serviceFormatted = nl2br(htmlspecialchars($Service));
    $serviceFormatted = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", " <br/>", $Service);   //Remove blank lines and preserve line separation for viewing in html documents
    $serviceTextTrimmed = trim($serviceFormatted);

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'ictunitportal';
    $con = mysqli_connect($host, $username, $password, $dbname);

    if (!$con) {
        die('Connection failed!' . mysqli_connect_error());
    }

    $result1 = false;
    $result2 = false;
    $requestID = '';

    if ($serviceTextTrimmed == '') {
        $checkRepairEntry = "SELECT record_id FROM repair WHERE emp_id = ? AND device_type = ? AND serial_no = ? AND issue = ? AND date = ?";
        $checkStmt = mysqli_prepare($con, $checkRepairEntry);
        mysqli_stmt_bind_param($checkStmt, 'sssss', $empID, $deviceType, $serialNo, $issueTrimmed, $date);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            echo 'Duplicate entry. This repair request has already been submitted.';
        } else {
            do {
                // Generate a random 4-digit number with a '0' at the front and Construct the request ID
                $randomNumber = str_pad(mt_rand(0, 9999), 4, '', STR_PAD_LEFT);
                $requestID = 'REP' . date('dmY') . '-' . $randomNumber;

                // Check if the request ID already exists in the repair table
                $checkRepairEntry = "SELECT record_id FROM repair WHERE request_id = ?";
                $checkStmt = mysqli_prepare($con, $checkRepairEntry);
                mysqli_stmt_bind_param($checkStmt, 's', $requestID);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);
            } while (mysqli_stmt_num_rows($checkStmt) > 0);

            $sql1 = "INSERT IGNORE INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES (?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($con, $sql1);
            mysqli_stmt_bind_param($stmt1, 'ssss', $empID, $empName, $designation, $empDivision);

            $sql2 = "INSERT INTO repair (device_type, serial_no, issue, date, emp_id, request_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($con, $sql2);
            mysqli_stmt_bind_param($stmt2, 'ssssss', $deviceType, $serialNo, $issueTrimmed, $date, $empID, $requestID);

            $result1 = mysqli_stmt_execute($stmt1);
            $result2 = mysqli_stmt_execute($stmt2);
        }
    } else {
        $checkServiceEntry = "SELECT record_id FROM service WHERE emp_id = ? AND service = ? AND date = ?";
        $checkStmt = mysqli_prepare($con, $checkServiceEntry);
        mysqli_stmt_bind_param($checkStmt, 'sss', $empID, $serviceTextTrimmed, $date);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            echo 'Duplicate entry. This service record already exists.';
        } else {
            do {
                // Generate a random 4-digit number with a '0' at the front and Construct the request ID
                $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $requestID = 'SVC' . date('dmY') . '-' . $randomNumber;

                // Check if the request ID already exists in the repair table
                $checkRepairEntry = "SELECT record_id FROM service WHERE request_id = ?";
                $checkStmt = mysqli_prepare($con, $checkRepairEntry);
                mysqli_stmt_bind_param($checkStmt, 's', $requestID);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);
            } while (mysqli_stmt_num_rows($checkStmt) > 0);

            $sql1 = "INSERT IGNORE INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES (?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($con, $sql1);
            mysqli_stmt_bind_param($stmt1, 'ssss', $empID, $empName, $designation, $empDivision);

            $sql2 = "INSERT INTO service (service, date, emp_id, request_id) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($con, $sql2);
            mysqli_stmt_bind_param($stmt2, 'ssss', $serviceTextTrimmed, $date, $empID, $requestID);

            $result1 = mysqli_stmt_execute($stmt1);
            $result2 = mysqli_stmt_execute($stmt2);
        }
    }

    if ($result1 && $result2) {
        echo "<script type='text/javascript'>Swal.fire({
                title: 'Request submitted!',
                html: 'Use below request ID to view progress.<br>Request ID: <b>$requestID</b>',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(function () {
                window.location.href = '';
            });
            </script>";
    }

    mysqli_close($con);
} else {
    exit();
}
?>

