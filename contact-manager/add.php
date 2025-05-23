<?php
require_once 'config/database.php';
$page_title = 'Add Contact';

$success = '';
$error = '';

// Handle form submission
if ($_POST) {
    // Get and sanitize input data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Basic validation
    if (empty($name) || empty($email) || empty($phone)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Use prepared statement to prevent SQL injection
            $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $phone]);
            
            $success = 'Contact added successfully!';
            // Clear form data after successful submission
            $name = $email = $phone = '';
        } catch(PDOException $e) {
            $error = 'Error adding contact: ' . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="form-container">
    <h2 class="text-center mb-4">Add New Contact</h2>
    
    <?php if ($success): ?>
        <div class="alert alert-success alert-custom"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-custom"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" 
                   value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
        </div>
        
        <div class="d-grid gap-3">
            <button type="submit" class="btn btn-primary">
                <?php echo isset($contact) ? 'Update Contact' : 'Add Contact'; ?>
            </button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>