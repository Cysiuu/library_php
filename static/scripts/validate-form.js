document.addEventListener("DOMContentLoaded", initialize());

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

function initialize() {
  validateUser();
  validateAllPhoneNumberFields();
  validatePermissionDurationField();
  checkIfFormHaveInvalidFields();
  validateForm();
}

function checkIfFormHaveInvalidFields() {
  var invalidFields = document.querySelectorAll(".is-invalid");
  return invalidFields.length > 0;
}

function validateForm() {
  "use strict";

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (checkIfFormHaveInvalidFields() || !form.checkValidity()) {
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
  var firstNameFields = document.querySelectorAll('[name^="firstName"]');
  var lastNameFields = document.querySelectorAll('[name^="lastName"]');

  var actualEditedFirstName = document.querySelector("#editFormFirstName");
  actualEditedFirstName = actualEditedFirstName
    ? actualEditedFirstName.value
    : null;

  var actualEditedLastName = document.querySelector("#editFormLastName");
  actualEditedLastName = actualEditedLastName
    ? actualEditedLastName.value
    : null;

  if (!firstNameFields || !lastNameFields) return;

  firstNameFields.forEach((firstNameField) => {
    preventWhiteSpaceInput((field = firstNameField));
    allowOnylLetters((field = firstNameField));
    firstNameField.addEventListener("input", function (event) {
      validateInputFields(event.target);
    });
  });

  lastNameFields.forEach((lastNameField) => {
    preventWhiteSpaceInput((field = lastNameField));
    allowOnylLetters((field = lastNameField));
    lastNameField.addEventListener("input", function (event) {
      validateInputFields(event.target);
    });
  });

  function validateInputFields(target) {
    var index = target.name.match(/\d+/);

    // Get the index from the input name attribute e.g firstName0 or firstName if index is not present
    index = index ? index[0] : "";

    var userTypedFirstName = document.querySelector(
      `[name="firstName${index}"]`
    ).value;
    var userTypedLastName = document.querySelector(
      `[name="lastName${index}"]`
    ).value;

    var firstNameFeedbackDiv = document.querySelector(
      `#firstNameFeedback${index}`
    );
    var lastNameFeedbackDiv = document.querySelector(
      `#lastNameFeedback${index}`
    );

    var firstNameField = document.querySelector(`[name="firstName${index}"]`);
    firstNameField.value = userTypedFirstName.replace(/\s+/g, ""); // Remove whitespace from the input even if user paste it
    var lastNameField = document.querySelector(`[name="lastName${index}"]`);
    lastNameField.value = userTypedLastName.replace(/\s+/g, "");

    if (!userTypedFirstName || !userTypedLastName) return;

    checkIfUserWithTheSameNameAndLastNameExists(
      userTypedFirstName,
      userTypedLastName,
      actualEditedFirstName,
      actualEditedLastName
    ).then((exists) => {
      if (exists) {
        firstNameField.classList.add("is-invalid");
        lastNameField.classList.add("is-invalid");
        firstNameFeedbackDiv.innerHTML =
          "Użytkownik o takim imieniu i nazwisku już istnieje w zdefiniowanych listach";
        lastNameFeedbackDiv.innerHTML = "";
      }
      else{
      firstNameField.classList.remove("is-invalid");
      lastNameField.classList.remove("is-invalid");
      firstNameFeedbackDiv.innerHTML = "";
      lastNameFeedbackDiv.innerHTML = "";
      }
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
      `/api/checkIfUserWithTheSameNameAndLastNameExists`,
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

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const data = await response.json();

    return data.exists ? true : false;
  } catch (error) {
    console.error("Error checking name and last name:", error);
    return false;
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
    phoneNumberField.addEventListener("input", function (event) {
      var userTypedPhoneNumber = event.target.value;

      preventWhiteSpaceInput((field = event.target));
      phoneNumberField.value = userTypedPhoneNumber.replace(/\s+/g, ""); // Remove whitespace from the input

      if (!checkIfPhoneNumberIsValid(userTypedPhoneNumber)) {
        phoneNumberField.classList.add("is-invalid");
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

function preventWhiteSpaceInput(field) {
  field.addEventListener("keypress", function (event) {
    var key = event.keyCode;
    if (key === 32) {
      event.preventDefault();
    }
  });
}

function allowOnylLetters(field) {
  field.addEventListener("keypress", function (event) {
    var key = event.keyCode;
    if (key < 65 || key > 90 && key < 97 || key > 122) { // Allow only A-Z and a-z letters
      event.preventDefault();
    }
  });

}

function checkIfPhoneNumberIsValid(phoneNumberToCheck) {
  const phoneNumberRegex = /^[0-9]{3}[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{3,6}$/;

  if (phoneNumberRegex.test(phoneNumberToCheck)) {
    return true;
  }
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
  } else {
    phoneNumberField.classList.remove("is-invalid");
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
