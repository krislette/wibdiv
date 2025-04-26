const registrationForm = document.querySelector("#registration-form");
const passwordInput = document.querySelector(".password-input");
const confirmPasswordInput = document.querySelector(".confirm-password-input");
const confirmMessageElement = document.querySelector(".password-match-message");
const passwordRequirementsElement = document.querySelector(
  ".password-requirements"
);
const profilePicInput = document.querySelector("#profile_pic");
const fileRequirementsElement = document.querySelector(".file-requirements");

// Function to validate password reqs
function validatePasswordRequirements() {
  const password = passwordInput.value;
  let requirements = [];

  // Check length
  if (password.length < 8) {
    requirements.push("Must be at least 8 characters long");
  }

  // Check for number
  if (!/[0-9]/.test(password)) {
    requirements.push("Must include at least one number");
  }

  // Check for special character
  if (!/[\W_]/.test(password)) {
    requirements.push("Must include at least one special character");
  }

  // Display reqs or success message
  if (requirements.length > 0) {
    passwordRequirementsElement.innerHTML = requirements
      .map((req) => `<div style="color: #dd262b;">• ${req}</div>`)
      .join("");
    return false;
  } else if (password.length > 0) {
    passwordRequirementsElement.innerHTML =
      '<div style="color: #27ac27;">Password meets all requirements</div>';
    return true;
  } else {
    passwordRequirementsElement.innerHTML = "";
    return false;
  }
}

// Function to validate that passwords match
function validatePasswordsMatch() {
  if (confirmPasswordInput.value === "") {
    confirmMessageElement.textContent = "";
    return false;
  } else if (passwordInput.value === confirmPasswordInput.value) {
    confirmMessageElement.textContent = "Passwords match";
    confirmMessageElement.style.color = "#27ac27";
    return true;
  } else {
    confirmMessageElement.textContent = "Passwords do not match";
    confirmMessageElement.style.color = "#dd262b";
    return false;
  }
}

// Function to validate profile picture
function validateProfilePicture() {
  if (!profilePicInput) return true; // Skip if element doesn't exist

  const file = profilePicInput.files[0];
  if (!file) return false;

  // Check file size (2MB = 2097152 bytes)
  const maxSize = 2 * 1024 * 1024; // 2MB

  // Check file type
  const allowedTypes = ["image/jpeg", "image/jpg", "image/png"];

  let isValid = true;
  let errorMessage = "";

  // Validate file type
  if (!allowedTypes.includes(file.type)) {
    isValid = false;
    errorMessage = "Only JPG, JPEG, or PNG files are allowed";
  }

  // Validate file size
  if (file.size > maxSize) {
    isValid = false;
    errorMessage = "File size must be less than 2MB";
  }

  // Update UI
  if (!isValid && fileRequirementsElement) {
    fileRequirementsElement.textContent = errorMessage;
    fileRequirementsElement.style.color = "#dd262b";
  } else if (fileRequirementsElement) {
    fileRequirementsElement.textContent = "File is valid";
    fileRequirementsElement.style.color = "#27ac27";
  }

  return isValid;
}

// Check password requirements on input
passwordInput.addEventListener("input", () => {
  validatePasswordRequirements();
  // If confirm password already has a value, check matching
  if (confirmPasswordInput.value !== "") {
    validatePasswordsMatch();
  }
});

// Check password matching on confirm password input
confirmPasswordInput.addEventListener("input", validatePasswordsMatch);

// Validate profile picture on change
if (profilePicInput) {
  profilePicInput.addEventListener("change", validateProfilePicture);
}

// Validate on form submission
registrationForm.addEventListener("submit", (e) => {
  const dateInput = document.querySelector(".date-picker");

  // Prevent submission if date is empty
  if (!dateInput.value) {
    e.preventDefault();
    dateInput.focus();
    return;
  }

  const requirementsMet = validatePasswordRequirements();
  const passwordsMatch = validatePasswordsMatch();
  const validProfilePic = validateProfilePicture();

  if (!requirementsMet || !passwordsMatch || !validProfilePic) {
    e.preventDefault();
    if (!requirementsMet) {
      passwordInput.focus();
    } else if (!passwordsMatch) {
      confirmPasswordInput.focus();
    } else if (!validProfilePic && profilePicInput) {
      profilePicInput.focus();
    }
    return;
  }
});

document.addEventListener("DOMContentLoaded", () => {
  // Initialize Flatpickr if it exists
  if (
    typeof flatpickr !== "undefined" &&
    document.querySelector(".date-picker")
  ) {
    const datePicker = flatpickr(".date-picker", {
      maxDate: "2007-03-21",
      dateFormat: "Y-m-d", // Changed to match PHP date format
      monthSelectorType: "dropdown",
      yearSelectorType: "dropdown",
      placeholder: "Date of Birth",
      clickOpens: true,
      position: "auto",
      allowInput: true,
      onClose: function (selectedDates, dateStr, instance) {
        // When calendar closes, if no date is selected, make field invalid
        if (selectedDates.length === 0) {
          instance.element.value = "";
          instance.element.setAttribute("aria-invalid", "true");
        } else {
          instance.element.setAttribute("aria-invalid", "false");
        }
      },
      onChange: function (selectedDates, dateStr, instance) {
        // Validate the form when a date is selected
        if (selectedDates.length > 0) {
          instance.element.value = dateStr;
          instance.element.dispatchEvent(new Event("change"));
        }
      },
    });
  }
});
