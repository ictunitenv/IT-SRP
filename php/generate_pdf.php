<?php

require_once('libraries/TCPDF/tcpdf.php');

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=ictunitportal", "root", "");


if (isset($_GET['record_id'])) {
    $recordData = fetchDataForRecord($_GET['record_id']);
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//    echo ($_GET['record_id']);
    $reqID = $recordData['request_id'];
//    echo ($reqID);
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('ICT Unit (Ministry of Environment)');
    $pdf->SetTitle(ucfirst($recordData['table_source']) . ' Request (ID: ' . $reqID . ')');
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    $pdf->setAutoPageBreak(true, 5);

    // set margins
    $pdf->setFooterMargin(7);
    $pdf->SetMargins(PDF_MARGIN_LEFT, 7, PDF_MARGIN_RIGHT, true);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set font used in the document
    $pdf->SetFont('freeserif', '', 14, true);
    $pdf->AddPage();

    // set a custom font
//    $fontname = TCPDF_FONTS::addTTFfont('./libraries/TCPDF/fonts/iskpota.ttf', 'TrueTypeUnicode', '', 32);
//    $pdf->SetFont($fontname, '', 14, '', true);

    // set line height
//    $pdf->setCellHeightRatio(1);


    // ICT Director's approval
    if ($recordData['approval_ict_dir'] === 1) {
        $ictApproval = '<b>arrange</b> / <s>do not arrange</s>';
        $ictApprovalLabel = 'APPROVE';
        $ictApprovalDate = $recordData['ict_dir_approval_date'];
    } else if ($recordData['approval_ict_dir'] === 0) {
        $ictApproval = '<s>arrange</s> / <b>do not arrange</b>';
        $ictApprovalLabel = 'DECLINE';
        $ictApprovalDate = $recordData['ict_dir_approval_date'];
    } else {
        $ictApproval = '<i><b>do not yet arrange</b></i>';
        $ictApprovalLabel = '';
        $ictApprovalDate = '';
    }

    // Divisional Director's approval
    if ($recordData['approval_div_dir'] === 1) {
        $divApproval = '<b>approve</b> / <s>decline</s> the';
        $divApprovalLabel = 'APPROVE';
        $divApprovalDate = $recordData['div_dir_approval_date'];
    } else if ($recordData['approval_div_dir'] === 0) {
        $divApproval = '<s>approve</s> / <b>decline</b> the';
        $divApprovalLabel = 'DECLINE';
        $divApprovalDate = $recordData['div_dir_approval_date'];
    } else {
        $divApproval = '<i><b>have not yet approved</b></i>';
        $divApprovalLabel = '';
        $divApprovalDate = '';
    }

    // Value assignment based on the table source
    if ($recordData['table_source'] === 'repair') {
        $serviceType = 'Repair';
        $deviceType = $recordData['device_type'];
        $serialNo = $recordData['serial_no'];
    } else {
        $serviceType = 'Service';
        $deviceType = '[not a repair]';
        $serialNo = '[not a repair]';
    }
    $issue = $recordData['issue'];
    $empName = $recordData['name_initials'];
    $empDesignation = $recordData['emp_designation'];
    $date = $recordData['date'];

    if ($recordData['completed_date'] != null) {
        $completedDate = $recordData['completed_date'];
    } else {
        $completedDate = "";
    }

    $pdf->Image('libraries/TCPDF/images/logo.jpg', 27, 6, 13, 13, 'JPG', '', 'left', true, 270, '', false, false, 0, false, false, false);

    $headerHTML_0 = <<<EOD
    <h1 style="text-align: center"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Request for IT Related Repairs and Services </h1>
<!--    <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>! -->
    
    <style>
        table.header {
            width: 90%;
            display: flex;
            flex-direction: row;
            align-content: center;
        }
    </style>
    <table class="header" border="0" style="width: 100%">
    <tbody>
        <tr style="font-size: 12pt" >
            <td style="width: 4%"></td>
            <td style="width: 8%">To : </td>
            <td style="width: 64%">Information & Communication Technology Unit</td>
            <td rowspan="2" style="width:30%; text-align:left; font-size: 11pt; line-height: 0.8"> <div> Req.ID:<span style="font-weight: bold">&nbsp;$reqID</span></div></td>
        </tr>
        <tr style="font-size: 12pt">
            <td style="width: 4%"></td>
            <td>From : </td>
            <td>Director</td>
        </tr>
        <tr style="font-size: 12pt">
            <td> </td>
            <td> </td>
            <td><sub>........................................................................................................................................</sub> division</td>
        </tr>
        <tr style="font-size: 3pt; line-height: 2">
            <td></td>
            <td></td>
            <td></td>\
        </tr>
    </tbody>
    </table>
        <hr>
    
    
<!--    <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>-->
<!--    <p>Please check the source code documentation and other examples for further information.</p>-->
<!--    <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>-->
EOD;


    $repairHTML_1 = <<<EOD
    <table>
    <tbody>
        <tr style="font-size: 6pt">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 13pt;">
            <td style="width: 3%; line-height:0.5">1) </td>
            <td style="width: 97%; line-height:0.5" colspan="3">$serviceType Description <br/> </td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td></td>
            <td style="line-height:0" colspan="3"><span style="font-size: 10pt">(The relevant service or repair should be completed by the officer and recommended by the Divisional Head.)</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td> </td>
            <td colspan="3">Equipment to be repaired :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$deviceType&nbsp;</span>
            <br><span style="font-size: 10pt; line-height:1.5">(Laptop/Desktop/Printer/etc.)</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td style="width: 3%"></td>
            <td colspan="3">Serial number of the Equipment :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$serialNo&nbsp;</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td></td>
            <td style="width: 33%;">Issue of the equipment (in brief) : </td>
            <td style="width: 70%;"><span style="font-size: 12pt; font-weight: bold;">&nbsp;$issue &nbsp;</span></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 1">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td style="width: 3%"></td>
            <td style="width: 65%">Requesting officer's name :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$empName &nbsp;</span></td>
            <td style="width: 30%">Sign&nbsp;&nbsp;  : <sub>........................................................</sub></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%">Designation :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$empDesignation &nbsp;</span></td>
            <td style="width: 30%">Date&nbsp;&nbsp; : &nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$date&nbsp;</span></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 2">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.3">
            <td style="width: 3%"></td>
            <td style="width: 65%">I hereby $divApproval the above $serviceType. </td>
            <td style="width: 30%">Status :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold; color: #fff; background-color: #000; ">&nbsp;$divApprovalLabel&nbsp;</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%">Designation :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;Director&nbsp;</span></td>
            <td style="width: 30%">Date&nbsp;&nbsp; : &nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$divApprovalDate &nbsp;</span></td>
        </tr>
    </tbody>
    </table>
    <hr>
