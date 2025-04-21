// Initializing operations
const allOperations = ["add", "subtract", "multiply", "divide"];

// Handles calculation for the chosen operation
function calculate(operation) {
  // Get all elements from the DOM
  const num1Input = document.querySelector("#num-1");
  const num2Input = document.querySelector("#num-2");
  const errorDiv = document.querySelector("#error");
  const resultDiv = document.querySelector("#result");

  errorDiv.textContent = "";

  // Get the value of input fields
  const num1 = parseFloat(num1Input.value);
  const num2 = parseFloat(num2Input.value);

  // Early return check if one of the field inputs are invalid
  if (isNaN(num1) || isNaN(num2)) {
    errorDiv.textContent = "Please enter valid numbers in both fields";
    resultDiv.textContent = "";
    return;
  }

  let result;

  // Perform calculation based on operation selected
  switch (operation) {
    case "add":
      result = num1 + num2;
      break;
    case "subtract":
      result = num1 - num2;
      break;
    case "multiply":
      result = num1 * num2;
      break;
    case "divide":
      if (num2 === 0) {
        errorDiv.textContent = "Cannot divide by zero";
        resultDiv.textContent = "";
        return;
      }
      result = num1 / num2;
      break;
  }

  // Show result
  resultDiv.textContent = result;
}

// Loop through all operations to add listener
allOperations.forEach((operation) => {
  document.querySelector(`#${operation}`).addEventListener("click", () => {
    calculate(operation);
  });
});
