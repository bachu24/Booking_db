<?php
// ตัวอย่างข้อมูล (จริง ๆ จะดึงจาก DB หรือ session)
$customer_name = "Samira Hadid";
$order_number = "12278182010069764";
$order_date = "Saturday, February 21, 2025, 10:30 AM";
$concert_name = "LYKN DUSK & DAWN CONCERT";
$venue = "IMPACT Arena, Muang Thong Thani";
$show_date = "Saturday, October 18, 2025";
$seat_number = "B1–AE13";
$phone = "0954532145";
$price = "2,920 THB";
$service_fee = "20 THB";
$insurance_fee = "0 THB";
$delivery_fee = "0 THB";
$total_amount = "2,920 THB";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>

    <!-- GLOBAL CSS -->
    <link rel="stylesheet" href="../css/global.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- PAGE CSS -->
    <link rel="stylesheet" href="../css/booking-confirm.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="layout">

    <!-- ==================== SIDEBAR ==================== -->
    <div class="sidebar">
        <div class="brand-box">
            <img src="../assets/logo-tickets.png" class="logo">
            <div class="brand-text">
                <h3>Evenity</h3>
                <p>Booking Service</p>
            </div>
        </div>

        <ul class="menu">
            <li onclick="location.href='dashboard.php'">
                <i class="fas fa-house icon"></i> Home
            </li>
            <li class="active" onclick="location.href='my-ticket.php'">
                <i class="fas fa-ticket icon"></i> My Ticket
            </li>
            <li onclick="location.href='profile.php'"><i class="fas fa-user icon"></i> Profile</li>
            <li onclick="location.href='settings.php'"><i class="fas fa-gear icon"></i> Settings</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
    </div>


    <!-- ==================== CONTENT ==================== -->
    <div class="content">

        <!-- TOP BAR -->
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

        <!-- BACK -->
        <div style="margin-bottom:20px;">
            <button onclick="history.back()" 
                style="background:none;border:none;font-size:24px;cursor:pointer;">
                <i class="fas fa-arrow-left"></i>
            </button>
        </div>


       <!-- ========== BOOKING CONFIRM HEADER ========== -->
        <div class="event-header">

        <!-- LEFT — STEP -->
        <div class="event-step">
            <h3>Step 4<br>Booking Confirmation</h3>
        </div>

        <!-- MIDDLE — POSTER -->
        <img src="../assets/event3.jpg" class="event-thumb">

        <!-- RIGHT — EVENT DETAILS -->
        <div class="event-header-right">
            <h2>LYKN DUSK & DAWN CONCERT</h2>
            <p>Showtime : Saturday, October 18, 2025 • 05:00 PM</p>
        </div>

    </div>


        <!-- ==================== CONFIRM DETAILS ==================== -->
        <div class="confirm-container">

            <h2 class="section-title">Booking Confirmation</h2>

            <div class="confirm-grid">

                <!-- LEFT COLUMN -->
                <div class="left-col">
                    <p><b>Name :</b> <?= $customer_name ?></p>
                    <p><b>Order Number :</b> <?= $order_number ?></p>
                    <p><b>Order Date :</b> <?= $order_date ?></p>
                    <p><b>Item Purchased :</b> <?= $concert_name ?></p>
                    <p><b>Venue :</b> <?= $venue ?></p>
                    <p><b>Show Date :</b> <?= $show_date ?></p>
                    <p><b>Tickets seat :</b> <?= $seat_number ?></p>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="right-col">
                    <p><b>Phone Number :</b> <?= $phone ?></p>
                    <p><b>Price :</b> <?= $price ?></p>
                    <p><b>Number of Tickets :</b> 1</p>
                    <p><b>Service Fee :</b> <?= $service_fee ?></p>
                    <p><b>Ticket Protect Insurance (7%) :</b> <?= $insurance_fee ?></p>
                    <p><b>Delivery Fee :</b> <?= $delivery_fee ?></p>
                    <p><b>Total Amount :</b> <?= $total_amount ?></p>
                </div>

            </div>

        </div>

    </div><!-- content -->

</div><!-- layout -->

</body>
</html>
