<?php
// 1. Start Session & Security Check
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Organizer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php"); 
    exit();
}

require '../db_connect.php';

// 2. Get Event ID
if (!isset($_GET['id'])) {
    header("Location: view-events.php");
    exit();
}
$event_id = intval($_GET['id']);
$organizer_name = $_SESSION['username'] ?? 'Organizer';

// 3. CALL THE STORED PROCEDURE
$sql = "CALL GenerateEventReport(?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Clean up result set
$conn->next_result(); 

if (!$data) {
    echo "<script>alert('No data found for this event.'); window.location.href='view-events.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Report</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard-organizer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .report-wrapper { padding: 30px; }
        .report-title { font-size: 24px; font-weight: 700; margin-bottom: 10px; }
        .report-sub { color: #666; margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .stat-box { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        .stat-box h4 { font-size: 14px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .stat-box h1 { font-size: 36px; font-weight: 700; color: #333; margin: 10px 0; }
        .stat-box.purple h1 { color: #6c5ce7; }
        .stat-box.green h1 { color: #00b894; }
        .stat-box.blue h1 { color: #0984e3; }
    </style>
</head>

<body>

<div class="layout">

    <aside class="sidebar">
        <div class="brand-box">
            <img src="../assets/logo-tickets.png" class="logo">
            <div class="brand-text"><h3>Evenity</h3><p>Booking Service</p></div>
        </div>
        <ul class="menu">
            <li onclick="location.href='dashboard.php'"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='new-event.php'"><i class="fas fa-calendar-plus icon"></i> New Event</li>
            <li class="active" onclick="location.href='view-events.php'"><i class="fas fa-eye icon"></i> View Events</li>
            <li onclick="location.href='settings.php'"><i class="fas fa-gear icon"></i> Settings</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>

    <main class="content">

        <div class="top-bar-organizer">
            <div>
                <h2>Event Report</h2>
                <p class="sub">Analytics & Sales</p>
            </div>
            <div class="organizer-right">
                <img src="../assets/profile2.png" class="organizer-profile">
            </div>
        </div>

        <button onclick="history.back()" class="back-btn" style="margin-left: 30px;">
            <i class="fas fa-arrow-left"></i> Back
        </button>

        <div class="report-wrapper">
            
            <h2 class="report-title"><?php echo htmlspecialchars($data['EventName']); ?></h2>
            <p class="report-sub">Performance Summary</p>

            <div class="stats-grid">

                <div class="stat-box purple">
                    <i class="fas fa-ticket fa-2x" style="color:#ddd; margin-bottom:10px;"></i>
                    <h4>Tickets Sold</h4>
                    <h1><?php echo number_format($data['TotalTicketsSold'] ?? 0); ?></h1>
                </div>

                <div class="stat-box green">
                    <i class="fas fa-money-bill-wave fa-2x" style="color:#ddd; margin-bottom:10px;"></i>
                    <h4>Total Revenue</h4>
                    <h1><?php echo number_format($data['TotalRevenue'] ?? 0, 2); ?> ฿</h1>
                </div>

                <div class="stat-box blue">
                    <i class="fas fa-chair fa-2x" style="color:#ddd; margin-bottom:10px;"></i>
                    <h4>Remaining Seats</h4>
                    <h1><?php echo number_format($data['RemainingSeats'] ?? 0); ?></h1>
                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>