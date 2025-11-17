<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Handle Event Deletion
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);
    
    $stmt = $conn->prepare("DELETE FROM Event WHERE event_id = ?");
    $stmt->bind_param("i", $id_to_delete);
    
    if ($stmt->execute()) {
        header("Location: manage-events.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

// 3. Fetch All Events
$events = [];
$sql = "SELECT Event.*, Venue.name AS venue_name 
        FROM Event 
        LEFT JOIN Venue ON Event.venue_id = Venue.venue_id 
        ORDER BY Event.date DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        // A. Image Logic
        if (!empty($row['event_image'])) {
            $row['img'] = "../assets/" . $row['event_image'];
        } else {
            $row['img'] = "../assets/default_event.jpg"; 
        }

        // B. IMPROVED STATUS LOGIC
        // 1. Check if Admin manually set it to Closed/Sold Out
        if (isset($row['status']) && ($row['status'] == 'Sold Out' || $row['status'] == 'Closed')) {
            $row['status_text'] = $row['status'];
            $row['status_color'] = "red";
        } 
        // 2. Check if seats ran out (even if status says Available)
        elseif ($row['available_seats'] <= 0) {
            $row['status_text'] = "Sold Out";
            $row['status_color'] = "red";
        } 
        // 3. Otherwise, it's Available
        else {
            $row['status_text'] = "Available";
            $row['status_color'] = "green";
        }

        // C. Formatting
        $row['formatted_date'] = date("d/m/y", strtotime($row['date']));
        $row['formatted_time'] = date("h:i A", strtotime($row['time']));
        $row['formatted_price'] = number_format($row['price'], 0);

        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/manage-users.css?v=<?php echo time(); ?>"> 

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Specific grid tweak for Events */
        .list-labels, .event-item {
            grid-template-columns: 40% 20% 20% 20%; 
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
            <li onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>
            <li class="active"><i class="fas fa-calendar-check icon"></i> Manage Events</li>
            <li onclick="location.href='manage-payments.php'"><i class="fas fa-credit-card icon"></i> Manage Payments</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, Admin!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>
            <div class="right">
                <div class="search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search events...">
                </div>
                <img src="../assets/profile3.png" class="profile">
            </div>
        </div>

        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="event-layout">

            <div class="event-details" id="details-box">
                <h2 class="details-title">Event Details</h2>

                <?php if (count($events) > 0): $first = $events[0]; ?>

                <div class="details-card">
                    <img id="event-image" src="<?php echo $first['img']; ?>" class="event-image" style="object-fit: cover;">

                    <span class="status-badge <?php echo $first['status_color']; ?>" id="event-status">
                        <?php echo $first['status_text']; ?>
                    </span>

                    <div class="info-list">
                        <p><i class="fas fa-location-dot"></i> <b>Venue</b><br>
                           <span id="event-venue"><?php echo htmlspecialchars($first['venue_name']); ?></span>
                        </p>
                        <p><i class="fas fa-clock"></i> <b>Time</b><br>
                           <span id="event-time"><?php echo $first['formatted_time']; ?></span>
                        </p>
                        <p><i class="fas fa-calendar-day"></i> <b>Date</b><br>
                           <span id="event-date"><?php echo $first['formatted_date']; ?></span>
                        </p>
                        <p><i class="fas fa-coins"></i> <b>Price</b><br>
                           <span id="event-price"><?php echo $first['formatted_price']; ?> THB</span>
                        </p>
                    </div>
                </div>

                <div class="action-buttons">
                    <a id="btn-edit-link" href="edit-event.php?id=<?php echo $first['event_id']; ?>" class="btn-edit">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <a id="btn-delete-link" href="manage-events.php?delete_id=<?php echo $first['event_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this event?');">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>

                <?php else: ?>
                    <p>No events found.</p>
                <?php endif; ?>
            </div>

            <div class="event-list">

                <div class="list-header">
                    <h2>All Events</h2>
                    <div class="right-btns">
                         <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>

                <div class="list-labels">
                    <span>Event</span>
                    <span>Date</span>
                    <span>Time</span>
                    <span>Status</span>
                </div>

                <div id="event-items">
                    <?php foreach($events as $index => $evt): ?>
                        <div class="event-item <?php echo ($index === 0) ? 'active' : ''; ?>" 
                             onclick="selectEvent(<?php echo $index; ?>, this)">
                            
                            <span><?php echo htmlspecialchars($evt['name']); ?></span>
                            <span><?php echo $evt['formatted_date']; ?></span>
                            <span><?php echo $evt['formatted_time']; ?></span>
                            
                            <span>
                                <span class="dot <?php echo $evt['status_color']; ?>"></span>
                                <?php echo $evt['status_text']; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="view-all">
                    <a href="#">Show All Events <i class="fas fa-chevron-down"></i></a>
                </div>

            </div>

        </div>

    </main>

</div>

<script>
/* PASS PHP DATA TO JS */
const eventData = <?php echo json_encode($events); ?>;

function selectEvent(index, element) {
    // 1. Handle UI Active Class
    document.querySelectorAll(".event-item").forEach(i => i.classList.remove("active"));
    element.classList.add("active");

    // 2. Get Data
    const evt = eventData[index];

    // 3. Update Details
    document.getElementById("event-image").src = evt.img;
    document.getElementById("event-venue").innerText = evt.venue_name;
    document.getElementById("event-time").innerText = evt.formatted_time;
    document.getElementById("event-date").innerText = evt.formatted_date;
    document.getElementById("event-price").innerText = evt.formatted_price + " THB";
    
    let badge = document.getElementById("event-status");
    badge.innerText = evt.status_text;
    badge.className = "status-badge " + evt.status_color;

    // 4. Update Buttons
    document.getElementById("btn-edit-link").href = "edit-event.php?id=" + evt.event_id;
    document.getElementById("btn-delete-link").href = "manage-events.php?delete_id=" + evt.event_id;
}
</script>

</body>
</html>