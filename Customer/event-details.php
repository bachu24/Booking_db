<?php
// 1. Start Session & Security Check
session_start();

// Ensure user is logged in as Customer or Admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php"); 
    exit();
}

require '../db_connect.php';

// 2. Get Event ID from URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$event_id = intval($_GET['id']);
$customer_name = $_SESSION['username'] ?? 'Customer';

// 3. Fetch Event Details
$sql = "SELECT 
            E.*, 
            V.name AS venue_name,
            V.location AS venue_location
        FROM Event E
        JOIN Venue V ON E.venue_id = V.venue_id
        WHERE E.event_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

// If event doesn't exist, redirect back
if (!$event) {
    echo "<script>alert('Event not found.'); window.location.href='dashboard.php';</script>";
    exit();
}

// 4. Format Data for Display
// Image
$event_img = !empty($event['event_image']) ? "../assets/" . $event['event_image'] : "../assets/default.jpg";

// Dates & Times
$show_date = date("l, F j, Y", strtotime($event['date'])); // e.g., Saturday, October 18, 2025
$gates_open = date("g:i A", strtotime($event['time']));   // e.g., 4:00 PM

// Logic for Status
$is_available = ($event['status'] == 'Available' && $event['available_seats'] > 0);
$status_text = $is_available ? "ON SALE NOW" : "SOLD OUT / UNAVAILABLE";
$status_class = $is_available ? "status-green" : "status-red";

// Price formatting
$price_display = number_format($event['price'], 0);

// Note: "Tickets on sale" date isn't in your DB schema yet, so we'll just use "Now Available" or hide it.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($event['name']); ?> - Details</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/event-details.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Quick utility classes for dynamic status */
        .status-green { color: #27ae60; font-weight: 700; text-transform: uppercase; }
        .status-red { color: #c0392b; font-weight: 700; text-transform: uppercase; }
        
        /* Disable button style */
        .btn-disabled {
            background-color: #ccc;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
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
            <li onclick="location.href='dashboard.php'"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='profile.php'"><i class="fas fa-user icon"></i> Profile</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
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
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="keyword, category, location">
                </div>

                <button class="heart-btn"><i class="fas fa-heart"></i></button>
                <img src="../assets/profile1.png" class="profile">
            </div>
        </div>


        <button class="back-btn" onclick="history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>


        <section class="event-header">

            <div class="event-img">
                <img src="<?php echo $event_img; ?>" alt="Event Poster">

                <?php if($is_available): ?>
                    <button class="select-seat-btn" onclick="location.href='select-seat.php?id=<?php echo $event_id; ?>'">
                        Select Seat
                    </button>
                <?php else: ?>
                    <button class="select-seat-btn btn-disabled">
                        Sold Out / Unavailable
                    </button>
                <?php endif; ?>
            </div>

            <div class="event-info">

                <h1><?php echo htmlspecialchars($event['name']); ?></h1>

                <div class="info-grid">

                    <div class="info-box">
                        <i class="fas fa-calendar-days"></i>
                        <div>
                            <h4>The show dates</h4>
                            <p><?php echo $show_date; ?></p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-ticket"></i>
                        <div>
                            <h4>Tickets on sale</h4>
                            <p>Now Available</p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-location-dot"></i>
                        <div>
                            <h4>Venue</h4>
                            <p><?php echo htmlspecialchars($event['venue_name']); ?></p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>
                            <h4>Price</h4>
                            <p><?php echo $price_display; ?> THB</p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-door-open"></i>
                        <div>
                            <h4>Gates open</h4>
                            <p><?php echo $gates_open; ?></p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h4>Ticket Status</h4>
                            <p class="<?php echo $status_class; ?>"><?php echo $status_text; ?></p>
                        </div>
                    </div>

                </div>
            </div>

        </section>


        <section class="details-section">

            <h2>Concert Details</h2>

            <?php if(!empty($event['description'])): ?>
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            <?php else: ?>
                <p><strong>Event Description:</strong></p>
                <p>Join us for an amazing experience at <?php echo htmlspecialchars($event['venue_name']); ?>. Don't miss out on this exclusive event!</p>
                
                <p>Benefit Registration: 2 hours before showtime.</p>
                <p><strong>Note:</strong> Please arrive early for security checks. Digital or printed tickets are accepted.</p>
            <?php endif; ?>

        </section>

    </main>
</div>

</body>
</html>