<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Receive Data from Select Seat Page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = intval($_POST['event_id']);
    $selected_seats = $_POST['selected_seats']; // String like "A1,A2"
    $total_price = floatval($_POST['total_price']);
} else {
    // If accessed directly without data, redirect back
    header("Location: dashboard.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$customer_name = $_SESSION['username'] ?? 'Customer';

// 3. Fetch Event Details for Display
$sql = "SELECT * FROM Event WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Formatting
$event_img = !empty($event['event_image']) ? "../assets/" . $event['event_image'] : "../assets/default.jpg";
$show_date = date("l, F j, Y", strtotime($event['date']));
$show_time = date("h:i A", strtotime($event['time']));
$formatted_price = number_format($total_price, 0);

// 4. Handle "Pay Now" Action
// Note: Ideally, this would be a separate processing file, but we can handle it here for simplicity.
// We use a hidden form to submit the final confirmation.
if (isset($_POST['confirm_payment'])) {
    
    $method = $_POST['payment_method'];
    $seat_array = explode(",", $selected_seats);
    
    // Start Transaction (Ensures all queries succeed or none do)
    $conn->begin_transaction();

    try {
        // A. Insert Tickets
        $ticket_stmt = $conn->prepare("INSERT INTO Ticket (event_id, user_id, seat_no, status) VALUES (?, ?, ?, 'Booked')");
        
        // B. Insert Payments
        $payment_stmt = $conn->prepare("INSERT INTO Payment (ticket_id, amount, method, status) VALUES (?, ?, ?, 'Completed')");
        
        $price_per_ticket = $total_price / count($seat_array);

        foreach ($seat_array as $seat) {
            // 1. Insert Ticket
            $ticket_stmt->bind_param("iis", $event_id, $customer_id, $seat);
            $ticket_stmt->execute();
            $new_ticket_id = $conn->insert_id;

            // 2. Insert Payment for this ticket
            $payment_stmt->bind_param("ids", $new_ticket_id, $price_per_ticket, $method);
            $payment_stmt->execute();
        }

        // C. Update Event Available Seats
        $seat_count = count($seat_array);
        $update_event = $conn->prepare("UPDATE Event SET available_seats = available_seats - ? WHERE event_id = ?");
        $update_event->bind_param("ii", $seat_count, $event_id);
        $update_event->execute();

        // Commit Transaction
        $conn->commit();
        
        // Redirect to Confirmation
        echo "<script>window.location.href='booking-confirm.php';</script>";
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Payment Failed: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/payment.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <li onclick="location.href='my-ticket.php'"><i class="fas fa-ticket icon"></i> My Ticket</li>
            <li onclick="location.href='profile.php'"><i class="fas fa-user icon"></i> Profile</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, <?php echo htmlspecialchars($customer_name); ?>!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>
            <div class="right">
                <div class="search"><i class="fas fa-search search-icon"></i><input type="text" placeholder="Search"></div>
                <img src="../assets/profile1.png" class="profile">
            </div>
        </div>

        <button onclick="history.back()" class="back-btn"><i class="fas fa-arrow-left"></i></button>

        <div class="event-header">
            <div class="event-step"><h3>Step 3<br>Payment</h3></div>
            <img src="<?php echo $event_img; ?>" class="event-thumb">
            <div class="event-header-right">
                <h2><?php echo htmlspecialchars($event['name']); ?></h2>
                <p>Showtime : <?php echo $show_date . " • " . $show_time; ?></p>
                <p style="margin-top: 5px; font-size: 14px; color: #ddd;">
                    Selected Seats: <b><?php echo htmlspecialchars($selected_seats); ?></b>
                </p>
                <p style="font-size: 18px; font-weight: 700; margin-top: 5px;">
                    Total: <?php echo $formatted_price; ?> THB
                </p>
            </div>
        </div>

        <h2 class="section-title">Payment Methods</h2>
        
        <form method="POST" action="" id="payment-form">
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <input type="hidden" name="selected_seats" value="<?php echo $selected_seats; ?>">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <input type="hidden" name="confirm_payment" value="1">
            <input type="hidden" name="payment_method" id="method-input" value="CreditCard">

            <div class="payment-methods">
                <div class="pay-card active" onclick="selectMethod(this, 'CreditCard')">
                    <i class="fas fa-credit-card pay-icon"></i>
                    <p>Credit/Debit Card</p>
                </div>
                <div class="pay-card" onclick="selectMethod(this, 'PayPal')">
                    <i class="fas fa-mobile-screen-button pay-icon"></i>
                    <p>Mobile Banking</p>
                </div>
                <div class="pay-card" onclick="selectMethod(this, 'Cash')">
                    <i class="fas fa-store pay-icon"></i>
                    <p>Cash on 7-11 store</p>
                </div>
            </div>

            <div class="paynow-center">
                <button type="submit" class="paynow-btn">
                    Pay Now
                </button>
            </div>
        </form>

    </main>

</div>

<script>
function selectMethod(element, method) {
    // Update UI
    document.querySelectorAll(".pay-card").forEach(c => c.classList.remove("active"));
    element.classList.add("active");
    
    // Update Hidden Input
    document.getElementById("method-input").value = method;
}
</script>

</body>
</html>