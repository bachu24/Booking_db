<?php
// Start the session to store user data
session_start();

// Include database connection
require 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare SQL to prevent SQL injection
    // We select the role [cite: 196] to know where to redirect them
    $stmt = $conn->prepare("SELECT user_id, name, password, role FROM User WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        // Verify Password
        // NOTE: If you are NOT hashing passwords in signup, change this to: if ($password === $hashed_password) {
        if (password_verify($password, $hashed_password)) {
            
            // Password correct, start session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $name;
            $_SESSION['role'] = $role;

            // Redirect based on Role [cite: 196]
            if ($role == 'Admin') {
                header("Location: Admin/dashboard.php");
            } elseif ($role == 'Organizer') {
                header("Location: Organizer/dashboard.php");
            } else {
                header("Location: Customer/dashboard.php"); 
            }
            exit();
        } else {
            $error_msg = "Invalid password.";
        }
    } else {
        $error_msg = "No account found with that email.";
    }
    $stmt->close();
}
$conn->close();
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Log in – Evenity</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/login.css">
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

        <a class="btn-login" href="login.php">
            Log In
        </a>
    </header>

    <main class="center-box">
        <div class="glass-box">

            <h1 class="title">Log in</h1>
            
            <?php if(isset($error_msg)) { echo "<p style='color:red; text-align:center;'>$error_msg</p>"; } ?>

            <form action="" method="post">

                <div class="input-group">
                    <span class="input-icon email"></span>
                    <input type="email" name="email" placeholder="email" required>
                </div>

                <div class="input-group">
                    <span class="input-icon password"></span>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <span class="toggle" onclick="togglePassword()"></span>
                </div>

                <div class="sub-row">
                    <label class="remember">
                        <input type="checkbox" name="remember"> Remember Me
                    </label>

                    <a href="#" class="forgot">Forgot Password?</a>
                </div>

                <button class="btn-submit" type="submit">Log in</button>

                <div class="signup-row">
                    or <a href="signup.php">Sign up</a>
                </div>

            </form>

        </div>
    </main>

    <script>
        function togglePassword() {
            const pwd = document.getElementById("password");
            pwd.type = pwd.type === "password" ? "text" : "password";
        }
    </script>

</body>
</html>