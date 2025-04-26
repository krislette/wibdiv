<?php
// Start session to store user data
session_start();

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize errors array
$errors = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate Full Name
    if (empty($_POST["fullname"])) {
        $errors[] = "Full Name is required";
    } else {
        $fullname = sanitize_input($_POST["fullname"]);
        if (strlen($fullname) < 3) {
            $errors[] = "Full Name must be at least 3 characters";
        }
    }
    
    // Validate Email
    if (empty($_POST["email"])) {
        $errors[] = "Email is required";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
    }
    
    // Validate Password
    if (empty($_POST["password"])) {
        $errors[] = "Password is required";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }
        // Check for number and special character
        if (!preg_match("/^(?=.*[0-9])(?=.*[\W_]).{8,}$/", $password)) {
            $errors[] = "Password must contain at least one number and one special character";
        }
    }
    
    // Validate Password Confirmation
    if (empty($_POST["confirm_password"])) {
        $errors[] = "Please confirm your password";
    } else if ($_POST["password"] !== $_POST["confirm_password"]) {
        $errors[] = "Passwords do not match";
    }
    
    // Validate Gender
    if (empty($_POST["gender"])) {
        $errors[] = "Gender is required";
    } else {
        $gender = sanitize_input($_POST["gender"]);
        // Ensure gender is one of the allowed values
        if (!in_array($gender, ["Male", "Female", "Other"])) {
            $errors[] = "Invalid gender selection";
        }
    }
    
    // Validate Date of Birth
    if (empty($_POST["dob"])) {
        $errors[] = "Date of Birth is required";
    } else {
        $dob = sanitize_input($_POST["dob"]);
    }
    
    // Validate Country
    if (empty($_POST["country"])) {
        $errors[] = "Country is required";
    } else {
        $country = sanitize_input($_POST["country"]);
    }
    
    // Validate Terms Agreement
    if (empty($_POST["terms"])) {
        $errors[] = "You must agree to the Terms & Conditions";
    }
    
    // Validate Profile Picture
    if (empty($_FILES["profile_pic"]["name"])) {
        $errors[] = "Profile picture is required";
    } else {
        $file = $_FILES["profile_pic"];
        $fileName = $file["name"];
        $fileSize = $file["size"];
        $fileTmpName = $file["tmp_name"];
        $fileError = $file["error"];
        
        // Get file extension and check if it's allowed
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ["jpg", "jpeg", "png"];
        
        if (!in_array($fileExt, $allowedExtensions)) {
            $errors[] = "Only JPG, JPEG, and PNG files are allowed";
        }
        
        // Check file size (2MB = 2097152 bytes)
        if ($fileSize > 2097152) {
            $errors[] = "File size must be less than 2MB";
        }
        
        // Check for file upload errors
        if ($fileError !== 0) {
            $errors[] = "Error uploading file. Please try again.";
        }
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // Create uploads directory if it doesn't exist
        $uploadDir = "uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Generate unique filename for profile picture
        $newFileName = uniqid() . "." . $fileExt;
        $uploadPath = $uploadDir . $newFileName;
        
        // Move uploaded file to the uploads directory
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Create user data array
            $userData = [
                "fullname" => $fullname,
                "email" => $email,
                "password" => $hashedPassword,
                "gender" => $gender,
                "dob" => $dob,
                "country" => $country,
                "profile_pic" => $uploadPath,
                "registration_date" => date("Y-m-d H:i:s")
            ];
            
            // Store user data in JSON file
            $jsonFile = "users.json";
            $existingUsers = [];
            
            // Read existing users if file exists
            if (file_exists($jsonFile)) {
                $jsonContent = file_get_contents($jsonFile);
                $existingUsers = json_decode($jsonContent, true) ?: [];
            }
            
            // Add new user
            $existingUsers[] = $userData;
            
            // Save updated user data
            if (file_put_contents($jsonFile, json_encode($existingUsers, JSON_PRETTY_PRINT))) {
                // Store user data in session
                $_SESSION["user"] = [
                    "fullname" => $fullname,
                    "email" => $email,
                    "profile_pic" => $uploadPath,
                    "gender" => $gender,
                    "dob" => $dob,
                    "country" => $country,
                    "logged_in" => true
                ];
                
                // Redirect to welcome page
                header("Location: welcome.php");
                exit;
            } else {
                $errors[] = "Error saving user data. Please try again.";
            }
        } else {
            $errors[] = "Error uploading profile picture. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="icon" href="./assets/icon.ico">
    <title>Registration Processing</title>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="https://krislette.me" target="_blank" class="main-logo">Krislette</a>
            <a href="./terms.html" class="terms-button" target="_blank">Terms & Conditions</a>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <section>
            <div class="registration-container">
                <div class="form-header">
                    <h1>Registration</h1>
                    <?php if (!empty($errors)): ?>
                        <p>Please correct the following errors</p>
                    <?php endif; ?>
                </div>
                
                <!-- Display validation errors -->
                <?php if (!empty($errors)): ?>
                    <div class="error-container">
                        <ul class="error-list">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <a href="register.html" class="submit-btn" style="display:block;text-align:center;text-decoration:none;margin-top:20px;">Back to Registration</a>
                <?php endif; ?>
            </div>
        </section>
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
