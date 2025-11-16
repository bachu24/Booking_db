<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Seats</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/select-seat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="layout">

    <!-- ==================== SIDEBAR ==================== -->
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
            <li onclick="location.href='my-ticket.php'"><i class="fas fa-ticket icon"></i> My Ticket</li>
            <li onclick="location.href='profile.php'"><i class="fas fa-user icon"></i> Profile</li>
            <li onclick="location.href='settings.php'"><i class="fas fa-gear icon"></i> Settings</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
    </aside>


    <!-- ==================== CONTENT ==================== -->
    <main class="content">

        <!-- TOPBAR -->
        <div class="top-bar">
            <div>
                <h2>Welcome Back, Samira!</h2>
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

        <!-- BACK BUTTON -->
        <button onclick="history.back()" style="background:none;border:none;font-size:25px;cursor:pointer;margin-bottom:20px;">
            <i class="fas fa-arrow-left"></i>
        </button>


        <!-- ===================== SEAT HEADER BANNER ===================== -->
        <div class="seat-banner">

            <div class="seat-step">
                <h1>Step 2<br>Select Seats</h1>
            </div>

            <img src="../assets/lykn.jpg" class="seat-cover">

            <div class="seat-info">
                <h2>LYKN DUSK & DAWN CONCERT</h2>
                <p>Showtime : Saturday, October 18, 2025 • 05:00 PM</p>
            </div>

        </div>

        <!-- ===================== SEAT SELECTION ===================== -->
        <div class="seat-layout-box">

            <div class="seat-map">

                <div class="legend">
                    <span class="dot available"></span> 2,920  
                    <span class="dot selected"></span> Selected  
                    <span class="dot blocked"></span> Unavailable
                </div>

                <h3 class="stage-title">STAGE</h3>

                <div class="seat-grid">
                    <?php 
                    $rows = ["AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO"];

                    foreach ($rows as $row): ?>
                        <div class="seat-row">
                            <span class="row-label"><?= $row ?></span>

                            <?php for ($s=1; $s <= 20; $s++): 
                                $seatCode = $row . str_pad($s,2,"0",STR_PAD_LEFT);
                            ?>
                                <div class="seat" data-seat="<?= $seatCode ?>">
                                    <?= str_pad($s,2,"0",STR_PAD_LEFT) ?>
                                </div>
                            <?php endfor; ?>

                        </div>
                    <?php endforeach; ?>
                </div>

            </div>


            <!-- ===================== BOOKING SUMMARY ===================== -->
            <div class="booking-details">
                <h2>Booking Details</h2>

                <div class="detail-box">
                    <p class="label">Showtime</p>
                    <p class="value">Sat, October 18, 2025<br>05:00 PM</p>
                </div>

                <div class="detail-box">
                    <p class="label">Seating</p>
                    <p class="value">B1</p>
                </div>

                <div class="detail-box">
                    <p class="label">Status</p>
                    <p class="value">Available</p>
                </div>

                <div class="detail-box">
                    <p class="label">Ticket Price</p>
                    <p class="value">2,920 THB</p>
                </div>

                <div class="detail-box">
                    <p class="label">Number of Seats</p>
                    <p class="value" id="seat-count">0</p>
                </div>

                <div class="detail-box">
                    <p class="label">Seat Number</p>
                    <p class="value" id="seat-list">-</p>
                </div>

                <button class="confirm-btn" onclick="location.href='payment.php'">
                    Confirm Selection
                </button>

            </div>

        </div>

    </main>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const seats = document.querySelectorAll(".seat");
    const seatCount = document.getElementById("seat-count");
    const seatList = document.getElementById("seat-list");

    let selectedSeats = [];

    seats.forEach(seat => {
        seat.addEventListener("click", () => {

            // ถ้าเป็นที่นั่งที่ถูกบล็อก
            if (seat.classList.contains("blocked")) return;

            const code = seat.getAttribute("data-seat");

            // toggle class
            seat.classList.toggle("selected");

            if (seat.classList.contains("selected")) {
                selectedSeats.push(code);
            } else {
                selectedSeats = selectedSeats.filter(x => x !== code);
            }

            // อัปเดต panel
            seatCount.innerText = selectedSeats.length;
            seatList.innerText = selectedSeats.length > 0 ? selectedSeats.join(", ") : "-";
        });
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const seats = document.querySelectorAll(".seat");

    seats.forEach(seat => {
        seat.addEventListener("click", () => {

            // blocked seat → ห้ามเลือก
            if (seat.classList.contains("blocked-seat")) return;

            // toggle selected class
            seat.classList.toggle("selected-seat");

        });
    });
});
</script>
</body>
</html>
