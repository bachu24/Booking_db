<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>

    <!-- GLOBAL + PAGE CSS -->
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/event-details.css">

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="layout">

    <!-- =============== SIDEBAR =============== -->
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


    <!-- =============== CONTENT AREA =============== -->
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
        <button class="back-btn" onclick="history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>


        <!-- ================= EVENT HEADER ================= -->
        <section class="event-header">

            <!-- LEFT SIDE: POSTER -->
            <div class="event-img">
                <img src="../assets/lykn.jpg" alt="Event Poster">

                <button class="select-seat-btn" onclick="location.href='select-seat.php'">
                    Select Seat
                </button>
            </div>

            <!-- RIGHT SIDE: INFO -->
            <div class="event-info">

                <h1>LYKN DUSK & DAWN</h1>

                <div class="info-grid">

                    <div class="info-box">
                        <i class="fas fa-calendar-days"></i>
                        <div>
                            <h4>The show dates</h4>
                            <p>Saturday, October 18, 2025</p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-ticket"></i>
                        <div>
                            <h4>Tickets on sale</h4>
                            <p>Saturday, February 21, 2025<br>10:00 AM</p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-location-dot"></i>
                        <div>
                            <h4>Venue</h4>
                            <p>IMPACT Arena, Muang Thong Thani</p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>
                            <h4>Price</h4>
                            <p>6,900 / 5,900 / 5,000 / 4,500 / 3,000 / 2,000 / 1,500</p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-door-open"></i>
                        <div>
                            <h4>Gates open</h4>
                            <p>4:00 PM</p>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h4>Ticket Status</h4>
                            <p class="status-green">ON SALE NOW</p>
                        </div>
                    </div>

                </div>
            </div>

        </section>


        <!-- ================= DETAILS SECTION ================= -->
        <section class="details-section">

            <h2>Concert Details</h2>

            <p><strong>Performers:</strong></p>
            <ul>
                <li>Jakrapat Kaewphanphong (William)</li>
                <li>Rapeepong Supathineeekittidecha (Lego)</li>
                <li>Thanat Darnjetsada (Nut)</li>
                <li>Pichettpong Jiradechsakulwong (Hong)</li>
                <li>Chayathorn Trairattanapradit (Tui)</li>
            </ul>

            <p>Benefit Registration: 8:00 AM – 1:50 PM</p>
            <p><strong>Note:</strong> If you do not register during this time, your benefit rights will be forfeited, but you may still attend the show as usual.</p>
            <p>Entry for Sound Check & Group Photo Benefit Holders: 1:00 PM</p>

        </section>

    </main>
</div>

</body>
</html>