EOD;


    $serviceHTML_1 = <<<EOD
    <table>
    <tbody>
        <tr style="font-size: 6pt">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 13pt;">
            <td style="width: 3%; line-height:0.5">1) </td>
            <td style="width: 97%; line-height:0.5" colspan="3">$serviceType Description <br/> </td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td></td>
            <td style="line-height:0" colspan="3"><span style="font-size: 10pt">(The relevant service or repair should be completed by the officer and recommended by the Divisional Head.)</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td></td>
            <td style="width: 27%;">Service required (in brief): </td>
            <td style="width: 70%;"><span style="font-size: 12pt; font-weight: bold;">&nbsp;$issue &nbsp;</span></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 2">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td style="width: 3%"></td>
            <td style="width: 65%">Requesting officer's name :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$empName &nbsp;</span></td>
            <td style="width: 30%">Sign : <sub>..........................................................</sub></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%">Designation :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$empDesignation &nbsp;</span></td>
            <td style="width: 30%">Date : &nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$date&nbsp;</span></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 2">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.3">
            <td style="width: 3%"></td>
            <td style="width: 65%">I hereby $divApproval the above $serviceType. </td>
            <td style="width: 30%">Status :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold; color: #fff; background-color: #000; ">&nbsp;$divApprovalLabel&nbsp;</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%">Designation :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;Director&nbsp;</span></td>
            <td style="width: 30%">Date&nbsp;&nbsp; : &nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$divApprovalDate &nbsp;</span></td>
        </tr>
    </tbody>
    </table>
    <hr>
