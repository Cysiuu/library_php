document.addEventListener("DOMContentLoaded", initalizeValidation);

var userFieldsAreOkay = true;
var phoneNumberFieldsAreOkay = true;

window.onpageshow = function (event) {
    if (
        event.persisted ||
        performance.getEntriesByType("navigation")[0].type === "back_forward"
    ) {
        location.reload();
    }
};

window.onload = function () {
    const forms = document.querySelectorAll(".needs-validation");
    Array.from(forms).forEach((form) => {
        form.reset();
    });
};

function initalizeValidation() {
    validateAllPhoneNumberFields();
    validateUser();
    validatePermissionDurationField();
    validateForm();
}

function validateForm() {
    "use strict";

    const forms = document.querySelectorAll(".needs-validation");

    Array.from(forms).forEach((form) => {
        form.addEventListener(
            "submit",
            (event) => {
                let isValid = true;

                // Check all required fields, including dynamically added ones
                form.querySelectorAll("input[required]").forEach((field) => {
                    if (!field.value.trim()) {
                        field.classList.add("is-invalid");
                        isValid = false;
                    } else {
                        field.classList.remove("is-invalid");
                    }
                });

                // Additional check for phone numbers
                form.querySelectorAll('[name^="phoneNumber"]').forEach((phoneField) => {
                    if (
                        !phoneField.value.trim() ||
                        !checkIfPhoneNumberIsValid(phoneField.value)
                    ) {
                        phoneField.classList.add("is-invalid");
                        isValid = false;
                    }
                });

                if (!isValid || !userFieldsAreOkay || !phoneNumberFieldsAreOkay) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add("was-validated");
            },
            false
        );
    });
}

function validateUser() {
    const fields = document.querySelectorAll(
        '[name^="firstName"], [name^="lastName"]'
    );
    const actualEditedFirstName =
        document.querySelector("#editFormFirstName")?.value;
    const actualEditedLastName =
        document.querySelector("#editFormLastName")?.value;

    if (!fields.length) return;

    fields.forEach((field) => {
        field.addEventListener("input", validateInputField);
    });

    function validateInputField(event) {
        const target = event.target;
        const index = target.name.match(/\d+/)?.[0] || "";
        const userTypedFirstName = document.querySelector(
            `[name="firstName${index}"]`
        )?.value;
        const userTypedLastName = document.querySelector(
            `[name="lastName${index}"]`
        )?.value;

        if (!userTypedFirstName || !userTypedLastName) {
            userFieldsAreOkay = false;
            return;
        }

        const firstNameField = document.querySelector(`[name="firstName${index}"]`);
        const lastNameField = document.querySelector(`[name="lastName${index}"]`);

        firstNameField.value = userTypedFirstName.value.replace(/\s+/g, " ");

        const firstNameFeedbackDiv = document.querySelector(
            `#firstNameFeedback${index}`
        );

        checkIfUserWithTheSameNameAndLastNameExists(
            userTypedFirstName,
            userTypedLastName,
            actualEditedFirstName,
            actualEditedLastName
        )
            .then((exists) => {
                if (exists) {
                    firstNameField?.classList.add("is-invalid");
                    lastNameField?.classList.add("is-invalid");
                    firstNameFeedbackDiv.innerHTML =
                        "Użytkownik o takim imieniu i nazwisku już istnieje w zdefiniowanych listach";
                    userFieldsAreOkay = false;
                } else {
                    firstNameField?.classList.remove("is-invalid");
                    lastNameField?.classList.remove("is-invalid");
                    firstNameFeedbackDiv.innerHTML = "";
                    userFieldsAreOkay = true;
                }
            })
            .catch((error) => {
                console.error("Error during user validation:", error);
                userFieldsAreOkay = false;
            });
    }
}

