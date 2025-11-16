<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign Up – Evenity</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/signup.css">
</head>

<body>

    <!-- BG -->
    <div class="background"></div>

    <!-- HEADER -->
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

    <!-- CONTENT -->
    <main class="center-box">
        <div class="glass-box">

            <h1 class="title">Sign Up</h1>

            <form action="" method="post">

                <label class="label">EMAIL ADDRESS</label>
                <input class="line-input" type="email" placeholder="hello@reallygreatsite" required>

                <label class="label">FULL NAME</label>
                <input class="line-input" type="text" placeholder="Samira Hadid" required>

                <label class="label">PHONE NUMBER</label>
                <input class="line-input" type="text" placeholder="0954532145" required>

                <label class="label">PASSWORD</label>
                <input class="line-input" type="password" placeholder="********" required>

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
