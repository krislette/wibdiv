<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"]) || $_SESSION["user"]["logged_in"] !== true) {
    // Redirect to registration page if not logged in
    header("Location: register.html");
    exit;
}

// Get user data from session
$user = $_SESSION["user"];

// Map country codes to names
$countryNames = [
    "ph" => "Philippines",
    "jp" => "Japan",
    "in" => "India",
    "us" => "United States",
    "ca" => "Canada",
    "uk" => "United Kingdom",
    "au" => "Australia",
    "zz" => "Other"
];

// Get country name from country code
$countryName = isset($countryNames[$user["country"]]) ? $countryNames[$user["country"]] : $user["country"];

// Format date of birth
$formattedDob = '';
if (!empty($user["dob"])) {
    $dob = new DateTime($user["dob"]);
    $formattedDob = $dob->format('F j, Y');
}

// Function to handle user logout
function logout() {
    // Clear session data
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    // Redirect to registration page
    header("Location: register.html");
    exit;
}

// Check if logout button was clicked
if (isset($_GET["logout"])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="./styles/welcome.css">
    <link rel="icon" href="./assets/icon.ico">
    <title>Krislette: Welcome</title>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="https://krislette.me" target="_blank" class="main-logo">Krislette</a>
            <a href="?logout=true" class="terms-button">Log Out</a>
        </div>
    </header>

    <!-- Main Content -->
    <main style="flex-direction: column; align-items: center;">
        <div class="welcome-container">
            <div class="form-header">
                <h1>Welcome, <?php echo htmlspecialchars($user["fullname"]); ?>!</h1>
                <p>Your registration was successful</p>
            </div>
            
            <div class="profile-section">
                <!-- Display profile picture -->
                <img src="<?php echo htmlspecialchars($user["profile_pic"]); ?>" alt="Profile Picture" class="profile-pic">
                
                <!-- Display user information -->
                <div class="user-info">
                    <div class="info-item">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value"><?php echo htmlspecialchars($user["fullname"]); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Email:</div>
                        <div class="info-value"><?php echo htmlspecialchars($user["email"]); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Gender:</div>
                        <div class="info-value"><?php echo htmlspecialchars($user["gender"]); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Date of Birth:</div>
                        <div class="info-value"><?php echo $formattedDob; ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Country:</div>
                        <div class="info-value"><?php echo htmlspecialchars($countryName); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <h3>Krislette</h3>
            <div class="footer-links">
                <div class="link-column">
                    <h4>Contact Me</h4>
                    <ul>
                        <li><a href="mailto:acellekrislette@gmail.com">acellekrislette@gmail.com</a></li>
                        <li><a href="tel:09159442848">09159442848</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="./terms.html" target="_blank">Terms & Conditions</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2025 Krislette. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
