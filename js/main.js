function clearRepair(type) {
    if (type === 'page2') {
        document.getElementById("emp_id_rep").value = "";
        document.getElementById("emp_name_rep").value = "";
        document.getElementById("emp_designation_rep").value = "";
        document.getElementById("emp_div_rep").selectedIndex = 0;
        document.getElementById("device_type_rep").value = "";
        document.getElementById("device_serial_rep").value = "";
        document.getElementById("issue_rep").value = "";
    } else {
        document.getElementById("emp_id_svc").value = "";
        document.getElementById("emp_name_svc").value = "";
        document.getElementById("emp_designation_svc").value = "";
        document.getElementById("emp_div_svc").selectedIndex = 0;
        document.getElementById("service_svc").value = "";
    }

}

$(document).ready(function () {
    $('#request_form').submit(function (e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Get form data here and perform checks
        let empId = $('#emp_id_rep').val();
        let empName = $('#emp_name_rep').val();
        let empDesignation = $('#emp_designation_rep').val();
        let empDiv = $('#emp_div_rep').val();
        let deviceType = $('#device_type_rep').val();
        let deviceSerial = $('#device_serial_rep').val();
        let serviceRequest = $('#issue_rep').val();
        let tableSource = 'repair';

        insertData(empId, empName, empDesignation, empDiv, deviceType, deviceSerial, serviceRequest, tableSource);
    });
});


$(document).ready(function () {
    $('#service_form').submit(function (e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Get form data here and perform checks
        let empId = $('#emp_id_svc').val();
        let empName = $('#emp_name_svc').val();
        let empDesignation = $('#emp_designation_svc').val();
        let empDiv = $('#emp_div_svc').val();
        let deviceType = "";
        let deviceSerial = "";
        let serviceRequest = $('#service_svc').val();
        let tableSource = 'service';

        insertData(empId, empName, empDesignation, empDiv, deviceType, deviceSerial, serviceRequest, tableSource);
    });
});

function insertData(empId, empName, empDesignation, empDiv, deviceType, deviceSerial, serviceRequest, tableSource) {
    // Use $.ajax to send data to process_request.php
    $.ajax({
        url: 'php/process_request.php',
        method: 'POST',
        data: {
            // Key-value pairs for form fields
            formData: {
                emp_id: empId,
                emp_name: empName,
                emp_designation: empDesignation,
                emp_div: empDiv,
                device_type: deviceType,
                device_serial: deviceSerial,
                service_request: serviceRequest,
                // issue: issue,
                // service: service,
                table_source: tableSource
            }
        },
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                Swal.fire({
                    title: "Request submitted!",
                    html: "Use below request ID to view progress.<br>" +
                        "<span style='font-size: small'>(Please keep the request ID in a safe place)</span><br>" +
                        "Request ID: <b>" + data.requestID + "</b>",
                    icon: "success",
                    confirmButtonText: "Ok",
                    backdrop: 'rgba(0,0,0,0.6)'
                }).then(function () {
                    window.location.href = "";
                });
            } else {
                // Handle duplicate entry with a Swal.fire alert
                Swal.fire({
                    title: "Duplicate Entry",
                    text: "This request has already been submitted.",
                    html: "Use below request ID to view progress.<br>" +
                        "<span style='font-size: small'>(Please keep the request ID in a safe place)</span><br>" +
                        "Request ID: <b>" + data.requestID + "</b>",
                    icon: "error",
                    confirmButtonText: "Ok",
                    backdrop: 'rgba(0,0,0,0.6)'
                });
            }
        },
        error: function () {
            console.log("Failed to submit request");
        }
    });
}



