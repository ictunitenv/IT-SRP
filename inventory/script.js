// // Set current month
// document.getElementById("request_month").value = new Date().getMonth() + 1;
//
// // Display current date
// const dateTime = new Date().toDateString();
// document.getElementById("current_date").innerHTML = 'Today is : ' + dateTime;
const maxItemFieldAmount = 25;

// Button for testing purposes to disable input fields on director's view
function directorView() {
    for (i = 1; i < maxItemFieldAmount; i++) {
        if (document.getElementById("item_name_" + i)) {
            const itemName = document.getElementById("item_name_" + i);
            itemName.disabled = true;
            itemName.style.outline = "none";
            itemName.style.border = "1px solid #ccc";
            itemName.style.backgroundColor = "#80808036";
            itemName.classList.add('disabled');

            const avlAmount = document.getElementById("avl_amt_" + i);
            avlAmount.disabled = true;
            avlAmount.style.outline = "none";
            avlAmount.style.border = "1px solid #ccc";
            avlAmount.style.backgroundColor = "#80808036";
            avlAmount.classList.add('disabled');
            // const tds = document.querySelectorAll('input');
            // tds.forEach((td) => {
            //     td.style.setProperty('--input_outline', '#00ff00');
            // });

            // let selector = 'span[redLabel="required_star' + i + '"]';


            // document.querySelector('span[redLabel="required_star' + i + '"]').style.display='none';
        }
    }
}


// Submit form when "ENTER" key press

$('#request_form').on('keyup keypress', function (e) {
    let key = e.key;

    if (key === 'Enter') {
        if (e.target.tagName !== 'TEXTAREA' && e.target.id !== 'submitBtn_requestItem' && e.target.id !== 'new_item_btn') {
            e.preventDefault();
            // let scrollPosition = window.scrollY;
            // formValidation(scrollPosition);
            // alert("Enter Pressed");
            return false;
        }
    }
});

// document.addEventListener('focus', function (event) {
//     if (event.target.id.startsWith('item_note_')){
//         console.log(event.target.id);
//     } else {
//
//     }
// });


// $('#request_form').on('keyup keypress', function (e) {
//     let keyCode = e.keyCode || e.which;
//     if (keyCode === 13) {
//         let scrollPosition = window.scrollY;
//         formValidation(scrollPosition);
//         e.preventDefault();
//         return false;
//     }
// });

// Trigger addNewItemField() when 'ADD NEW ITEM' button is clicked
document.getElementById("new_item_btn").addEventListener("click", function (event) {
    event.preventDefault();
    addNewItemField();

    // Scroll to bottom of the page to show the newly added item field
    window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'})
});


// Submit the form when 'SUBMIT' button is clicked
document.getElementById("submitBtn_requestItem").addEventListener("click", function (event) {
    event.preventDefault()

    Swal.fire({
        title: "Confirm Submission",
        html: "Please double-check your request. <br> Are you sure you want to submit?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Continue",
        cancelButtonText: "Cancel",
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with submission
            formValidation(0);  // form submission is done inside the formValidation function
            // scrollToTop();

        }
    });

    // return false;
});


function scrollToTop() {
    if ('scrollBehavior' in document.documentElement.style) {
        window.scrollTo({top: 0, behavior: 'smooth'});
    } else {
        // Fallback for older browsers that don't support smooth scrolling
        window.scrollTo(0, 0);
    }
}


function formValidation(y) {
    let isValidated = null;

    const empName = document.getElementById("emp_name");
    const empDesignation = document.getElementById("emp_designation");
    const empId = document.getElementById("emp_id");
    const empDivision = document.getElementById("division");

    if (empId.validity.valueMissing || empName.validity.valueMissing || empDesignation.validity.valueMissing || empDivision.validity.valueMissing) {
        isValidated = false;
    } else {
        isValidated = true;
    }

    if (isValidated) {
        // alert("test")
        submitItemRequest();
    } else {
        Swal.fire({
            didOpen: () => window.scrollTo({top: y, behavior: 'smooth'}),
            title: "Empty Employee Details",
            text: "The employee details fields have not been filled. Please fill them and try submitting again",
            icon: "error",
            didClose: () => window.scrollTo({top: y, behavior: 'smooth'}),
        });
    }
}


