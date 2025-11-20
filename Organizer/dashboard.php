<?php
// 1. Start Session & Security Check
session_start();

// Ensure user is logged in AND is an Organizer (or Admin)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Organizer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php"); 
    exit();
}

require '../db_connect.php';

// Get Organizer ID and Name
$organizer_id = $_SESSION['user_id'];
$organizer_name = $_SESSION['username'] ?? 'Organizer';

// ---------------------------------------------------------
// 2. CALL STORED PROCEDURE (Get Stats)
// ---------------------------------------------------------
$stats_sql = "CALL GetOrganizerDashboardStats($organizer_id)";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();

// CRITICAL: Clean up the connection! 
// Stored procedures return a secondary result set that must be cleared
// before running another SELECT query.
$stats_result->close();
while($conn->next_result()); 

// Default values if null
$total_events = $stats['TotalEvents'] ?? 0;
$total_sold   = $stats['TotalTicketsSold'] ?? 0;
$total_revenue= $stats['TotalRevenue'] ?? 0;


// ---------------------------------------------------------
// 3. FETCH EVENTS (For the list)
// ---------------------------------------------------------
$events = [];

$sql = "SELECT 
            E.event_id, 
            E.name AS event_title,
            E.event_image,
            E.time, 
            E.total_seats, 
            E.available_seats,
            V.name AS venue_name,
            (E.total_seats - E.available_seats) AS tickets_sold
        FROM Event E
        JOIN Venue V ON E.venue_id = V.venue_id
        WHERE E.organizer_id = ?
        ORDER BY E.date DESC
        LIMIT 3"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Image Fallback
        $row['img'] = !empty($row['event_image']) 
                     ? "../assets/" . $row['event_image'] 
                     : "../assets/default.jpg";
        
        $events[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizer Dashboard</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard-organizer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .purple { background: #f3f0ff; color: #8d5bff; }
        .green { background: #e6fffa; color: #00b894; }
        .blue { background: #e3f2fd; color: #0984e3; }
        .stat-info h4 { font-size: 14px; color: #888; font-weight: 500; margin-bottom: 5px; }
        .stat-info h1 { font-size: 24px; font-weight: 700; color: #333; }
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
            <li class="active"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='new-event.php'"><i class="fas fa-calendar-plus icon"></i> New Event</li>
            <li onclick="location.href='view-events.php'"><i class="fas fa-eye icon"></i> View Events</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
        <div class="help-box"><i class="fas fa-circle-question help-icon"></i> Help & Support</div>
    </aside>

    <main class="content">

        <div class="top-bar-organizer">
            <div>
                <h2>Welcome Back, <?php echo htmlspecialchars($organizer_name); ?>!</h2> 
                <p class="sub">Exclusive Events Await!</p>
            </div>
            <div class="organizer-right">
                <div class="organizer-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Events">
                </div>
                <img src="../assets/profile3.png" class="organizer-profile">
            </div>
        </div>

        <div class="organizer-section">
            
            <div class="stats-grid">
                
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-info">
                        <h4>Total Revenue</h4>
                        <h1><?php echo number_format($total_revenue); ?> ฿</h1>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon purple"><i class="fas fa-ticket"></i></div>
                    <div class="stat-info">
                        <h4>Tickets Sold</h4>
                        <h1><?php echo number_format($total_sold); ?></h1>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-calendar-day"></i></div>
                    <div class="stat-info">
                        <h4>My Events</h4>
                        <h1><?php echo number_format($total_events); ?></h1>
                    </div>
                </div>

            </div>
            <h3>Recent Events</h3>
            <p class="small-sub">Quick overview of your latest events</p>

            <a href="view-events.php" class="view-all-events">View All Events</a>

            <div class="events-row">
                <?php if (empty($events)): ?>
                    <p style="padding: 20px; color: #888;">You haven't created any events yet. Click 'New Event' to begin!</p>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <div class="event-card" onclick="location.href='view-events.php?id=<?php echo $event['event_id']; ?>'">
                            <img src="<?php echo $event['img']; ?>" class="event-thumb">

                            <div class="event-info">
                                <p class="event-title"><?php echo htmlspecialchars($event['event_title']); ?></p>

                                <div class="event-sub">
                                    <i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($event['venue_name']); ?>
                                </div>

                                <div class="event-time">
                                    <i class="fas fa-clock"></i> <?php echo date("h:i A", strtotime($event['time'])); ?>
                                </div>
                            </div>

                            <div class="event-stats">
                                <div class="stats-number"><?php echo number_format($event['tickets_sold']); ?></div>
                                <div class="stats-label">Number of Tickets Sold</div>

                                <div class="event-stats-row">
                                    <div class="stats-number small"><?php echo number_format($event['available_seats']); ?></div>
                                    <div class="stats-label">Number of Tickets Remaining</div>
                                    <span class="stats-arrow"><i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </main>

</div>

</body>
</html>