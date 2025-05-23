// Confirm delete action
function confirmDelete(name) {
  return confirm("Are you sure you want to delete contact: " + name + "?");
}

// Form validation
document.addEventListener("DOMContentLoaded", function () {
  // Add form validation if forms exist
  const forms = document.querySelectorAll("form");
  forms.forEach(function (form) {
    form.addEventListener("submit", function (e) {
      const inputs = form.querySelectorAll("input[required]");
      let valid = true;

      inputs.forEach(function (input) {
        if (!input.value.trim()) {
          valid = false;
          input.classList.add("is-invalid");
        } else {
          input.classList.remove("is-invalid");
        }
      });

      if (!valid) {
        e.preventDefault();
        alert("Please fill in all required fields.");
      }
    });
  });
});

// Auto-hide alert messages after 3 seconds
document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert-custom");

  if (alerts.length > 0) {
    setTimeout(() => {
      alerts.forEach((alert) => {
        alert.style.transition = "opacity 0.5s ease";
        alert.style.opacity = 0;
        setTimeout(() => (alert.style.display = "none"), 500);
      });
    }, 3000); // 3 seconds
  }
});