// Remove an Item Field when clicked on the "X" button
document.addEventListener('click', function (event) {
    event.preventDefault();

    // console.log(event.target.id);

    if (event.target.id.startsWith('closeItemBtn_') || event.target.id.startsWith('closeItemXIcon_')) {
        // const itemFieldId = event.target.id;
        const itemFieldIdHalf = event.target.id.split('_')[1];
        const removingElement = document.getElementById("item_field_" + itemFieldIdHalf);
        removingElement.className = 'item_remove withFrame';
        removingElement.style.opacity = 0;
        removingElement.style.height = 0;

        updateItemFieldNumber(itemFieldIdHalf);
        // console.log(itemFieldIdHalf);

        setTimeout(function () {
            // removingElement.style.display = "none"
            removingElement.parentNode.removeChild(removingElement);
        }, 440);
    }


    // removingElement.parentNode.removeChild(removingElement);0

    // let scrollPosition = window.scrollY;
    // window.scrollTo({top: scrollPosition-50, behavior: 'smooth'})

});


function updateItemFieldNumber(removedItemIdNumber) {
    let currentId = parseInt(removedItemIdNumber);
    const nextItemId = parseInt(removedItemIdNumber) + 1;
    for (let i = nextItemId; i <= maxItemFieldAmount; i++) {
        const nextItem = document.getElementById("item_field_"+i);
        if (nextItem) {
            console.log("Item Exist : "+nextItem.id);
            nextItem.id = "item_field_"+currentId;
            document.getElementById("item_label_"+i).textContent = "Item "+currentId;
            document.getElementById("item_label_"+i).id = "item_label_"+currentId;
            document.getElementById("item_field_table_"+i).id = "item_field_table_"+currentId;
            document.getElementById("item_desc_"+i).id = "item_desc_"+currentId;
            document.getElementById("item_name_"+i).id = "item_name_"+currentId;
            document.getElementById("avl_amt_field_"+i).id = "avl_amt_field_"+currentId;
            document.getElementById("avl_amt_"+i).id = "avl_amt_"+currentId;
            document.getElementById("req_amt_field_"+i).id = "req_amt_field_"+currentId;
            document.getElementById("req_amt_"+i).id = "req_amt_"+currentId;
            document.getElementById("closeItemBtn_"+i).id = "closeItemBtn_"+currentId;
            document.getElementById("closeItemXIcon_"+i).id = "closeItemXIcon_"+currentId;
            document.getElementById("item_remark_" + i).id = "item_remark_" + currentId;
            document.getElementById("item_note_" + i).id = "item_note_" + currentId;
            currentId+=1;
        } else {
            console.log("No Item : item field "+i);
        }
    }
}


let newItemIdNumber = 2;  //global variable to use as base for incrementing IDs of input fields
// let newItemIdNumber = 2;

function addNewItemField() {
    // const nextItemId = maxItemFieldAmount;
    for (let i = maxItemFieldAmount; i > 1; i--) {
        const newItem = document.getElementById("item_field_"+i);
        if (newItem) {
            newItemIdNumber = i+1;
            break;
        }
    }

    if (newItemIdNumber <= maxItemFieldAmount) {
        document.getElementById("add_new_item")
            .insertAdjacentHTML("beforebegin",
                "<div class=\"item_field withFrame\" id=\"item_field_" + newItemIdNumber + "\">\n" +
                "                <span class=\"item_label\" id=\"item_label_" + newItemIdNumber + "\">Item " + newItemIdNumber + " </span>\n" +
                "                <table class=\"item_field_table\" id=\"item_field_table_" + newItemIdNumber + "\">\n" +
                "                    <tbody>\n" +
                "                    <tr>\n" +
                "                        <td class=\"item_desc\" colspan=\"3\" id=\"item_desc_" + newItemIdNumber + "\">\n" +
                "                            <input class=\"item_name\" id=\"item_name_" + newItemIdNumber + "\" name=\"item_name\"\n" +
                "                                   placeholder=\"Item name / description\" type=\"text\"><br>\n" +
                "                            <span class=\"small_label\">Item name / description\n" +
                "                                <span class=\"required_star redLabel\" redLabel=\"required_star\"> Required *  </span>\n" +
                "                            </span>\n" +
                "                        </td>\n" +
                "                        <td class=\"item_remark\" rowspan=\"2\" id=\"item_remark_" + newItemIdNumber + "\">\n" +
                "                            <textarea tabindex=\"-1\" class=\"item_note\" id=\"item_note_" + newItemIdNumber + "\" name=\"item_note\" placeholder=\"Additional notes\" rows=\"4\"></textarea><br>\n" +
                "                            <span class=\"small_label\">Additional notes (optional)</span>\n" +
                "                        </td>\n" +
                "                    <tr>\n" +
                "                        <td class=\"avl amt\" id=\"avl_amt_field_" + newItemIdNumber + "\">\n" +
                "                            <input class=\"available amount\" id=\"avl_amt_" + newItemIdNumber + "\" name=\"avl_amt\"\n" +
                "                                   placeholder=\"Currently available amount\" type=\"text\"><br>\n" +
                "                            <span class=\"small_label\">Currently available amount (optional)</span>\n" +
                "                        </td>\n" +
                "                        <td class=\"req amt\" id=\"req_amt_field_" + newItemIdNumber + "\">\n" +
                "                            <input class=\"required amount\" id=\"req_amt_" + newItemIdNumber + "\" name=\"avail\" placeholder=\"Required amount\"\n" +
                "                                   type=\"text\"><br>\n" +
                "                            <span class=\"small_label\">Required amount\n" +
                "                                <span class=\"required_star redLabel\" redLabel=\"required_star\"> Required * </span>\n" +
                "                            </span>\n" +
                "                        </td>\n" +
                "                    </tr>\n" +
                "                    </tbody>\n" +
                "                </table>\n" +
                "                <div class=\"close_item_button\" id=\"closeItemBtn_" + newItemIdNumber + "\"><i class=\"fa-solid fa-x\" id=\"closeItemXIcon_" + newItemIdNumber + "\"></i></div>\n" +
                "            </div>");
        newItemIdNumber += 1;

    } else {
        // alert("Only maximum of 25 item can be requested.")
        Swal.fire({
            title: "Maximum item count reached",
            text: "You can request only 25 different items at a time.",
            icon: "error",
        });
    }
}


