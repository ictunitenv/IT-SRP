let requestId;

function updateProgress() {
    // event.preventDefault();
    requestId = document.getElementById("searchInput").value;
    let requestIdType = requestId.substring(0, 3);
    let tableSource;
    if (requestIdType === "REP") {
        tableSource = "repair";
    } else {
        tableSource = "service";
    }

    if (requestId !== "") {
        document.getElementById("noReqIdTable").style.display = "none";
        document.getElementById("noLabelsTable").style.display = "none";
        document.getElementById("noValidReqIdTable").style.display = "none";

        $.ajax({
            url: 'php/fetch_progressData.php',
            method: 'GET',
            data: {request_id: requestId, table_source: tableSource}, // Pass the request ID to PHP
            dataType: 'json',
            success: function (data) {
                // Populate the step progress with the new data
                if (data.length === 0) {
                    // If there are no records, display a message instead of the progress
                    document.getElementById("labelTable").style.display = "none";
                    document.getElementById("noReqIdTable").style.display = "none";
                    document.getElementById("noValidReqIdTable").style.display = "none";
                    document.getElementById("progressSteps").style.display = "none";
                    document.getElementById("noLabelsTable").style.display = "table";

                } else {
                    // Display the step progress based on the data fetched from the database
                    updateStepProgress(data);
                }
            },
            error: function () {
                console.log("Failed to fetch new data");
            }
        });
    } else {
        console.log("test" + requestId + "ing");
        document.getElementById("noReqIdTable").style.display = "table";
        document.getElementById("labelTable").style.display = "none";
        document.getElementById("noLabelsTable").style.display = "none";
        document.getElementById("noValidReqIdTable").style.display = "none";
        document.getElementById("progressSteps").style.display = "none";
    }

}

