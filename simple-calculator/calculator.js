const allOperations = ["add", "subtract", "multiply", "divide"];

function calculate(operation) {
  const num1Input = document.querySelector("#num-1");
  const num2Input = document.querySelector("#num-2");
  const errorDiv = document.querySelector("#error");
  const resultDiv = document.querySelector("#result");

  errorDiv.textContent = "";

  const num1 = parseFloat(num1Input.value);
  const num2 = parseFloat(num2Input.value);

  if (isNaN(num1) || isNaN(num2)) {
    errorDiv.textContent = "Please enter valid numbers in both fields";
    resultDiv.textContent = "";
    return;
  }

  let result;

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

  resultDiv.textContent = result;
}

allOperations.forEach((operation) => {
  document.querySelector(`#${operation}`).addEventListener("click", () => {
    calculate(operation);
  });
});
