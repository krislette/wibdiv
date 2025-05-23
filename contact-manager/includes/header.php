<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Contact Manager'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="./assets/icon.ico" rel="icon" />
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">Contact Manager</a>
            <div class="navbar-nav ms-auto d-flex flex-row gap-3">
                <a class="nav-link" href="index.php">View Contacts</a>
                <a class="nav-link" href="add.php">Add Contact</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">