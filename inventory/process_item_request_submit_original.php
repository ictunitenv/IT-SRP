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
//    $emp_name = $_POST['emp_name'];
//    $emp_designation = $_POST['emp_designation'];
//    $request_month = $_POST['request_month'];
//    $division = $_POST['division'];
//    $no_of_employees = $_POST['no_of_employees'];
    $itemData = $_POST['itemData'];

    // Check if itemData is an array
    if (is_array($itemData)) {
        // Build the SQL query
        $sql = "INSERT INTO item (item_desc , current_amount, required_amount, emp_id) VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $db->prepare($sql);

        if ($stmt) {
            // Loop through the itemData array and insert data into the database
            foreach ($itemData as $item) {
                $item_description = $item['item_desc'];
                $current_amount = $item['current_amount'];
                $required_amount = $item['required_amount'];
                $emp_id = $item['emp_id'];

                // Bind parameters
                $stmt->bind_param("siis", $item_description, $current_amount, $required_amount, $emp_id);

                // Execute the prepared statement
                if ($stmt->execute()) {
//                    echo "Data inserted successfully for item: $item_description\n";
                    echo "success";
                } else {
//                    echo "Error inserting data for item: $item_description\n";
                    echo "error_data_insert";
                }
            }

            // Close the prepared statement
            $stmt->close();
        } else {
//            echo "Statement preparation failed: " . $db->error;
            echo "error_prepare";
        }

    } else {
//        echo "Invalid input data. Expected an array.";
        echo "error_invalid_data";
    }

    // Close the database connection
    $db->close();
}
?>
