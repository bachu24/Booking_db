<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard-customer.css">

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
            <li class="active" onclick="location.href='dashboard.php'">
                <i class="fa-solid fa-house icon"></i>
                <span>Home</span>
            </li>

            <li onclick="location.href='my-ticket.php'">
                <i class="fa-solid fa-ticket icon"></i>
                <span>My Ticket</span>
            </li>

            <li onclick="location.href='profile.php'">
                <i class="fa-solid fa-user icon"></i>
                <span>Profile</span>
            </li>

            <li onclick="location.href='settings.php'">
                <i class="fa-solid fa-gear icon"></i>
                <span>Settings</span>
            </li>

            <li onclick="location.href='../logout.php'">
                <i class="fa-solid fa-right-from-bracket icon"></i>
                <span>Logout</span>
            </li>
        </ul>

        <div class="help-box">
            <i class="fa-regular fa-circle-question help-icon"></i>
            Help & Support
        </div>
    </aside>


    <!-- ========== MAIN CONTENT ========== -->
    <main class="content">

        <!-- Header Welcome -->
        <div class="top-bar">
            <div>
                <h2>Welcome Back, Samira!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="right">
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" placeholder="keyword, category, location">
                </div>

                <button class="heart-btn">
                    <i class="fa-regular fa-heart"></i>
                </button>

                <img src="../assets/profile1.png" class="profile">
            </div>
        </div>


        <!-- Upcoming Events title -->
        <div class="section-title">
            <h2>Upcoming Events</h2>

            <a href="#" class="view-link">
                View All Events →
            </a>
        </div>


        <!-- EVENT CARDS GRID -->
        <div class="event-grid">

            <!-- Card 1 -->
            <div class="event-card">
                <div class="img-box">
                    <span class="date-badge">18<br>feb</span>
                    <img src="../assets/event1.webp">
                </div>
                <h3>RIIZING LOUD IN BKK</h3>
                <div class="detail-row"><i class="fa-solid fa-location-dot"></i> Impact Arena</div>
                <div class="detail-row"><i class="fa-solid fa-clock"></i> 04:00 pm to 07:00 pm</div>
                <button class="btn-details">See Details <i class="fa-regular fa-heart"></i></button>
            </div>

            <!-- Card 2 -->
            <div class="event-card">
                <div class="img-box">
                    <span class="date-badge">20<br>feb</span>
                    <img src="../assets/event2.webp">
                </div>
                <h3>THE DRAM SHOW4 IN BKK</h3>
                <div class="detail-row"><i class="fa-solid fa-location-dot"></i> Rajamangala Stadium</div>
                <div class="detail-row"><i class="fa-solid fa-clock"></i> 06:00 pm to 09:00 pm</div>
                <button class="btn-details">See Details <i class="fa-regular fa-heart"></i></button>
            </div>

            <!-- Card 3 -->
            <div class="event-card">
                <div class="img-box">
                    <span class="date-badge">21<br>feb</span>
                    <img src="../assets/lykn.jpg">
                </div>
                <h3>LYKN DUSK & DAWN</h3>
                <div class="detail-row"><i class="fa-solid fa-location-dot"></i> Impact Arena</div>
                <div class="detail-row"><i class="fa-solid fa-clock"></i> 05:00 pm to 08:00 pm</div>
                <button class="btn-details">See Details <i class="fa-regular fa-heart"></i></button>
            </div>

            <!-- Card 4 -->
            <div class="event-card">
                <div class="img-box">
                    <span class="date-badge">23<br>feb</span>
                    <img src="../assets/event4.jpg">
                </div>
                <h3>GMMTV STARLYMPICS</h3>
                <div class="detail-row"><i class="fa-solid fa-location-dot"></i> Impact Arena</div>
                <div class="detail-row"><i class="fa-solid fa-clock"></i> 01:00 pm to 04:00 pm</div>
                <button class="btn-details">See Details <i class="fa-regular fa-heart"></i></button>
            </div>

            <!-- Card 5 -->
            <div class="event-card">
                <div class="img-box">
                    <span class="date-badge">26<br>feb</span>
                    <img src="../assets/khemjira.jpg">
                </div>
                <h3>KHEMJIRA’S FINAL EP</h3>
                <div class="detail-row"><i class="fa-solid fa-location-dot"></i> Union Hall</div>
                <div class="detail-row"><i class="fa-solid fa-clock"></i> 02:00 pm to 05:00 pm</div>
                <button class="btn-details">See Details <i class="fa-regular fa-heart"></i></button>
            </div>

            <!-- Card 6 -->
            <div class="event-card">
                <div class="img-box">
                    <span class="date-badge">28<br>feb</span>
                    <img src="../assets/event6.webp">
                </div>
                <h3>ALDI FANCON IN BKK</h3>
                <div class="detail-row"><i class="fa-solid fa-location-dot"></i> Thunder Dome</div>
                <div class="detail-row"><i class="fa-solid fa-clock"></i> 04:00 pm to 07:00 pm</div>
                <button class="btn-details">See Details <i class="fa-regular fa-heart"></i></button>
            </div>

        </div>

    </main>
</div>

</body>
</html>