async function checkIfUserWithTheSameNameAndLastNameExists(
    firstName,
    lastName,
    actualEditedFirstName,
    actualEditedLastName
) {
    try {
        const response = await fetch(
            "/api/checkIfUserWithTheSameNameAndLastNameExists",
            {
                method: "POST",
                headers: {
                    "X-CSRFToken": document.querySelector('input[name="csrf_token"]')
                        .value,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    first_name: firstName,
                    last_name: lastName,
                    actual_edited_first_name: actualEditedFirstName,
                    actual_edited_last_name: actualEditedLastName,
                }),
            }
        );

        if (!response.ok) throw new Error("Network response was not ok");

        const {exists} = await response.json();
        return exists;
    } catch (error) {
        console.error("Error checking name and last name:", error);
        throw error;
    }
}

function validateAllPhoneNumberFields() {
    var phoneNumberFields = document.querySelectorAll('[name^="phoneNumber"]');
    var actualEditedUserPhoneNumber = document.querySelector(
        "#editFormPhoneNumber"
    );
    actualEditedUserPhoneNumber = actualEditedUserPhoneNumber
        ? actualEditedUserPhoneNumber.value
        : null;

    if (!phoneNumberFields) return;

    phoneNumberFields.forEach((phoneNumberField) => {
        if (phoneNumberField.value === "") phoneNumberFieldsAreOkay = false;

        phoneNumberField.addEventListener("input", function (event) {
            var userTypedPhoneNumber = event.target.value;

            if (!checkIfPhoneNumberIsValid(userTypedPhoneNumber)) {
                phoneNumberField.classList.add("is-invalid");
                phoneNumberFieldsAreOkay = false;
                return;
            }

            checkIfPhoneNumberExists(
                phoneNumberField,
                userTypedPhoneNumber,
                actualEditedUserPhoneNumber
            );
        });
    });
}

function checkIfPhoneNumberIsValid(phoneNumberToCheck) {
    const phoneNumberRegex = /^[0-9]{3}[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{3,6}$/;

    if (phoneNumberRegex.test(phoneNumberToCheck)) return true;

    return false;
}

async function checkIfPhoneNumberExists(
    phoneNumberField,
    userTypedPhoneNumber,
    actualEditedUserPhoneNumber
) {
    const exists = await sendRequestToCheckIfPhoneNumberExists(
        userTypedPhoneNumber,
        actualEditedUserPhoneNumber
    );

    if (exists) {
        phoneNumberField.classList.add("is-invalid");
        phoneNumberFieldsAreOkay = false;
    } else {
        phoneNumberField.classList.remove("is-invalid");
        phoneNumberFieldsAreOkay = true;
    }
}

async function sendRequestToCheckIfPhoneNumberExists(
    phoneNumberToCheck,
    actualEditedUserPhoneNumber
) {
    try {
        const response = await fetch(`/api/checkIfPhoneNumberExists`, {
            method: "POST",
            headers: {
                "X-CSRFToken": document.querySelector('input[name="csrf_token"]').value,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                actual_edited_user_phone_number: actualEditedUserPhoneNumber,
                phone_number: phoneNumberToCheck,
            }),
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        const data = await response.json();

        // Debugging line to verify the API response structure
        console.log("API Response:", data);

        return data.exists === true; // Ensure `data.exists` is boolean
    } catch (error) {
        console.error("Error checking phone number:", error);
        return false;
    }
}

function validatePermissionDurationField() {
    const permissionDuration = document.querySelector("#permissionDuration");
    if (!permissionDuration) return;

    permissionDuration.addEventListener("input", function (event) {
        var permissionDurationValue = event.target.value;
        if (!checkIfPermissionDurationIsValid(permissionDurationValue)) {
            permissionDuration.value = roundPermissionDurationValue(
                //We can round the value so there's no need to add is-invalid class
                permissionDurationValue
            );
        }
    });
}

function checkIfPermissionDurationIsValid(permissionDurationToCheck) {
    const permissionDurationRegex = /^(-|-1|[0-9]|[1-8][0-9]|90)$/;

    if (!permissionDurationRegex.test(permissionDurationToCheck)) {
        return false;
    }
    return true;
}

function roundPermissionDurationValue(value) {
    if (value > 90) return "90";
    else if (value < -1) return "-1";
    return "";
}

var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
);
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});
