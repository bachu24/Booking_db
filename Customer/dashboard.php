<?php
// 1. Start Session & Security Check
session_start();

// Ensure user is logged in AND is a Customer (or Admin for testing purposes)
// Note: If a Customer dashboard is public facing, this check would be less strict.
// But following the role-based model, we check for 'Customer'.
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php"); 
    exit();
}

require '../db_connect.php';

// Get Customer ID and Name from Session
$customer_id = $_SESSION['user_id'];
$customer_name = $_SESSION['username'] ?? 'Customer';

// 2. Fetch Upcoming Events 
$events = [];

// Fetch events that are 'Available' and scheduled for today or later
$sql = "SELECT 
            E.event_id, 
            E.name AS event_name, 
            E.date, 
            E.time, 
            E.event_image,
            V.location AS venue_location 
        FROM Event E
        JOIN Venue V ON E.venue_id = V.venue_id
        WHERE E.status = 'Available' AND E.date >= CURDATE()
        ORDER BY E.date ASC
        LIMIT 6"; // Limit to 6 for the dashboard preview

$stmt = $conn->prepare($sql);
// No bind_param needed for the fixed WHERE clause, but kept structure for clarity.
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        // Image Fallback Logic: Uses default.jpg
        $row['img'] = !empty($row['event_image']) 
                     ? "../assets/" . $row['event_image'] 
                     : "../assets/default.jpg";
        
        // Formatting
        $row['day'] = date("d", strtotime($row['date']));
        $row['month'] = date("M", strtotime($row['date']));
        $row['time_display'] = date("h:i A", strtotime($row['time']));
        
        $events[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard-customer.css?v=<?php echo time(); ?>">

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
            <li class="active" onclick="location.href='dashboard.php'">
                <i class="fa-solid fa-house icon"></i>
                <span>Home</span>
            </li>

    

            <li onclick="location.href='profile.php'">
                <i class="fa-solid fa-user icon"></i>
                <span>Profile</span>
            </li>

            <li onclick="location.href='../home.php'">
                <i class="fa-solid fa-right-from-bracket icon"></i>
                <span>Logout</span>
            </li>
        </ul>

        <div class="help-box">
            <i class="fa-regular fa-circle-question help-icon"></i>
            Help & Support
        </div>
    </aside>


    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, <?php echo htmlspecialchars($customer_name); ?>!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="right">
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" placeholder="keyword, category, location">
                </div>

                <button class="heart-btn">
                    <i class="fa-regular fa-heart"></i>
                </button>

                <img src="../assets/profile1.png" class="profile">
            </div>
        </div>


        <div class="section-title">
            <h2>Upcoming Events</h2>

            <a href="#" class="view-link">
                View All Events →
            </a>
        </div>


        <div class="event-grid">

            <?php if (empty($events)): ?>
                 <p style="text-align: center; width: 100%; margin-top: 50px;">
                    No upcoming events found.
                </p>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="img-box">
                            <span class="date-badge"><?php echo $event['day']; ?><br><?php echo $event['month']; ?></span>
                            <img src="<?php echo $event['img']; ?>">
                        </div>
                        <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        <div class="detail-row"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($event['venue_location']); ?></div>
                        <div class="detail-row"><i class="fa-solid fa-clock"></i> <?php echo $event['time_display']; ?></div>
                        
                        <a href="event-details.php?id=<?php echo $event['event_id']; ?>" class="btn-details">
                            See Details <i class="fa-regular fa-heart"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

    </main>
</div>

</body>
</html>