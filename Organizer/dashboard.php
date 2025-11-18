<?php
// 1. Start Session & Security Check
session_start();

// Check if user is logged in AND is an Organizer (or Admin for testing purposes)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Organizer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php"); 
    exit();
}

require '../db_connect.php';

// Get Organizer ID and Name from Session
$organizer_id = $_SESSION['user_id'];
$organizer_name = $_SESSION['username'] ?? 'Organizer';

// 2. Fetch Events created by THIS Organizer (Joining Venue and calculating Sold/Remaining)
$events = [];

// SQL Query to fetch event details, venue name, and calculate tickets sold
$sql = "SELECT 
            E.event_id, 
            E.name AS event_title,
            E.event_image,
            E.time, 
            E.total_seats, 
            E.available_seats,
            V.name AS venue_name,
            -- Calculate tickets sold
            (E.total_seats - E.available_seats) AS tickets_sold
        FROM Event E
        JOIN Venue V ON E.venue_id = V.venue_id
        WHERE E.organizer_id = ?
        ORDER BY E.date DESC
        LIMIT 3"; // Limit to 3 for the dashboard preview

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Image Fallback Logic
        $row['img'] = !empty($row['event_image']) 
                     ? "../assets/" . $row['event_image'] 
                     : "../assets/default_event.jpg";
        
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
            <li onclick="location.href='new-event.php'"><i class="fas fa-calendar-plus icon"></i> New Event</li>
            <li onclick="location.href='view-events.php'"><i class="fas fa-eye icon"></i> View Events</li>
            <li onclick="location.href='settings.php'"><i class="fas fa-gear icon"></i> Settings</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
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

            <h3>My Events</h3>
            <p class="small-sub">Total tickets sold of all events</p>

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

            </div></div></main>

</div>

</body>
</html>