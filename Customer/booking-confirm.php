<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

$customer_id = $_SESSION['user_id'];
$customer_name = $_SESSION['username'] ?? 'Customer';

// 2. Find the LATEST payment made by this user
// We order by payment_id DESC to get the absolute last entry
$last_pay_sql = "SELECT payment_id, date FROM Payment 
                 JOIN Ticket ON Payment.ticket_id = Ticket.ticket_id 
                 WHERE Ticket.user_id = ? 
                 ORDER BY payment_id DESC LIMIT 1";

$stmt = $conn->prepare($last_pay_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$last_pay_result = $stmt->get_result();
$last_pay_row = $last_pay_result->fetch_assoc();
$stmt->close();

if (!$last_pay_row) {
    // No bookings found, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

// 3. Fetch ALL tickets associated with that transaction time (approximate batch)
// We fetch tickets for the same event booked within a 1-minute window of the last payment
// This groups the seats together (e.g. A1 and A2)
$transaction_time = $last_pay_row['date'];
$order_id = "ORD-" . str_pad($last_pay_row['payment_id'], 8, "0", STR_PAD_LEFT); // Generate ID

$sql = "SELECT 
            E.name AS event_name, 
            E.date AS show_date, 
            E.time AS show_time, 
            E.event_image,
            V.name AS venue_name, 
            T.seat_no,
            P.amount,
            P.date AS payment_date,
            U.phone
        FROM Payment P
        JOIN Ticket T ON P.ticket_id = T.ticket_id
        JOIN Event E ON T.event_id = E.event_id
        JOIN Venue V ON E.venue_id = V.venue_id
        JOIN User U ON T.user_id = U.user_id
        WHERE T.user_id = ? AND P.date = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $customer_id, $transaction_time);
$stmt->execute();
$result = $stmt->get_result();

// 4. Aggregate Data
$seats = [];
$total_price = 0;
$event_data = null;

while ($row = $result->fetch_assoc()) {
    // Capture event details from the first row
    if (!$event_data) {
        $event_data = $row;
    }
    $seats[] = $row['seat_no'];
    $total_price += $row['amount'];
}
$stmt->close();

// 5. Format Variables for HTML
$order_date = date("l, F j, Y, h:i A", strtotime($event_data['payment_date']));
$concert_name = $event_data['event_name'];
$venue = $event_data['venue_name'];
$show_date = date("l, F j, Y", strtotime($event_data['show_date']));
$show_time = date("h:i A", strtotime($event_data['show_time'])); // e.g. 05:00 PM
$seat_number = implode(", ", $seats);
$phone = $event_data['phone'];

// Fees (Static or calculated)
$service_fee = 0; 
$total_amount = $total_price + $service_fee;

// Formatting Currency
$formatted_price = number_format($total_price, 0) . " THB";
$formatted_total = number_format($total_amount, 0) . " THB";
$formatted_fee = number_format($service_fee, 0) . " THB";

// Image Logic
$event_img = !empty($event_data['event_image']) ? "../assets/" . $event_data['event_image'] : "../assets/default_event.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>

    <link rel="stylesheet" href="../css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/booking-confirm.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="layout">

    <div class="sidebar">
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
    </div>


    <div class="content">

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

        <div style="margin-bottom:20px;">
            <button onclick="history.back()" style="background:none;border:none;font-size:24px;cursor:pointer;">
                <i class="fas fa-arrow-left"></i>
            </button>
        </div>


       <div class="event-header">

            <div class="event-step">
                <h3>Step 4<br>Booking Confirmation</h3>
            </div>

            <img src="<?php echo $event_img; ?>" class="event-thumb">

            <div class="event-header-right">
                <h2><?php echo htmlspecialchars($concert_name); ?></h2>
                <p>Showtime : <?php echo $show_date . " • " . $show_time; ?></p>
            </div>

        </div>


        <div class="confirm-container">

            <h2 class="section-title">Booking Confirmation</h2>

            <div class="confirm-grid">

                <div class="left-col">
                    <p><b>Name :</b> <?php echo htmlspecialchars($customer_name); ?></p>
                    <p><b>Order Number :</b> <?php echo $order_id; ?></p>
                    <p><b>Order Date :</b> <?php echo $order_date; ?></p>
                    <p><b>Item Purchased :</b> <?php echo htmlspecialchars($concert_name); ?></p>
                    <p><b>Venue :</b> <?php echo htmlspecialchars($venue); ?></p>
                    <p><b>Show Date :</b> <?php echo $show_date; ?></p>
                    <p><b>Tickets seat :</b> <?php echo htmlspecialchars($seat_number); ?></p>
                </div>

                <div class="right-col">
                    <p><b>Phone Number :</b> <?php echo htmlspecialchars($phone); ?></p>
                    <p><b>Price :</b> <?php echo $formatted_price; ?></p>
                    <p><b>Number of Tickets :</b> <?php echo count($seats); ?></p>
                    <p><b>Service Fee :</b> <?php echo $formatted_fee; ?></p>
                    <p><b>Ticket Protect Insurance (7%) :</b> 0 THB</p>
                    <p><b>Delivery Fee :</b> 0 THB</p>
                    <p><b>Total Amount :</b> <?php echo $formatted_total; ?></p>
                </div>

            </div>

        </div>

    </div></div></body>
</html>