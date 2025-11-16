<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard-admin.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand-box">
            <img src="../assets/logo-tickets.png" class="logo">
            <div class="brand-text">
                <h3>Evenity</h3>
                <p>Booking Service</p>
            </div>
        </div>

        <ul class="menu">
            <li class="active"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar icon"></i> Manage Events</li>
            <li onclick="location.href='manage-payments.php'"><i class="fas fa-money-bill icon"></i> Manage Payments</li>
            <li onclick="location.href='settings.php'"><i class="fas fa-gear icon"></i> Settings</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">

        <!-- TOP BAR -->
        <div class="top-bar">
            <div>
                <h2>Welcome Back, Sainam!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="right">
                <div class="search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search">
                </div>

                <img src="../assets/profile3.png" class="profile">
            </div>
        </div>

        <!-- DASHBOARD MAIN -->
        <div class="dashboard-wrapper">

            <!-- STAT CARDS -->
            <div class="stats-grid">

                <div class="stat-card purple">
                    <h4>Total Users</h4>
                    <h1>80 K</h1>
                    <p class="green">+18% <span>+3.8k this week</span></p>
                </div>

                <div class="stat-card purple">
                    <h4>Total Events</h4>
                    <h1>12 K</h1>
                    <p class="green">+18% <span>+2.8k this week</span></p>
                </div>

                <div class="stat-card purple">
                    <h4>Total Revenue</h4>
                    <h1>10 M</h1>
                    <p class="green">+18% <span>+7.8k this week</span></p>
                </div>

                <div class="stat-card purple">
                    <h4>Completed Payments</h4>
                    <h1>5 K</h1>
                    <p class="green">+18% <span>+1.2k this week</span></p>
                </div>

            </div>

        </div>

    </main>
</div>

</body>
</html>
