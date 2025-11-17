<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

$message = "";
$error = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'] ?? ''; // Handle if nothing selected
    $status = 'Active'; // Default status for new users

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if Email already exists
        $check = $conn->prepare("SELECT user_id FROM User WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // A. Handle Image Upload
            $image_filename = "default.jpg"; // Default if no file uploaded

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['profile_image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    // Create unique name: user_timestamp.ext
                    $new_name = "user_" . time() . "." . $ext;
                    $upload_path = "../assets/" . $new_name;

                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                        $image_filename = $new_name;
                    }
                } else {
                    $error = "Invalid file type. Only JPG, PNG, GIF allowed.";
                }
            }

            // B. Insert into Database (Only if no upload error)
            if (empty($error)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // SQL: Insert Name, Email, Password, Role, Status, Profile Image
                $sql = "INSERT INTO User (name, email, password, role, status, profile_image) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $name, $email, $hashed_password, $role, $status, $image_filename);

                if ($stmt->execute()) {
                    echo "<script>alert('User Added Successfully!'); window.location.href='manage-users.php';</script>";
                    exit();
                } else {
                    $error = "Database Error: " . $conn->error;
                }
                $stmt->close();
            }
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Users</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/add-user.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Quick CSS to make the image preview look good */
        .preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: none; /* Hidden until image selected */
        }
        .upload-box {
            overflow: hidden; /* Ensures image stays in circle */
            position: relative;
        }
        .upload-icon {
            z-index: 1;
        }
    </style>
</head>

<body>

<div class="layout">

    <aside class="sidebar">
        <div class="brand-box">
            <img src="../assets/logo-tickets.png" class="logo">
            <div class="brand-text">
                <h3>Evenity</h3>
                <p>Booking Service</p>
            </div>
        </div>

        <ul class="menu">
            <li onclick="location.href='dashboard.php'"><i class="fas fa-house icon"></i> Home</li>
            <li class="active" onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar-check icon"></i> Manage Events</li>
            <li onclick="location.href='manage-payments.php'"><i class="fas fa-credit-card icon"></i> Manage Payments</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, Admin!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="right">
                <div class="search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search users...">
                </div>

                <img src="../assets/profile3.png" class="profile">
            </div>
        </div>

        <button onclick="history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </button>

        <div class="event-box">

            <h1 class="title">Add Users</h1>

            <?php if($error): ?>
                <p style="color: red; text-align: center; margin-bottom: 10px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">

                <label class="upload-container">
                    <input type="file" id="userImage" name="profile_image" hidden onchange="previewImage(event)">
                    <div class="upload-box">
                        <img id="preview" class="preview-img">
                        <i class="fas fa-plus upload-icon" id="uploadIcon"></i>
                    </div>
                </label>

                <label class="input-label">FULL NAME</label>
                <input type="text" name="name" class="input-line" required>

                <label class="input-label">EMAILS</label>
                <input type="email" name="email" class="input-line" required>

                <label class="input-label">PASSWORD</label>
                <input type="password" name="password" class="input-line" required>

                <label class="input-label">ROLE</label>
                <select name="role" class="input-line select-line" required>
                    <option disabled selected value="">Select role…</option>
                    <option value="Customer">Customer</option>
                    <option value="Organizer">Organizer</option>
                    <option value="Admin">Admin</option>
                </select>

                <div class="btn-center">
                    <button type="submit" class="save-btn">Save</button>
                </div>

            </form>

        </div>

    </main>

</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    const preview = document.getElementById('preview');
    const icon = document.getElementById('uploadIcon');

    reader.onload = function() {
        preview.src = reader.result;
        preview.style.display = 'block'; // Show image
        icon.style.display = 'none';     // Hide plus icon
    };
    
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>

</body>
</html>