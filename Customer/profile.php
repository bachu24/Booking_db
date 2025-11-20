<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

$customer_id = $_SESSION['user_id'];
$message = "";
$error = "";

// 2. Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        // If password is provided, update it too
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE User SET name=?, email=?, phone=?, password=? WHERE user_id=?");
            $stmt->bind_param("ssssi", $name, $email, $phone, $hashed_password, $customer_id);
        } else {
            // Update info only
            $stmt = $conn->prepare("UPDATE User SET name=?, email=?, phone=? WHERE user_id=?");
            $stmt->bind_param("sssi", $name, $email, $phone, $customer_id);
        }

        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
            // Update Session Name
            $_SESSION['username'] = $name;
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    }
}

// 3. Fetch User Data
$stmt = $conn->prepare("SELECT * FROM User WHERE user_id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Image Logic
$profile_img = !empty($user['profile_image']) ? "../assets/" . $user['profile_image'] : "../assets/default.jpg";

// 4. Fetch Ticket History
$tickets = [];
$sql = "SELECT 
            T.ticket_id, 
            T.seat_no, 
            T.status, 
            E.name AS event_name, 
            E.date AS event_date, 
            E.time AS event_time, 
            V.name AS venue_name 
        FROM Ticket T
        JOIN Event E ON T.event_id = E.event_id
        JOIN Venue V ON E.venue_id = V.venue_id
        WHERE T.user_id = ?
        ORDER BY T.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Format Date
    $row['formatted_date'] = date("d M Y", strtotime($row['event_date']));
    $row['formatted_time'] = date("h:i A", strtotime($row['event_time']));
    $tickets[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        /* Simple Profile CSS */
        .profile-container {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }
        .profile-card {
            width: 350px;
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: fit-content;
        }
        .profile-img-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 4px solid #f3f0ff;
        }
        .history-section {
            flex: 1;
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group label { font-size: 13px; color: #666; font-weight: 600; display: block; margin-bottom: 5px; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #eee; border-radius: 8px; font-family: 'Poppins'; }
        .btn-save {
            width: 100%; background: linear-gradient(90deg, #8d5bff, #5f2cff); color: white; border: none;
            padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; margin-top: 10px;
        }
        
        /* Ticket List */
        .ticket-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px; border-bottom: 1px solid #f0f0f0;
        }
        .ticket-item:last-child { border-bottom: none; }
        .t-date { font-size: 12px; color: #888; font-weight: 600; text-transform: uppercase; }
        .t-name { font-size: 16px; font-weight: 600; color: #333; margin: 2px 0; }
        .t-venue { font-size: 13px; color: #666; }
        .t-seat { background: #f3f0ff; color: #5f2cff; padding: 5px 12px; border-radius: 15px; font-weight: 600; font-size: 13px; }
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
            <li class="active" onclick="location.href='profile.php'"><i class="fas fa-user icon"></i> Profile</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>My Profile</h2>
                <p class="sub">Manage your account and view bookings</p>
            </div>
            <div class="right">
                <img src="<?php echo $profile_img; ?>" class="profile">
            </div>
        </div>

        <div class="profile-container">

            <div class="profile-card">
                <img src="<?php echo $profile_img; ?>" class="profile-img-large">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p style="font-size:13px; color:#888;"><?php echo htmlspecialchars($user['email']); ?></p>
                
                <?php if($message): ?>
                    <p style="color:green; font-size:13px; margin-top:10px;"><?php echo $message; ?></p>
                <?php endif; ?>
                
                <form method="POST" action="" style="margin-top: 20px;">
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    <div class="input-group">
                        <label>New Password (Optional)</label>
                        <input type="password" name="password" placeholder="********">
                    </div>
                    <button type="submit" class="btn-save">Save Changes</button>
                </form>
            </div>

            <div class="history-section">
                <h2 style="margin-bottom: 20px;">My Tickets</h2>
                
                <?php if (empty($tickets)): ?>
                    <p style="color:#888; text-align:center; margin-top:30px;">You haven't booked any tickets yet.</p>
                <?php else: ?>
                    <?php foreach ($tickets as $t): ?>
                        <div class="ticket-item">
                            <div>
                                <div class="t-date"><?php echo $t['formatted_date'] . " • " . $t['formatted_time']; ?></div>
                                <div class="t-name"><?php echo htmlspecialchars($t['event_name']); ?></div>
                                <div class="t-venue"><i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($t['venue_name']); ?></div>
                            </div>
                            <div style="text-align:right;">
                                <div class="t-seat">Seat: <?php echo htmlspecialchars($t['seat_no']); ?></div>
                                <div style="font-size:12px; color:#888; margin-top:5px; margin-bottom: 8px;">ID: #<?php echo $t['ticket_id']; ?></div>

                                <?php if ($t['status'] == 'Booked'): ?>
                                    <a href="cancel-ticket.php?id=<?php echo $t['ticket_id']; ?>" 
                                       onclick="return confirm('Are you sure you want to cancel this ticket?');"
                                       style="font-size: 12px; color: #c0392b; text-decoration: underline; cursor: pointer;">
                                       Cancel Ticket
                                    </a>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: #888; font-style: italic;">(Cancelled)</span>
                                <?php endif; ?>
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