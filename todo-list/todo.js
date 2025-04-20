$(document).ready(function () {
  // Track number of tasks
  let taskCount = 0;

  // Add task function
  $("#add-task-btn").on("click", function () {
    addNewTask();
  });

  // Allow adding tasks by pressing Enter
  $("#task-input").on("keypress", function (e) {
    if (e.which === 13) {
      addNewTask();
    }
  });

  function addNewTask() {
    const taskText = $("#task-input").val().trim();

    // Validate input
    if (taskText === "") {
      $("#error").text("Please enter a task!");
      return;
    }

    // Clear error message if exists
    $("#error").text("");

    // Create task item
    const taskItem = $("<li>").addClass("task-item");

    // Create task text element
    const taskTextElement = $("<div>").addClass("task-text").text(taskText);

    // Create action buttons container
    const taskActions = $("<div>").addClass("task-actions");

    // Create delete button
    const deleteBtn = $("<button>")
      .addClass("delete-btn")
      .html('<i class="bx bx-trash"></i>')
      .on("click", function () {
        $(this).closest(".task-item").remove();
        updateTaskCount(-1);
      });

    // Append elements
    taskActions.append(deleteBtn);
    taskItem.append(taskTextElement, taskActions);

    // Add click event to toggle completion
    taskItem.on("click", function (e) {
      // Make sure we're not clicking on the delete button
      if (!$(e.target).closest(".delete-btn").length) {
        taskTextElement.toggleClass("completed");
      }
    });

    // Add task to the list
    $("#task-list").append(taskItem);

    // Clear input
    $("#task-input").val("").focus();

    // Update task counter
    updateTaskCount(1);
  }

  function updateTaskCount(change) {
    taskCount += change;
    $("#tasks-count").text(taskCount);
  }
});
