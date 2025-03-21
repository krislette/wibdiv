const registrationForm = document.querySelector("#registration-form");
const passwordInput = document.querySelector(".password-input");
const confirmPasswordInput = document.querySelector(".confirm-password-input");
const confirmMessageElement = document.querySelector(".password-match-message");
const passwordRequirementsElement = document.querySelector(
  ".password-requirements"
);

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

// Validate on form submission
registrationForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const dateInput = document.querySelector(".date-picker");

  // Prevent submission if date is empty
  if (!dateInput.value) {
    e.preventDefault();
    dateInput.focus();
    return;
  }

  const requirementsMet = validatePasswordRequirements();
  const passwordsMatch = validatePasswordsMatch();

  if (!requirementsMet || !passwordsMatch) {
    e.preventDefault();
    if (!requirementsMet) {
      passwordInput.focus();
    } else {
      confirmPasswordInput.focus();
    }
    return;
  }

  const popup = document.getElementById("confirmation-popup");
  popup.style.display = "flex";

  // Remove all input values and message divs after submission
  registrationForm.reset();
  passwordRequirementsElement.innerHTML = "";
  confirmMessageElement.textContent = "";
});

// Close popup when close button is clicked
document.querySelector("#close-popup").addEventListener("click", () => {
  document.querySelector("#confirmation-popup").style.display = "none";
});

document.addEventListener("DOMContentLoaded", () => {
  // Initialize Flatpickr
  const datePicker = flatpickr(".date-picker", {
    maxDate: "2007-03-21",
    dateFormat: "d/m/Y",
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
        instance.element.classList.add("invalid");
        instance.element.setAttribute("aria-invalid", "true");
      } else {
        instance.element.classList.remove("invalid");
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
});
