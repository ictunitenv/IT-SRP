<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish a MySQLi database connection (replace with your own connection code)

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'ictunitportal';
    $db = new mysqli($host, $username, '', $dbname);

    // Check the connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Your input array from JavaScript
    $emp_id = $_POST['emp_id'];
    $emp_name = $_POST['emp_name'];
    $emp_designation = $_POST['emp_designation'];
    $empDivision = $_POST['emp_division'];
//    $request_month = $_POST['request_month'];
//    $no_of_employees = $_POST['no_of_employees'];

//    $itemData = $_POST['itemData'];   // this array is initialized inside the "if($exec2)" condition

    // if the employee details fields are not empty
    if ($emp_id !== '' || $emp_name !== '' || $emp_designation !== '' || $empDivision !== '') {

        // Insert employee information if it doesn't exist
        $sql_emp = "INSERT IGNORE INTO employee (emp_id, name_initials, emp_designation, emp_division) VALUES (?, ?, ?, ?)";
        $stmt2 = $db->prepare($sql_emp);

        if ($stmt2) {
            $stmt2->bind_param("ssss", $emp_id, $emp_name, $emp_designation, $empDivision);
            $exec2 = $stmt2->execute();
            $stmt2->close();

            if ($exec2) {
                // Check if itemData is passed
                if (isset($_POST['itemData'])) {
                    // Assign itemData from POST to $itemData array
                    $itemData = $_POST['itemData'];

                    // Build the SQL query
                    $sql = "INSERT INTO item (emp_id, item_desc , current_amount, required_amount,item_remarks) VALUES (?, ?, ?, ?, ?)";

                    // Prepare the statement
                    $stmt = $db->prepare($sql);

                    if ($stmt) {
                        $errorOccurred = false;
                        // Loop through the itemData array and insert data into the database
                        foreach ($itemData as $item) {
                            $item_description = $item['item_desc'];
                            $current_amount = $item['current_amount'];
                            $required_amount = $item['required_amount'];
                            $item_remark = $item['item_remarks'];

                            // Bind parameters
                            $stmt->bind_param("ssiis", $emp_id, $item_description, $current_amount, $required_amount, $item_remark);

                            // Execute the prepared statement
                            if ($stmt->execute()) {
                                // echo "Data inserted successfully for item: $item_description\n";
                                $errorOccurred = false;
                            } else {
                                // echo "Error inserting data for item: $item_description\n";
                                $errorOccurred = true;
                            }
                        }

                        if (!$errorOccurred) {
                            // echo "Data inserted successfully for item: $item_description\n";
                            echo "success";
                        } else {
                            // echo "Error inserting data for item: $item_description\n";
                            echo "error_data_insert";
                        }
                        // Close the prepared statement
                        $stmt->close();
                    } else {
                        // echo "Statement preparation failed: " . $db->error;
                        echo "error_prepare";
                    }
                } else {
                    // echo "Invalid input data. Expected an array.";
                    echo "error_invalid_data";
                }
            } else {
                echo "error_employee_insert";
            }
        } else {
            echo "error_employee_prepare";
        }
    } else {
        echo "error_employee_details_blank";
    }


    $db->close();
}
?>
