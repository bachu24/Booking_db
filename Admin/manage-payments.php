<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Handle Deletion
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM Payment WHERE payment_id = ?");
    $stmt->bind_param("i", $id_to_delete);
    $stmt->execute();
    $stmt->close();
    header("Location: manage-payments.php");
    exit();
}

// 3. Fetch All Payments (JOIN 4 Tables!)
$payments = [];
$sql = "SELECT 
            Payment.*, 
            Ticket.seat_no, 
            Event.name AS event_name, 
            User.name AS customer_name 
        FROM Payment
        JOIN Ticket ON Payment.ticket_id = Ticket.ticket_id
        JOIN Event ON Ticket.event_id = Event.event_id
        JOIN User ON Ticket.user_id = User.user_id
        ORDER BY Payment.date DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        // Format Date & Time
        $row['formatted_date'] = date("d M Y", strtotime($row['date']));
        $row['formatted_time'] = date("h:i A", strtotime($row['date']));
        $row['full_datetime'] = date("d M Y, h:i A", strtotime($row['date']));
        
        // Format Money
        $row['formatted_amount'] = number_format($row['amount'], 0);
        
        // Status Color Logic
        if ($row['status'] == 'Completed') {
            $row['status_label'] = 'Success'; // Display as Success
            $row['color_class'] = 'green';
            $row['text_color'] = '#1e7b1e';
        } elseif ($row['status'] == 'Pending') {
            $row['status_label'] = 'Pending';
            $row['color_class'] = 'blue';
            $row['text_color'] = '#1e40af';
        } else {
            $row['status_label'] = $row['status']; // Refunded/Failed
            $row['color_class'] = 'red';
            $row['text_color'] = '#b80000';
        }

        $payments[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/manage-payments.css?v=<?php echo time(); ?>">

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
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar icon"></i> Manage Events</li>
            <li class="active"><i class="fas fa-credit-card icon"></i> Manage Payments</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, Admin!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>
            <img src="../assets/profile3.png" class="profile">
        </div>

        <div class="payment-layout">

            <div class="payment-details">

                <h2 class="pd-title">Payment Details</h2>

                <?php if (count($payments) > 0): $first = $payments[0]; ?>

                <div class="pd-card">
                    <p><b>Payment ID</b><br><span id="pd-id">#PAY<?php echo $first['payment_id']; ?></span></p>
                    <p><b>Customer Name</b><br><span id="pd-name"><?php echo htmlspecialchars($first['customer_name']); ?></span></p>
                    <p><b>Event Name</b><br><span id="pd-event"><?php echo htmlspecialchars($first['event_name']); ?></span></p>
                    <p><b>Ticket Seats</b><br><span id="pd-seats"><?php echo htmlspecialchars($first['seat_no']); ?></span></p>
                    <p><b>Amount (฿)</b><br><span id="pd-amount"><?php echo $first['formatted_amount']; ?></span></p>
                    <p><b>Transaction Date</b><br><span id="pd-date"><?php echo $first['full_datetime']; ?></span></p>
                    <p><b>Method</b><br><span id="pd-method"><?php echo $first['method']; ?></span></p>
                    
                    <p><b>Status</b><br>
                        <span id="pd-status" style="color: <?php echo $first['text_color']; ?>; font-weight:600;">
                            <?php echo $first['status_label']; ?>
                        </span>
                    </p>
                </div>

                <div class="pd-actions">
                    <a id="btn-edit-link" href="edit-payment.php?id=<?php echo $first['payment_id']; ?>" class="btn-edit" style="text-decoration:none; display:inline-flex; justify-content:center; align-items:center; gap:8px; color:white; padding:12px; border-radius:12px; flex:1;">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    
                    <a id="btn-delete-link" href="manage-payments.php?delete_id=<?php echo $first['payment_id']; ?>" class="btn-delete" style="text-decoration:none; display:inline-flex; justify-content:center; align-items:center; gap:8px; color:white; padding:12px; border-radius:12px; flex:1;" onclick="return confirm('Are you sure?');">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>

                <?php else: ?>
                    <p>No payments found.</p>
                <?php endif; ?>

            </div>

            <div class="payment-list">

                <div class="list-header">
                    <h2>All Payments</h2>
                    <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>

                <div class="list-labels">
                    <span>Payment ID</span>
                    <span>Date</span>
                    <span>Time</span>
                    <span>Status</span>
                </div>

                <div id="payment-items">
                    <?php foreach($payments as $index => $pay): ?>
                        <div class="pay-item <?php echo ($index === 0) ? 'active' : ''; ?>" 
                             onclick="selectPay(<?php echo $index; ?>, this)">
                            
                            <span>#PAY<?php echo $pay['payment_id']; ?></span>
                            <span><?php echo $pay['formatted_date']; ?></span>
                            <span><?php echo $pay['formatted_time']; ?></span>
                            
                            <span>
                                <span class="dot <?php echo $pay['color_class']; ?>"></span>
                                <?php echo $pay['status_label']; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="view-all">
                    <a href="#">Show All Payments <i class="fas fa-chevron-down"></i></a>
                </div>

            </div>

        </div>

    </main>

</div>

<script>
/* PASS PHP DATA TO JS */
const payData = <?php echo json_encode($payments); ?>;

function selectPay(index, el) {
    // 1. Handle UI Active Class
    document.querySelectorAll(".pay-item").forEach(i => i.classList.remove("active"));
    el.classList.add("active");

    // 2. Get Data
    const d = payData[index];

    // 3. Update Details
    document.getElementById("pd-id").innerText = "#PAY" + d.payment_id;
    document.getElementById("pd-name").innerText = d.customer_name;
    document.getElementById("pd-event").innerText = d.event_name;
    document.getElementById("pd-seats").innerText = d.seat_no;
    document.getElementById("pd-amount").innerText = d.formatted_amount;
    document.getElementById("pd-date").innerText = d.full_datetime;
    document.getElementById("pd-method").innerText = d.method;

    // 4. Update Status Color
    let statusEl = document.getElementById("pd-status");
    statusEl.innerText = d.status_label;
    statusEl.style.color = d.text_color;

    // 5. Update Buttons
    document.getElementById("btn-edit-link").href = "edit-payment.php?id=" + d.payment_id;
    document.getElementById("btn-delete-link").href = "manage-payments.php?delete_id=" + d.payment_id;
}
</script>

</body>
</html>