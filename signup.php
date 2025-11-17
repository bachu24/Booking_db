<?php
// signup.php
session_start();
require 'db_connect.php'; // Connect to database

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get data from form
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $role = $_POST['role']; // from dropdown

    // 2. Basic Validation
    if (empty($email) || empty($name) || empty($password) || empty($role)) {
        $message = "Please fill in all required fields.";
    } else {
        // 3. Check if email already exists
        $checkStmt = $conn->prepare("SELECT user_id FROM User WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message = "This email is already registered.";
        } else {
            // 4. Hash the password (SECURITY BEST PRACTICE)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 5. Insert into Database
            // Note: Ensure you ran the ALTER TABLE command to add 'phone' column!
            $sql = "INSERT INTO User (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $role);

            if ($stmt->execute()) {
                // Success! Redirect to login
                header("Location: login.php");
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
    $conn->close();
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign Up – Evenity</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/signup.css">
</head>

<body>

    <div class="background"></div>

    <header class="top-header">
        <div class="brand">
            <img src="assets/logo-tickets.png" class="logo" alt="">
            <div class="brand-text">
                <div class="brand-name">Evenity</div>
                <div class="brand-sub">Booking Service</div>
            </div>
        </div>

        <a class="btn-top" href="signup.php">
            <span class="icon"></span>
            Sign up
        </a>
    </header>

    <main class="center-box">
        <div class="glass-box">

            <h1 class="title">Sign Up</h1>
            
            <?php if(!empty($message)): ?>
                <p style="color: #ff4d4d; text-align: center; margin-bottom: 10px; font-weight: 500;">
                    <?php echo $message; ?>
                </p>
            <?php endif; ?>

            <form action="" method="post">

                <label class="label">I AM A...</label>
                <select class="line-input" name="role" required style="background: transparent; color: #fff; option {color: #000;}">
                    <option value="Customer" style="color:black;">Customer (Book Events)</option>
                    <option value="Organizer" style="color:black;">Organizer (Create Events)</option>
                    </select>

                <label class="label">EMAIL ADDRESS</label>
                <input class="line-input" type="email" name="email" placeholder="hello@reallygreatsite" required>

                <label class="label">FULL NAME</label>
                <input class="line-input" type="text" name="name" placeholder="Samira Hadid" required>

                <label class="label">PHONE NUMBER</label>
                <input class="line-input" type="text" name="phone" placeholder="0954532145" required>

                <label class="label">PASSWORD</label>
                <input class="line-input" type="password" name="password" placeholder="********" required>

                <label class="agree">
                    <input type="checkbox" required>
                    I agree to the Terms & Conditions
                </label>

                <button class="btn-submit" type="submit">Sign Up</button>

            </form>
        </div>
    </main>

</body>
</html>