function submitItemRequest() {
    // Initialize an array to store the data for each item
    let itemData = [];

    const empName = document.getElementById("emp_name").value;
    const empDesignation = document.getElementById("emp_designation").value;
    const empId = document.getElementById("emp_id").value;
    const empDivision = document.getElementById("division").value;
    let errorOccurred = false;

    // Create three sets of dummy data
    for (let i = 1; i <= maxItemFieldAmount; i++) {
        const nameField = document.getElementById("item_name_" + i);
        const currentAmountField = document.getElementById("avl_amt_" + i);
        const requiredAmountField = document.getElementById("req_amt_" + i);
        const itemRemarks = document.getElementById("item_note_" + i);

        if (nameField && currentAmountField && requiredAmountField && itemRemarks) {
            if (nameField.value !== '' && requiredAmountField.value !== '') {
                const item = {
                    item_desc: nameField.value,
                    current_amount: currentAmountField.value,
                    required_amount: requiredAmountField.value,
                    item_remarks: itemRemarks.value
                };

                itemData.push(item);
            } else {
                errorOccurred = true; // Set the error flag
                break; // Exit the loop
                //     Swal.fire({
                //         title: "No data input",
                //         text: "You haven't input any data",
                //         icon: "question",
                //         showCancelButton: false,
                //         confirmButtonText: "OK",
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             // User confirmed, proceed with submission
                //             submitItemRequest();
                //         }
                //     });
            }
        } else {
            console.log("some element does not exist")
            break;
        }

    }
    $.ajax({
        type: "POST",
        url: "process_item_request_submit.php",
        data: {
            emp_name: empName,
            emp_designation: empDesignation,
            emp_id: empId,
            emp_division: empDivision,
            // request_month: requestMonth,
            // no_of_employees: noOfEmployees,
            itemData: itemData
        },
        success: function (response) {
            if (response === "success") {
                // Display a success message with SweetAlert2
                Swal.fire({
                    title: "Success",
                    text: "Your request has been submitted successfully.",
                    icon: "success",
                }).then(function () {
                    window.location.reload();
                });
            } else {
                // Display custom error messages based on the PHP response
                if (response === "error_prepare") {
                    Swal.fire({
                        title: "Error",
                        text: "An error occurred while preparing the statement. Please contact IT support for assistance.",
                        icon: "error",
                    });
                } else if (response === "error_data_insert") {
                    Swal.fire({
                        title: "Error",
                        text: "An error occurred while inserting data. Please double-check your input and try again.",
                        icon: "error",
                    });
                } else if (response === "error_invalid_data") {
                    Swal.fire({
                        title: "Error",
                        text: "You haven't filled any item details. You have to fill at least ONE item field.",
                        icon: "error",
                    });
                } else if (response === "error_employee_insert") {
                    Swal.fire({
                        title: "Error",
                        text: "Employee data insertion failed",
                        icon: "error",
                    });
                } else if (response === "error_employee_prepare") {
                    Swal.fire({
                        title: "Error",
                        text: "Error occurred preparing employee statement. Please contact IT support for assistance.",
                        icon: "error",
                    });
                } else if (response === "error_employee_details_blank") {
                    Swal.fire({
                        title: "Empty Employee Details",
                        text: "The employee details fields have not been filled. Please fill them and try submitting again",
                        icon: "error",
                    });
                } else {
                    // Handle any other error situations
                    Swal.fire({
                        title: "Error",
                        text: "An unknown error occurred. Please contact IT support for assistance.",
                        icon: "error",
                    });
                }
            }
        },
        error: function () {
            // Display a generic error message with SweetAlert2
            Swal.fire({
                title: "Error",
                text: "An error occurred while processing your request. Please contact IT support for assistance.",
                icon: "error",
            });
        }
    });

    // Access and display the values for each item in the itemData array
    itemData.forEach((item, index) => {
        console.log(`Item ${index + 1}:`);
        console.log(`Name: ${empName}`);
        console.log(`Name: ${item.item_desc}`);
        console.log(`Amount: ${item.current_amount}`);
        console.log(`Quantity: ${item.required_amount}`);
        console.log("--------");
    });


}


