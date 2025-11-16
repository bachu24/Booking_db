<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Log in – Evenity</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Login CSS -->
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <!-- Background Blur Layer -->
    <div class="background"></div>

    <!-- Header -->
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

    <!-- Center Login Form -->
    <main class="center-box">
        <div class="glass-box">

            <h1 class="title">Log in</h1>

            <form action="" method="post">

                <!-- Email -->
                <div class="input-group">
                    <span class="input-icon email"></span>
                    <input type="email" placeholder="email" required>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <span class="input-icon password"></span>
                    <input type="password" id="password" placeholder="Password" required>
                    <span class="toggle" onclick="togglePassword()"></span>
                </div>

                <div class="sub-row">
                    <label class="remember">
                        <input type="checkbox"> Remember Me
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
