<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>

    <!-- GLOBAL + PAGE CSS -->
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/payment.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="layout">

    <!-- ========== SIDEBAR ========== -->
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


    <!-- ========== CONTENT ========== -->
    <main class="content">

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

        <!-- BACK BUTTON -->
        <button onclick="history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </button>


        <!-- ========== PAYMENT HEADER (FIXED LAYOUT) ========== -->
        <div class="event-header">

            <!-- LEFT : Step -->
            <div class="event-step">
                <h3>Step 3<br>Payment</h3>
            </div>

            <!-- MIDDLE : Thumbnail -->
            <img src="../assets/lykn.jpg" class="event-thumb">

            <!-- RIGHT : Info -->
            <div class="event-header-right">
                <h2>LYKN DUSK & DAWN CONCERT</h2>
                <p>Showtime : Saturday, October 18, 2025 • 05:00 PM</p>
            </div>

        </div>



        <!-- ========== PAYMENT METHODS ========== -->
        <h2 class="section-title">Payment Methods</h2>

        <div class="payment-methods">

            <div class="pay-card" onclick="selectMethod('card')">
                <i class="fas fa-credit-card pay-icon"></i>
                <p>Credit/Debit Card</p>
            </div>

            <div class="pay-card" onclick="selectMethod('mobile')">
                <i class="fas fa-mobile-screen-button pay-icon"></i>
                <p>Mobile Banking</p>
            </div>

            <div class="pay-card" onclick="selectMethod('711')">
                <i class="fas fa-store pay-icon"></i>
                <p>Cash on 7-11 store</p>
            </div>

        </div>


        <!-- PAY BUTTON -->
        <div class="paynow-center">
            <button class="paynow-btn" onclick="location.href='booking-confirm.php'">
                Pay Now
            </button>
        </div>

    </main>

</div>

<script>
function selectMethod(method) {
    alert("Selected : " + method);
}
</script>

</body>
</html>
