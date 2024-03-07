<?php
if (isset($_POST["submit"])) {
    if (isset($_POST["approval"]) && is_array($_POST["approval"])) {
        // Replace the database connection details accordingly
        $conn = mysqli_connect("localhost", "root", "", "ictunitportal");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        foreach ($_POST["record_id"] as $recordId) {
//            $recordId = intval($recordId);
////            $isChecked = isset($_POST['approval']) ? "approved" : "denied";
//            $isChecked = $_POST["approval"];
//
//            if ($isChecked == "approved"){
//                $sql = "UPDATE repair SET approval = 1 WHERE record_id = $recordId";
//            } else{
//                $sql = "UPDATE repair SET approval = 0 WHERE record_id = $recordId";
//            }

            $approvalValue = (in_array($recordId, $_POST["approval"])) ? 1 : 0;

            // Prepare and execute an SQL query to update the 'approval' column in the database
            $sql1 = "UPDATE repair SET approval = ? WHERE record_id = ?";
            $stmt1 = mysqli_prepare($conn, $sql1);
            mysqli_stmt_bind_param($stmt1, "si", $approvalValue, $recordId);
//            $stmt1->execute([$approvalValue, $recordId]);




//            if (mysqli_query($conn, $sql)) {
//                // Record updated successfully
//            } else {
//                // Handle the update error
//                echo "Error updating record: " . mysqli_error($conn);
//            }
        }

        mysqli_close($conn);
    }
}

// Redirect back to your original page or perform any other actions
header("Location: dirReportDivisional.php");
?>
<?php
