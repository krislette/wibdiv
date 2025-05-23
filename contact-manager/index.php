<?php
require_once 'config/database.php';
$page_title = 'Contact List';

// Handle success/error messages from redirects
$success_msg = $_GET['success'] ?? '';
$error_msg = $_GET['error'] ?? '';

// Fetch all contacts from database
try {
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY name ASC");
    $contacts = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching contacts: " . $e->getMessage();
}

include 'includes/header.php';
?>

<?php if ($success_msg): ?>
    <div class="alert alert-success alert-custom"><?php echo htmlspecialchars($success_msg); ?></div>
<?php endif; ?>

<?php if ($error_msg): ?>
    <div class="alert alert-danger alert-custom"><?php echo htmlspecialchars($error_msg); ?></div>
<?php endif; ?>

<div class="bento-header">
    <div class="header-content">
        <h1 class="page-title">Contact Hub</h1>
        <p class="page-subtitle">Manage your professional network</p>
    </div>
    <a href="add.php" class="btn-primary-custom">
        <i class="fas fa-user-plus"></i>
        Add Contact
    </a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-custom"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (empty($contacts)): ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-address-book fa-4x"></i>
        </div>
        <h3>No contacts yet</h3>
        <p>Start building your network by adding your first contact</p>
        <a href="add.php" class="btn-secondary-custom">Get Started</a>
    </div>
<?php else: ?>
    <div class="bento-grid">
        <?php foreach ($contacts as $contact): ?>
            <div class="bento-card contact-card">
                <div class="card-header">
                    <div class="contact-avatar">
                        <?php echo strtoupper(substr($contact['name'], 0, 2)); ?>
                    </div>
                    <div class="contact-info">
                        <h3 class="contact-name"><?php echo htmlspecialchars($contact['name']); ?></h3>
                        <span class="contact-id">#<?php echo str_pad($contact['id'], 3, '0', STR_PAD_LEFT); ?></span>
                    </div>
                </div>
                <div class="contact-details">
                    <div class="detail-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="contact-link">
                            <?php echo htmlspecialchars($contact['email']); ?>
                        </a>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-phone"></i>
                        <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>" class="contact-link">
                            <?php echo htmlspecialchars($contact['phone']); ?>
                        </a>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="edit.php?id=<?php echo $contact['id']; ?>" class="btn-action btn-edit">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <a href="delete.php?id=<?php echo $contact['id']; ?>" 
                        class="btn-action btn-delete"
                        onclick="return confirmDelete('<?php echo htmlspecialchars($contact['name']); ?>')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>