function updateStepProgress(data) {
    // Write logic to update the step progress based on the data
    // You can use the data to update each step accordingly
    // For example, for step 2 (div_dir_step):

    const emp_division = data.emp_division;
    const tableSource = data.table_source;
    const divDirAprvDate = data.div_dir_approval_date;
    const ictDirAprvDate = data.ict_dir_approval_date;
    const dateCompleted = data.completed_date;
    let remarks = data.remarks;


    if (data.record_id != null) {
        document.getElementById("noReqIdTable").style.display = "none";
        document.getElementById("noLabelsTable").style.display = "none";
        document.getElementById("noValidReqIdTable").style.display = "none";

        document.getElementById("labelTable").style.display = "table";
        document.getElementById("progressSteps").style.display = "flex";

        document.getElementById("valueReqId").textContent = data.request_id;
        document.getElementById("valueName").textContent = data.name_initials + " (" + data.emp_designation + ")";
        document.getElementById("valueDiv").textContent = data.emp_division;
        document.getElementById("valueDate").textContent = data.date;

        document.getElementById("ServInfo").textContent = data.table_source + " info : ";
        // document.getElementById("valueServInfo").textContent = data.service_issue;
        //display multi lined text as multi lines.
        const valueServInfo = document.getElementById("valueServInfo");
        valueServInfo.innerHTML = data.service_issue;

        if (tableSource === "Repair") {
            document.getElementById("valueDevType").textContent = data.device_type;
            document.getElementById("valueSerial").textContent = data.serial_no;
        } else {
            document.getElementById("valueDevType").textContent = "-not a repair-";
            document.getElementById("valueSerial").textContent = "-not a repair-";
        }

        const req_sub_icon = document.getElementById("req_sub_icon");
        const req_sub_text = document.getElementById("req_sub_text");
        const div_dir_icon = document.getElementById("div_dir_icon");
        const div_dir_text = document.getElementById("div_dir_text");
        const ict_dir_icon = document.getElementById("ict_dir_icon");
        const ict_dir_text = document.getElementById("ict_dir_text");
        const rep_status_icon = document.getElementById("rep_status_icon");
        const rep_status_text = document.getElementById("rep_status_text");
        const finish_icon = document.getElementById("finish_icon");
        const finish_text = document.getElementById("finish_text");

        const completeImg = "complete.png";     // if current step value = successful
        const declineImg = "decline.gif";       // if current step value = declined
        const prevStepDeclinedImg = "disabled.png";     // if prev step is declined
        const workingRepImg = "working1.gif";       // if the repair is in progress
        const workingSVcImg = "working2.png";       // if the service is in progress
        const partsPending = "partsPending.gif";       // if the service is in progress
        const svcRepFailedImg = "repair_serv_failed.gif";   // if the request failed
        const svcRepDeclinedImg = "repair_serv_declined.gif";   // if the request declined
        const processingImg = "processing.gif";
        // if this step don't have a value yet but prev step has
        const PendingImg = "pending.gif";   //Steps that are not started yet

        let disabledStepColor = 'rgb(191,191,191)';
        let completedStepsColor = 'rgba(0,0,0,0.55)';


        //Step 2 logic

        /* Clean up changes made in 4th step.
        * Bugfix for when checking progress for multiple requests that in different states
        */
        document.getElementById("repStatusItem").removeAttribute('onclick');
        document.getElementById("repStatusItem").style.cursor = "default";
        document.getElementById("rep_status_step").style.cursor = "default";

        // set text color of the previously completed steps (to highlight the one)
        if (data.approval_div_dir !== 1){
            req_sub_text.style.color = completedStepsColor;
        } else {
            req_sub_text.style.color = completedStepsColor;
            div_dir_text.style.color = completedStepsColor;
        }

        if (data.request_id === null) {
            // if the request_id does not exist, 'disable' this and every next step from here (this can be optimized further) [unneeded check]

            // Set the class
            document.getElementById("div_dir_step").className = "nav-link not-started";
            // Set the image/icon for the step
            div_dir_icon.innerHTML = '<img src="img/' + prevStepDeclinedImg + '">';
            // Set description of the step
            div_dir_text.innerHTML = 'Director\'s Approval<span style="font-size: small">(ICT)</span>';

            // change color of 'disabled' step descriptions
            div_dir_text.style.color = disabledStepColor;
            ict_dir_text.style.color = disabledStepColor;
            rep_status_text.style.color = disabledStepColor;
            finish_text.style.color = disabledStepColor;

        } else if (data.approval_div_dir === 1) {   // if current step value = approved/completed

            // Set the class
            document.getElementById("div_dir_step").className = "nav-link completed";
            // Set the image/icon for the step
            div_dir_icon.innerHTML = '<img src="img/' + completeImg + '">';
            // Set description of the step
            div_dir_text.innerHTML = '<b>Approved by the Director</b>' +
                '<span style="font-size: small">(' + emp_division + ' division)</span>' +
                '<span style="font-size: small">Approved on:</span> <span>' + divDirAprvDate + '</span>';

            // update color of current active step descriptions
            div_dir_text.style.color = "black";

            // set text color of the previously completed steps (to highlight the one)
            // req_sub_text.style.color = completedStepsColor;

        } else if (data.approval_div_dir === 0) {   // if current step value = declined

            // Set the class
            document.getElementById("div_dir_step").className = "nav-link declined";
            // Set the image/icon for the step
            div_dir_icon.innerHTML = '<img src="img/' + declineImg + '">';
            // Set description of the step
            div_dir_text.innerHTML = '<b>Declined by the Director</b>' +
                '<span style="font-size: small">(' + emp_division + ' division)</span>' +
                '<span style="font-size: small">Declined on:</span> <span>' + divDirAprvDate + '</span>';

            // update color of current active step descriptions
            div_dir_text.style.color = "black";

        } else if (data.approval_div_dir === null && data.request_id != null) {
            // when if this step does not have any value yet but the previous step does

            // Set the class
            document.getElementById("div_dir_step").className = "nav-link processing";
            // Set the image/icon for the step
            div_dir_icon.innerHTML = '<img src="img/' + processingImg + '">';
            // Set description of the step
            div_dir_text.innerHTML = '<b>Waiting for director\'s approval</b><span style="font-size: small">(' + emp_division + ' division)</span>';

            // update color of current active step descriptions
            div_dir_text.style.color = "black";

            // set text color of the previously completed steps (to highlight the one)
            // req_sub_text.style.color = completedStepsColor;

        } else {    //this means this step has not yet started
            // Set the class
            document.getElementById("div_dir_step").className = "nav-link not-started";
            // Set the image/icon for the step
            div_dir_icon.innerHTML = '<img src="img/' + PendingImg + '">';
            // Set description of the step
            div_dir_text.innerHTML = 'Director\'s Approval<span style="font-size: small">(' + emp_division + ' division)</span>';
        }


        //Step 3 logic

        /* Clean up changes made in 4th step.
        * Bugfix for when checking progress for multiple requests that in different states
        */
        document.getElementById("repStatusItem").removeAttribute('onclick');
        document.getElementById("repStatusItem").style.cursor = "default";
        document.getElementById("rep_status_step").style.cursor = "default";

        // set text color of the previously completed steps (to highlight the one)
        if (data.approval_ict_dir !== 1){
            req_sub_text.style.color = completedStepsColor;
            div_dir_text.style.color = completedStepsColor;
            ict_dir_text.style.color = "black";
        } else {
            req_sub_text.style.color = completedStepsColor;
            div_dir_text.style.color = completedStepsColor;
            ict_dir_text.style.color = completedStepsColor;
        }

        if (data.approval_div_dir === 0 || data.request_id === null) {
            //if any of previous two steps resulted in denial of service, 'disable' this and every next step from here (this can be optimized further)
            document.getElementById("ict_dir_step").className = "nav-link not-started";
            ict_dir_icon.innerHTML = '<img src="img/' + prevStepDeclinedImg + '">';
            ict_dir_text.innerHTML = 'Director\'s Approval<span style="font-size: small">(Environment Education Training, and Special Project)</span>';

            // change color of 'disabled' step descriptions
            // ict_dir_text.style.color = disabledStepColor;
            rep_status_text.style.color = disabledStepColor;
            finish_text.style.color = disabledStepColor;

        } else if (data.approval_ict_dir === 1) {   // if current step value = approved
            document.getElementById("ict_dir_step").className = "nav-link completed";
            ict_dir_icon.innerHTML = '<img src="img/' + completeImg + '">';
            ict_dir_text.innerHTML = '<b>Approved by the Director</b>' +
                '<span style="font-size: small">(Environment Education Training, and Special Project)</span>' +
                '<span style="font-size: small">Approved on:</span> <span>' + ictDirAprvDate + '</span>';

            // update color of current active step descriptions
            ict_dir_text.style.color = "black";

            // set text color of the previously completed steps (to highlight the one)
            // div_dir_text.style.color = completedStepsColor;

        } else if (data.approval_ict_dir === 0) {   // if current step value = declined
            document.getElementById("ict_dir_step").className = "nav-link declined";
            ict_dir_icon.innerHTML = '<img src="img/' + declineImg + '">';
            ict_dir_text.innerHTML = '<b>Declined by the Director</b>' +
                '<span style="font-size: small">(Environment Education Training, and Special Project)</span>' +
                '<span style="font-size: small">Declined on:</span> <span>' + divDirAprvDate + '</span>';

            // update color of current active step descriptions
            ict_dir_text.style.color = "black";

            // set text color of the previously completed steps (to highlight the one)
            // div_dir_text.style.color = completedStepsColor;

        } else if (data.approval_ict_dir === null && data.approval_div_dir != null) {
            // when if this step does not have any value yet but the previous step does
            document.getElementById("ict_dir_step").className = "nav-link processing";
            ict_dir_icon.innerHTML = '<img src="img/' + processingImg + '">';
            ict_dir_text.innerHTML = '<b>Waiting for director\'s approval</b><span style="font-size: small">(Environment Education Training, and Special Project)</span>';

            // update color of current active step descriptions
            ict_dir_text.style.color = "black";

            // set text color of the previously completed steps (to highlight the one)
            // div_dir_text.style.color = completedStepsColor;

        } else {    //this means this step has not yet started
            document.getElementById("ict_dir_step").className = "nav-link not-started";
            ict_dir_icon.innerHTML = '<img src="img/' + PendingImg + '">';
            ict_dir_text.innerHTML = 'Director\'s Approval<span style="font-size: small">(ICT)</span>';

        }


        //Step 4 logic

        // set text color of the previously completed steps (to highlight the one)
        if (data.status !== 1){
            req_sub_text.style.color = completedStepsColor;
            div_dir_text.style.color = completedStepsColor;
            ict_dir_text.style.color = completedStepsColor;
        } else {
            req_sub_text.style.color = completedStepsColor;
            div_dir_text.style.color = completedStepsColor;
            ict_dir_text.style.color = completedStepsColor;
            rep_status_text.style.color = completedStepsColor;
        }

        if (data.approval_ict_dir === 0 || data.approval_div_dir === 0 || data.request_id === null) {
            //if any of previous 3 steps resulted in denial of service, 'disable' this and every next step from here (this can be optimized further)
            document.getElementById("rep_status_step").className = "nav-link not-started";
            rep_status_icon.innerHTML = '<img class="item_image" src="img/' + prevStepDeclinedImg + '">';
            rep_status_text.innerHTML = 'ICT Unit';

            // change color of 'disabled' step descriptions
            // rep_status_text.style.color = disabledStepColor;
            finish_text.style.color = disabledStepColor;

            // Hide 'Click Here' banner (which shows remarks) in unneeded states of this step
            document.getElementById("clickHereBanner").style.display = "none";

        } else if (data.status === 1) {     // if current step value = completed
            document.getElementById("rep_status_step").className = "nav-link completed";
            rep_status_icon.innerHTML = '<img class="item_image clickable" src="img/' + completeImg + '">';
            rep_status_text.innerHTML = '<b>Repair Complete</b>' +
                '<span class="prog_details">Completed on:</span> <span>' + dateCompleted + '</span><span class="prog_details fontWeight" style="padding-top:5px">Remarks: </span> <span class="prog_details textAlign" style="">' + remarks + '</span>';

            // set text color of the previously completed steps (to highlight the one)
            // ict_dir_text.style.color = completedStepsColor;

            // update color of current active step descriptions
            rep_status_text.style.color = "black";

            // Display remarks
            document.getElementById("clickHereBanner").style.display = "flex";
            document.getElementById("clickHereBanner").style.marginTop = "-220px";
            document.getElementById("clickHereBanner").style.marginBottom = "190px";
            document.getElementById("rep_status_step").style.cursor = "pointer";
            document.getElementById("repStatusItem").style.cursor = "pointer";
            document.getElementById("repStatusItem").setAttribute('onclick', 'viewRemarks(\'' + remarks + '\',\'' + tableSource + '\')');    // adding the onclick attribute to this step item only when request status = completed

        } else if (data.status === 0) {    // if current step value = declined (status = 0)
            // Update the class and text for this step
            document.getElementById("rep_status_step").className = "nav-link declined";
            rep_status_icon.innerHTML = '<img class="item_image clickable" src="img/' + svcRepDeclinedImg + '">';
            rep_status_text.innerHTML = '<b>' + tableSource + ' has been declined by the ICT unit</b>';

            // update color of current active step descriptions
            rep_status_text.style.color = "black";

            // Display remarks
            document.getElementById("clickHereBanner").style.display = "flex";
            document.getElementById("clickHereBanner").style.marginTop = "-220px";
            document.getElementById("clickHereBanner").style.marginBottom = "190px";
            document.getElementById("rep_status_step").style.cursor = "pointer";
            document.getElementById("repStatusItem").style.cursor = "pointer";
            document.getElementById("repStatusItem").setAttribute('onclick', 'viewRemarks(\'' + remarks + '\',\'' + tableSource + '\')');    // adding the onclick attribute to this step item only when request status = declined

            // set text color of the previously completed steps (to highlight the one)
            // ict_dir_text.style.color = completedStepsColor;

        } else if (data.status === 9) {    // if current step value = outsources (status = 9)
            // Update the class and text for this step
            document.getElementById("rep_status_step").className = "nav-link declined";
            rep_status_icon.innerHTML = '<img class="item_image clickable" src="img/' + svcRepFailedImg + '">';
            rep_status_text.innerHTML = '<b>The ' + tableSource + ' is outsoursed</b>';

            // update color of current active step descriptions
            rep_status_text.style.color = "black";

            // Display remarks
            document.getElementById("clickHereBanner").style.display = "flex";
            document.getElementById("clickHereBanner").style.marginTop = "-220px";
            document.getElementById("clickHereBanner").style.marginBottom = "190px";
            document.getElementById("rep_status_step").style.cursor = "pointer";
            document.getElementById("repStatusItem").style.cursor = "pointer";
            document.getElementById("repStatusItem").setAttribute('onclick', 'viewRemarks(\'' + remarks + '\',\'' + tableSource + '\')');    // adding the onclick attribute to this step item only when request status = failed

            // set text color of the previously completed steps (to highlight the one)
            // ict_dir_text.style.color = completedStepsColor;

        } else if (data.status !== null) {    // if the request is in progress (status = 2-7)

            // '3' for 'Parts Pending' state for Repairs only
            if (data.status === 3 && tableSource === 'Repair') {
                document.getElementById("rep_status_step").className = "nav-link partsPending";
                rep_status_icon.innerHTML = '<img class="item_image clickable" src="img/' + partsPending + '">';

                rep_status_text.innerHTML = '<b>' + tableSource + ' paused untill parts arrive</b>' +
                    '</span><span class="prog_details fontWeight" style="padding-top:5px">Remarks: </span> <span class="prog_details textAlign" style="">' + remarks + '</span>';

                // update color of current active step descriptions
                rep_status_text.style.color = "black";

            } else {
                document.getElementById("rep_status_step").className = "nav-link working";
                if (tableSource === 'Repair') {
                    rep_status_icon.innerHTML = '<img class="item_image clickable" src="img/' + workingRepImg + '">';
                } else {
                    rep_status_icon.innerHTML = '<img class="item_image clickable" src="img/' + workingSVcImg + '">';
                }
                rep_status_text.innerHTML = '<b>' + tableSource + ' in Progress</b>' +
                    '</span><span class="prog_details fontWeight" style="padding-top:5px">Remarks: </span> <span class="prog_details textAlign" style="">' + remarks + '</span>';

                // update color of current active step descriptions
                rep_status_text.style.color = "black";

            }
            // Display remarks only if there is remarks
            if (remarks !== null || remarks !== '') {
                document.getElementById("clickHereBanner").style.display = "flex";
                document.getElementById("clickHereBanner").style.marginTop = "-220px";
                document.getElementById("clickHereBanner").style.marginBottom = "190px";
                document.getElementById("rep_status_step").style.cursor = "pointer";
                document.getElementById("repStatusItem").style.cursor = "pointer";
                document.getElementById("repStatusItem").setAttribute('onclick', 'viewRemarks(\'' + remarks + '\',\'' + tableSource + '\')');    // adding the onclick attribute to this step item only when request status = failed

                // set text color of the previously completed steps (to highlight the one)
                // ict_dir_text.style.color = completedStepsColor;

                // // Hide 'Click Here' banner (which shows remarks) in unneeded states of this step
                // document.getElementById("clickHereBanner").style.display = "none";

            }
        } else if (data.status === null && data.approval_ict_dir != null) {
            // when if this step does not have any value yet, but the previous step does
            document.getElementById("rep_status_step").className = "nav-link processing";
            rep_status_icon.innerHTML = '<img class="item_image" src="img/' + processingImg + '">';
            rep_status_text.innerHTML = '</b>The ' + tableSource + ' is yet to be addressed by the ICT Unit</b>';

            // set text color of the previously completed steps (to highlight the one)
            // ict_dir_text.style.color = completedStepsColor;

            // update color of current active step descriptions
            rep_status_text.style.color = "black";

            // Clean up changes made in previous IF cases.
            document.getElementById("repStatusItem").removeAttribute('onclick');
            document.getElementById("repStatusItem").style.cursor = "default";
            document.getElementById("rep_status_step").style.cursor = "default";

            // Hide 'Click Here' banner (which shows remarks) in unneeded states of this step
            document.getElementById("clickHereBanner").style.display = "none";

        } else {    //this means this step has not yet started
            document.getElementById("rep_status_step").className = "nav-link not-started";
            rep_status_icon.innerHTML = '<img class="item_image" src="img/' + PendingImg + '">';
            rep_status_text.innerHTML = 'ICT Unit';

            // Clean up changes made in previous IF cases.
            document.getElementById("repStatusItem").removeAttribute('onclick');
            document.getElementById("repStatusItem").style.cursor = "default";
            document.getElementById("rep_status_step").style.cursor = "default";

            // Hide 'Click Here' banner (which shows remarks) in unneeded states of this step
            document.getElementById("clickHereBanner").style.display = "none";
        }


        //Step 5 logic

        // set text color of the previously completed steps (to highlight the one)
        if (data.is_finished !== 1){
            req_sub_text.style.color = completedStepsColor;
            div_dir_text.style.color = completedStepsColor;
            ict_dir_text.style.color = completedStepsColor;
            rep_status_text.style.color = completedStepsColor;
        }

        if (data.status === 0 || data.status === 9 || data.approval_ict_dir === 0 || data.approval_div_dir === 0 || data.request_id === null) {
            //if any of previous four steps resulted in denial of service, 'disable' this and every next step from here (this can be optimized further)
            document.getElementById("finish_step").className = "nav-link not-started";
            finish_icon.innerHTML = '<img src="img/' + prevStepDeclinedImg + '">';
            finish_text.innerHTML = 'Request Completion';
            finish_text.style.color = disabledStepColor;

        } else if (data.is_finished === 1) {   // if current step value = successful
            document.getElementById("finish_step").className = "nav-link completed last";
            finish_icon.innerHTML = '<img src="img/' + completeImg + '">';
            if (tableSource === 'Repair') {
                finish_text.innerHTML = '<b>Request Complete!<br/><span style="color:' + completedStepsColor + '">Your device is ready</span></b>';
            } else {
                finish_text.innerHTML = '<b>The requested service has been successfully provided</b>';
            }

            // update color of current active step descriptions
            finish_text.style.color = "black";

            // set text color of the previously completed steps (to highlight the one)
            // rep_status_text.style.color = completedStepsColor;

        } else if (data.is_finished === 0) {   // if current step value = failed/error
            document.getElementById("finish_step").className = "nav-link declined";
            finish_icon.innerHTML = '<img src="img/' + declineImg + '">';
            finish_text.innerHTML = '<b>Finalizing failed</b>';

            // set text color of the previously completed steps (to highlight the one)
            // rep_status_text.style.color = completedStepsColor;

            // update color of current active step descriptions
            finish_text.style.color = "black";


        } else if (data.is_finished === null && data.status === 1) {
            // when if this step does not have any value yet but the previous step does
            document.getElementById("finish_step").className = "nav-link processing";
            finish_icon.innerHTML = '<img src="img/' + processingImg + '">';
            finish_text.innerHTML = '<b>Finalizing...</b>Please wait';

            // set text color of the previously completed steps (to highlight the one)
            // rep_status_text.style.color = completedStepsColor;

            // update color of current active step descriptions
            finish_text.style.color = "black";


        } else {    //this means this step has not yet started (probably won't be used in this step)
            document.getElementById("finish_step").className = "nav-link not-started";
            finish_icon.innerHTML = '<img src="img/' + PendingImg + '">';
            finish_text.innerHTML = 'Request Completion';
        }

    } else {
        // document.getElementById("labelTable").style.display = "flex";
        document.getElementById("noReqIdTable").style.display = "none";
        document.getElementById("noLabelsTable").style.display = "none";
        document.getElementById("labelTable").style.display = "none";
        document.getElementById("progressSteps").style.display = "none";
        document.getElementById("noValidReqIdTable").style.display = "table";
    }

}


function viewRemarks(remarks, tableSource) {
    // console.log(remarks + ' ' + tableSource);
    // console.log(remarks);
    let msg;
    if (remarks === null || remarks === '') {
        remarks = "-No Remarks-";
    }
    Swal.fire({
        // title: tableSource + ' Remarks',
        html: remarks,
        icon: "info",
        showCancelButton: false,
        confirmButtonColor: "#2196F3",
        confirmButtonText: "Ok",
        backdrop: 'rgba(0,0,0,0.6)',
    });
}