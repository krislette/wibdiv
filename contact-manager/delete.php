<?php
require_once 'config/database.php';

// Get contact ID from URL
$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

try {
    // First check if contact exists
    $stmt = $pdo->prepare("SELECT name FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    $contact = $stmt->fetch();
    
    if (!$contact) {
        // Contact doesn't exist, redirect back
        header('Location: index.php?error=Contact not found');
        exit;
    }
    
    // Delete the contact using prepared statement
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    
    // Redirect with success message
    header('Location: index.php?success=Contact deleted successfully');
    exit;
    
} catch(PDOException $e) {
    // Redirect with error message
    header('Location: index.php?error=' . urlencode('Error deleting contact: ' . $e->getMessage()));
    exit;
}
?>