EOD;


    $ictDirector_2 = <<<EOD
    <table>
    <tbody>
        <tr style="font-size: 6pt">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 13pt;">
            <td style="width: 3%; line-height:0.5">2) </td>
            <td style="width: 97%; line-height:0.5" colspan="3">Information and Communication Technology Officer<br/></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td></td>
            <td style="width: 97%;">Please $ictApproval for the above $serviceType to be carried out. </td>
        </tr>
        <tr style="font-size: 6pt; line-height: 3">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.3">
            <td style="width: 3%"></td>
            <td style="width: 65%; text-align: start;">Director&nbsp;</td>
            <td style="width: 30%">Status :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold; color: #fff; background-color: #000; ">&nbsp;$ictApprovalLabel&nbsp;</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%"><span style="font-size: 11pt;">(Education Training and Research)</span></td>
            <td style="width: 30%">Date&nbsp;&nbsp; : &nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$ictApprovalDate&nbsp;</span></td>
        </tr>
    </tbody>
    </table>
    <hr>
EOD;


    $ictUnit_3 = <<<EOD
    <table>
    <tbody>
        <tr style="font-size: 6pt">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 13pt;">
            <td style="width: 3%; line-height:0.5">3) </td>
            <td style="width: 97%; line-height:0.5" colspan="3">For use of Information and Communication Technology Unit<br/> </td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td> </td>
            <td colspan="3">Date of receipt of the request to the division :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$date (dummy)&nbsp;</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td style="width: 3%"></td>
            <td colspan="3">Date of inspection :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$date (dummy)&nbsp;</span></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 1">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td></td>
            <td style="width: 95%;">$serviceType can / cannot be done :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;Can be done (dummy)&nbsp;</span></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td style="width: 3%"></td>
            <td style="width: 65%">Reason if the $serviceType cannot be done :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;- (dummy)&nbsp;</span></td>
            <td style="width: 30%"></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td style="width: 3%"></td>
            <td style="width: 65%">Recommendation :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;Replace PSU (dummy)&nbsp;</span></td>
            <td style="width: 30%"></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 1">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td style="width: 3%"></td>
            <td style="width: 95%">Required accessory details for $serviceType (if applicable) :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;EVGA 400W N1 100-N1-0400-L1 POWER SUPPLY (dummy).
            &nbsp;</span></td>
            <td></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 2">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.3">
            <td style="width: 3%"></td>
            <td style="width: 65%"><sub>................................................................................................................................</sub></td>
            <td style="width: 30%">Sign :&nbsp;<sub>..........................................................</sub></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%">Information and Communication Technology Officer</td>
            <td style="width: 30%">Date :&nbsp;<sub>..........................................................</sub></td>
        </tr>
    </tbody>
    </table>
    <hr>
EOD;


    $accDiv_4 = <<<EOD
    <table>
    <tbody>
        <tr style="font-size: 6pt">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 13pt;">
            <td style="width: 3%; line-height:0.5">4) </td>
            <td style="width: 97%; line-height:0.5" colspan="3">Accounts / Admin division<br/> </td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td style="width: 3%"></td>
            <td style="width: 95%">The accessories required for this repair are recommended and requested to be provided.&nbsp;&nbsp;</td>
            <td></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 3">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%"><sub>...............................................................................................................................</sub></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.3">
            <td style="width: 3%"></td>
            <td style="width: 65%">Director / Senior Assistant Secretary (Admin)</td>
            <td style="width: 30%">Seal&nbsp; : <sub>..........................................................</sub></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%"><sub>...............................................................................................................................</sub>&nbsp;division</td>
            <td style="width: 30%">Date :&nbsp;<sub>..........................................................</sub></td>
        </tr>
        <tr style="font-size: 6pt; line-height: 0.9">
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
    </table>
    <hr>
