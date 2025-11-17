<?php
// 1. Start Session & Security Check
session_start();

// Check if user is logged in AND is an Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php"); // Redirect unauthorized users
    exit();
}

// 2. Connect to Database
// Note: We use "../" because db_connect.php is one folder up
require '../db_connect.php';

// 3. Get Admin Name
$admin_name = $_SESSION['username'] ?? 'Admin';

// 4. Fetch Statistics
// A. Total Users (excluding Admins themselves usually, but let's count all for now)
$userQuery = $conn->query("SELECT COUNT(*) as total FROM User");
$userCount = $userQuery->fetch_assoc()['total'];

// B. Total Events
$eventQuery = $conn->query("SELECT COUNT(*) as total FROM Event");
$eventCount = $eventQuery->fetch_assoc()['total'];

// C. Total Revenue (Sum of Completed Payments)
$revenueQuery = $conn->query("SELECT SUM(amount) as total FROM Payment WHERE status = 'Completed'");
$revenueData = $revenueQuery->fetch_assoc();
$totalRevenue = $revenueData['total'] ?? 0; // Default to 0 if null

// D. Completed Payments Count
$paymentQuery = $conn->query("SELECT COUNT(*) as total FROM Payment WHERE status = 'Completed'");
$paymentCount = $paymentQuery->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard-admin.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <li class="active"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar icon"></i> Manage Events</li>
            <li onclick="location.href='manage-payments.php'"><i class="fas fa-money-bill icon"></i> Manage Payments</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, <?php echo htmlspecialchars($admin_name); ?>!</h2>
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

        <div class="dashboard-wrapper">

            <div class="stats-grid">

                <div class="stat-card purple">
                    <h4>Total Users</h4>
                    <h1><?php echo $userCount; ?></h1>
                    <p class="green">+18% <span>+3.8k this week</span></p>
                </div>

                <div class="stat-card purple">
                    <h4>Total Events</h4>
                    <h1><?php echo $eventCount; ?></h1>
                    <p class="green">+18% <span>+2.8k this week</span></p>
                </div>

                <div class="stat-card purple">
                    <h4>Total Revenue</h4>
                    <h1>$<?php echo number_format($totalRevenue, 2); ?></h1>
                    <p class="green">+18% <span>+7.8k this week</span></p>
                </div>

                <div class="stat-card purple">
                    <h4>Completed Payments</h4>
                    <h1><?php echo $paymentCount; ?></h1>
                    <p class="green">+18% <span>+1.2k this week</span></p>
                </div>

            </div>

        </div>

    </main>
</div>

</body>
</html>