// Required input fields handling.
// All the logic for displaying the "Required *" label is here
document.addEventListener('DOMContentLoaded', function () {
    // Add focusout event listeners to dynamically created items
    document.addEventListener('focusout', function (event) {
        if (event.target.id.startsWith('item_name_') || event.target.id.startsWith('req_amt_')) {
            console.log("Message displayed")
            validateRequiredFields(event.target);
        } else {
        }
    });

    // Add input event listener to hide the "required" message when typing
    document.addEventListener('input', function (event) {
        if (event.target.id.startsWith('item_name_') || event.target.id.startsWith('req_amt_')) {
            hideRequiredMessage(event.target);
        } else {
            console.log(" INPUT error")
        }
    });
});

function validateRequiredFields(target) {
    const itemId = target.id.split('_')[2];
    const itemNameInput = document.getElementById('item_name_' + itemId);
    const itemNameField = document.getElementById('item_desc_' + itemId);
    const reqAmountInput = document.getElementById('req_amt_' + itemId);
    const reqAmountField = document.getElementById('req_amt_field_' + itemId);

    const requiredStar1 = document.getElementById('item_1_required_star_1');
    const requiredStar2 = document.getElementById('item_1_required_star_2');

    const requiredStarItemName = itemNameField.querySelector('[redLabel="required_star"]');
    const requiredStarReqAmount = reqAmountField.querySelector('[redLabel="required_star"]');

    if ((target.id === "item_name_1") || (target.id === "req_amt_1")) {
        if (itemNameInput.value.trim() === '') {
            requiredStarItemName.style.display = 'table-cell';
            requiredStar1.style.display = 'none';
        } else {
            requiredStarItemName.style.display = 'none';
            requiredStar1.style.display = 'inline';
        }

        if (reqAmountInput.value.trim() === '') {
            requiredStarReqAmount.style.display = 'table-cell';
            requiredStar2.style.display = 'none';
        } else {
            requiredStarReqAmount.style.display = 'none';
            requiredStar2.style.display = 'inline';
        }
    } else {
        if ((itemNameInput.value.trim() === '') && (reqAmountInput.value.trim() === '')) {
            requiredStarItemName.style.display = 'none';
            requiredStarReqAmount.style.display = 'none';
        } else if ((itemNameInput.value.trim() !== '') && (reqAmountInput.value.trim() === '')) {
            requiredStarReqAmount.style.display = 'table-cell';
        } else if ((itemNameInput.value.trim() === '') && reqAmountInput.value.trim() !== '') {
            requiredStarItemName.style.display = 'table-cell';
        } else {
            requiredStarItemName.style.display = 'none';
            requiredStarReqAmount.style.display = 'none';
        }
    }
}

function hideRequiredMessage(target) {
    const itemId = target.id.split('_')[2];
    const requiredStarItemName = document.getElementById('item_desc_' + itemId).querySelector('[redLabel="required_star"]');
    const requiredStarReqAmount = document.getElementById('req_amt_field_' + itemId).querySelector('[redLabel="required_star"]');

    const requiredStar1 = document.getElementById('item_1_required_star_1');
    const requiredStar2 = document.getElementById('item_1_required_star_2');

    if (target.id.startsWith('item_name_')) {
        requiredStarItemName.style.display = 'none';
        requiredStar1.style.display = 'inline';
    } else if (target.id.startsWith('req_amt_')) {
        requiredStarReqAmount.style.display = 'none';
        requiredStar2.style.display = 'inline';
    }
}

// let selector = 'span[redLabel="required_star"]';
// let elements = document.querySelectorAll(selector);
// for (var j = 0; j < elements.length; j++) {
//     elements[j].style.display = 'table-cell';
// }