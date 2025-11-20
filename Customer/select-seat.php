<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Get Event ID
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$event_id = intval($_GET['id']);
$customer_name = $_SESSION['username'] ?? 'Customer';

// 3. Fetch Event Details
$sql = "SELECT * FROM Event WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    echo "<script>alert('Event not found.'); window.location.href='dashboard.php';</script>";
    exit();
}

// 4. Fetch Booked Seats
$booked_seats = [];
$seat_sql = "SELECT seat_no FROM Ticket WHERE event_id = ? AND status = 'Booked'";
$seat_stmt = $conn->prepare($seat_sql);
$seat_stmt->bind_param("i", $event_id);
$seat_stmt->execute();
$seat_result = $seat_stmt->get_result();

while ($row = $seat_result->fetch_assoc()) {
    $booked_seats[] = $row['seat_no'];
}
$seat_stmt->close();

// Formatting
$event_img = !empty($event['event_image']) ? "../assets/" . $event['event_image'] : "../assets/default.jpg";
$show_date = date("l, F j, Y", strtotime($event['date']));
$show_time = date("h:i A", strtotime($event['time']));
$formatted_price = number_format($event['price'], 0);

$rows = ["AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Seats</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/select-seat.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        <div class="seat-banner">
            <div class="seat-step"><h1>Step 2<br>Select Seats</h1></div>
            <img src="<?php echo $event_img; ?>" class="seat-cover">
            <div class="seat-info">
                <h2><?php echo htmlspecialchars($event['name']); ?></h2>
                <p>Showtime : <?php echo $show_date . " • " . $show_time; ?></p>
            </div>
        </div>

        <div class="seat-layout-box">

            <div class="seat-map">
                <div class="legend">
                    <span class="dot available"></span> <?php echo $formatted_price; ?> THB
                    <span class="dot selected"></span> Selected  
                    <span class="dot blocked"></span> Unavailable
                </div>

                <h3 class="stage-title">STAGE</h3>

                <div class="seat-grid">
                    <?php foreach ($rows as $row): ?>
                        <div class="seat-row">
                            <span class="row-label"><?= $row ?></span>
                            <?php for ($s=1; $s <= 20; $s++): 
                                $seatCode = $row . str_pad($s,2,"0",STR_PAD_LEFT);
                                $is_booked = in_array($seatCode, $booked_seats);
                                $class = $is_booked ? "blocked" : "available";
                            ?>
                                <div class="seat <?= $class ?>" data-seat="<?= $seatCode ?>">
                                    <?= str_pad($s,2,"0",STR_PAD_LEFT) ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="booking-details">
                <h2>Booking Details</h2>

                <div class="detail-box">
                    <p class="label">Showtime</p>
                    <p class="value"><?php echo $show_date; ?><br><?php echo $show_time; ?></p>
                </div>

                <div class="detail-box">
                    <p class="label">Total Price</p>
                    <p class="value" id="display-total-price">0 THB</p>
                </div>

                <div class="detail-box">
                    <p class="label">Number of Seats</p>
                    <p class="value" id="seat-count">0</p>
                </div>

                <div class="detail-box">
                    <p class="label">Selected Seats</p>
                    <p class="value" id="seat-list">-</p>
                </div>

                <form action="payment.php" method="POST" id="booking-form">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <input type="hidden" name="selected_seats" id="input-seats">
                    <input type="hidden" name="total_price" id="input-price">
                    
                    <button type="submit" class="confirm-btn" id="btn-confirm" disabled>
                        Confirm Selection
                    </button>
                </form>

            </div>

        </div>

    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const seats = document.querySelectorAll(".seat");
    const seatCountEl = document.getElementById("seat-count");
    const seatListEl = document.getElementById("seat-list");
    const displayTotalEl = document.getElementById("display-total-price"); // Visual Total
    
    const inputSeats = document.getElementById("input-seats");
    const inputPrice = document.getElementById("input-price"); // Hidden Total
    const btnConfirm = document.getElementById("btn-confirm");

    let selectedSeats = [];
    const pricePerSeat = <?php echo $event['price']; ?>;

    seats.forEach(seat => {
        seat.addEventListener("click", () => {

            if (seat.classList.contains("blocked")) return;

            const code = seat.getAttribute("data-seat");

            seat.classList.toggle("selected");

            if (seat.classList.contains("selected")) {
                selectedSeats.push(code);
            } else {
                selectedSeats = selectedSeats.filter(s => s !== code);
            }

            updatePanel();
        });
    });

    function updatePanel() {
        // 1. Update Count
        seatCountEl.innerText = selectedSeats.length;
        
        // 2. Update List
        seatListEl.innerText = selectedSeats.length > 0 ? selectedSeats.join(", ") : "-";
        
        // 3. Calculate Total
        const total = selectedSeats.length * pricePerSeat;
        
        // 4. Update Visuals & Inputs
        if (displayTotalEl) displayTotalEl.innerText = total.toLocaleString() + " THB";
        if (inputPrice) inputPrice.value = total;
        if (inputSeats) inputSeats.value = selectedSeats.join(",");

        // 5. Toggle Button
        if (selectedSeats.length > 0) {
            btnConfirm.disabled = false;
            btnConfirm.style.opacity = "1";
            btnConfirm.style.cursor = "pointer";
        } else {
            btnConfirm.disabled = true;
            btnConfirm.style.opacity = "0.5";
            btnConfirm.style.cursor = "not-allowed";
        }
    }
});
</script>

</body>
</html>