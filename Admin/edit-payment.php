<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Get Payment ID
if (!isset($_GET['id'])) {
    header("Location: manage-payments.php");
    exit();
}
$payment_id = intval($_GET['id']);
$message = "";
$error = "";

// 3. Handle Form Submission (Update Status & Notes)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $notes = trim($_POST['notes']);

    $stmt = $conn->prepare("UPDATE Payment SET status = ?, notes = ? WHERE payment_id = ?");
    $stmt->bind_param("ssi", $status, $notes, $payment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Payment Updated Successfully!'); window.location.href='manage-payments.php';</script>";
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// 4. Fetch Payment Data (JOIN tables to get names and seats)
$sql = "SELECT 
            Payment.*, 
            Ticket.seat_no, 
            Event.name AS event_name, 
            User.name AS customer_name 
        FROM Payment
        JOIN Ticket ON Payment.ticket_id = Ticket.ticket_id
        JOIN Event ON Ticket.event_id = Event.event_id
        JOIN User ON Ticket.user_id = User.user_id
        WHERE Payment.payment_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$pay = $result->fetch_assoc();
$stmt->close();

if (!$pay) { echo "Payment record not found."; exit(); }

// Formatting for Display
$formatted_date = date("d M Y, h:i A", strtotime($pay['date']));
$formatted_amount = number_format($pay['amount'], 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Payments</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/edit-payment.css?v=<?php echo time(); ?>">

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
            <li onclick="location.href='dashboard.php'"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='manage-users.php'"><i class="fas fa-user icon"></i> Manage Users</li>
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar-check icon"></i> Manage Events</li>
            <li class="active"><i class="fas fa-credit-card icon"></i> Manage Payments</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, Admin!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>
            <img src="../assets/profile3.png" class="profile">
        </div>

        <a href="manage-payments.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="edit-wrapper">

            <p class="warning-text">
                For data integrity and privacy reasons,<br>
                only payment status and remarks can be updated*
            </p>

            <h1 class="title">Edit Payments</h1>
            
            <?php if($error): ?>
                <p style="color:red; text-align:center;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="edit-grid">

                    <div class="label">PAYMENT ID</div>
                    <div class="value">
                        <input type="text" value="#PAY<?php echo $pay['payment_id']; ?>" disabled>
                    </div>

                    <div class="label">CUSTOMER NAME</div>
                    <div class="value">
                        <input type="text" value="<?php echo htmlspecialchars($pay['customer_name']); ?>" disabled>
                    </div>

                    <div class="label">EVENT NAME</div>
                    <div class="value">
                        <input type="text" value="<?php echo htmlspecialchars($pay['event_name']); ?>" disabled>
                    </div>

                    <div class="label">TICKET SEATS</div>
                    <div class="value">
                        <input type="text" value="<?php echo htmlspecialchars($pay['seat_no']); ?>" disabled>
                    </div>

                    <div class="label">AMOUNT (฿)</div>
                    <div class="value">
                        <input type="text" value="<?php echo $formatted_amount; ?>" disabled>
                    </div>

                    <div class="label">TRANSACTION DATE</div>
                    <div class="value">
                        <input type="text" value="<?php echo $formatted_date; ?>" disabled>
                    </div>

                    <div class="label">METHOD</div>
                    <div class="value">
                        <input type="text" value="<?php echo htmlspecialchars($pay['method']); ?>" disabled>
                    </div>

                    <div class="label">PAYMENT STATUS</div>
                    <div class="value">
                        <select name="status">
                            <option value="Completed" <?php if($pay['status']=='Completed') echo 'selected'; ?>>Success (Completed)</option>
                            <option value="Pending" <?php if($pay['status']=='Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Refunded" <?php if($pay['status']=='Refunded') echo 'selected'; ?>>Failed / Refunded</option>
                        </select>
                        <i class="fa-solid fa-pen icon-edit"></i>
                    </div>

                    <div class="label">NOTES / REMARKS</div>
                    <div class="value">
                        <input type="text" name="notes" placeholder="Enter remarks..." value="<?php echo htmlspecialchars($pay['notes'] ?? ''); ?>">
                        <i class="fa-solid fa-pen icon-edit"></i>
                    </div>

                </div>

                <button type="submit" class="save-btn">Save Changes</button>

            </form>

        </div>

    </main>
</div>

</body>
</html>