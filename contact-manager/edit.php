<?php
require_once 'config/database.php';
$page_title = 'Edit Contact';

$success = '';
$error = '';
$contact = null;

// Get contact ID from URL
$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

// Fetch existing contact data
try {
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    $contact = $stmt->fetch();
    
    if (!$contact) {
        header('Location: index.php');
        exit;
    }
} catch(PDOException $e) {
    $error = 'Error fetching contact: ' . $e->getMessage();
}

// Handle form submission
if ($_POST && $contact) {
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
            // Use prepared statement to update contact
            $stmt = $pdo->prepare("UPDATE contacts SET name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $id]);
            
            $success = 'Contact updated successfully!';
            // Update contact array with new data
            $contact['name'] = $name;
            $contact['email'] = $email;
            $contact['phone'] = $phone;
        } catch(PDOException $e) {
            $error = 'Error updating contact: ' . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="form-container">
    <h2 class="text-center mb-4">Edit Contact</h2>
    
    <?php if ($success): ?>
        <div class="alert alert-success alert-custom"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-custom"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($contact): ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($contact['name']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($contact['email']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($contact['phone']); ?>" required>
            </div>
            
            <div class="d-grid gap-3">
                <button type="submit" class="btn btn-primary">
                    <?php echo isset($contact) ? 'Update Contact' : 'Add Contact'; ?>
                </button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>