EOD;


    $completion_5 = <<<EOD
    <table>
    <tbody>
        <tr style="font-size: 6pt">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 13pt;">
            <td style="width: 3%; line-height:0.5">5) </td>
            <td style="width: 97%; line-height:0.5" colspan="3">Completion<br/> </td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td> </td>
            <td colspan="3">Date on which the relevant $serviceType was performed :&nbsp;&nbsp;<span style="font-size: 12pt; font-weight: bold;">&nbsp;$completedDate&nbsp;</span></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.1">
            <td style="width: 3%"></td>
            <td colspan="3">The relevant $serviceType was carried out by the Information and Communication Technology Unit.</td>
        </tr>
        <tr style="font-size: 6pt; line-height: 3">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1">
            <td style="width: 3%"></td>
            <td style="width: 65%"><sub>.....................................................................................</sub></td>
            <td style="width: 30%">Date :&nbsp;<sub>..........................................................</sub></td>
        </tr>
        <tr style="font-size: 12pt; line-height:1.5">
            <td style="width: 3%"></td>
            <td style="width: 65%">Service-received officer's signature</td>
        </tr>
        <tr style="font-size: 6pt; line-height: 0.5">
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
    </table>
EOD;


    $ictDirector_2 = utf8_decode($ictDirector_2);

    if ($recordData['table_source'] === 'repair') {
        $pdf->writeHTMLCell(0, 0, '', '', $headerHTML_0 . $repairHTML_1 . $ictDirector_2 . $ictUnit_3 . $accDiv_4 . $completion_5, 0, 1, 0, true, '', true);
    } else {
        $pdf->writeHTMLCell(0, 0, '', '', $headerHTML_0 . $serviceHTML_1 . $ictDirector_2 . $ictUnit_3 . $accDiv_4 . $completion_5, 0, 1, 0, true, '', true);
    }


//    $pdf->Cell(0, 10, 'Date: ' . $recordData['date'], 0, 1);


    $pdf->Cell(0, 10, '', 0, 1);


//    $pdf->writeHTMLCell(0, 0, '', '', '‍ෙගදර ගෙදර ග‍ෙදර මව් ඇ ඒ වි ටි ඤ ඥ ' . $divApproval . ' above ' . $recordData['table_source'] . ' request.', 0, 1, 0, true, '', true);

    $pdf->IncludeJS("print();");

    $pdf->Output($serviceType . ' Request (ID: ' . $reqID . ').pdf', 'I');

} else {
    echo "Record ID is missing.";
}

function fetchDataForRecord($recordId)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            repair.record_id, 
            CASE 
                WHEN repair.device_type IS NOT NULL AND repair.serial_no IS NOT NULL THEN CONCAT(repair.issue, '<br/><p><b>Device type:- </b>', repair.device_type, '<br/><b>Serial no:- </b>', repair.serial_no,'<br/><b>Requested by:- </b>', employee.name_initials, ' (',employee.emp_division, ')<br/><b>Request ID:- </b>', repair.request_id, '</p>') 
                ELSE repair.issue 
            END as service_issue, 
            repair.device_type,
            repair.serial_no,
            repair.issue,
            repair.date, 
            repair.emp_id, 
            repair.request_id,
            repair.approval_div_dir, 
            repair.div_dir_approval_date, 
            repair.approval_ict_dir, 
            repair.ict_dir_approval_date, 
            repair.completed_date, 
            employee.name_initials, 
            employee.emp_division,
            employee.emp_designation,
            'repair' as table_source
        FROM repair  
        JOIN employee ON repair.emp_id = employee.emp_id 
        WHERE repair.request_id = :recordId
        
        UNION ALL 
        
        SELECT 
            service.record_id, 
            CONCAT(service.service, '<br/><p><b>Requested by:- </b>', employee.name_initials, ' (',employee.emp_division, ')<br/> <b>Request ID:- </b>', service.request_id, '</p>') as service_issue, 
            NULL as device_type,
            NULL as serial_no,
            service.service AS issue,
            service.date, 
            service.emp_id, 
            service.request_id,
            service.approval_div_dir, 
            service.div_dir_approval_date, 
            service.approval_ict_dir, 
            service.ict_dir_approval_date, 
            service.completed_date, 
            employee.name_initials, 
            employee.emp_division,
            employee.emp_designation,
            'service' as table_source
        FROM service 
        JOIN employee ON service.emp_id = employee.emp_id 
        WHERE service.request_id = :recordId");

    $stmt->bindParam(':recordId', $recordId, PDO::PARAM_STR);
    $stmt->execute();
    $recordData = $stmt->fetch(PDO::FETCH_ASSOC);
    return $recordData;
}

?>
