<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizer Dashboard</title>

    <!-- GLOBAL -->
    <link rel="stylesheet" href="../css/global.css">

    <!-- ORGANIZER DASHBOARD CSS -->
    <link rel="stylesheet" href="../css/dashboard-organizer.css">

    <!-- ICON & FONT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            <li class="active"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='new-event.php'"><i class="fas fa-calendar-plus icon"></i> New Event</li>
            <li onclick="location.href='view-events.php'"><i class="fas fa-eye icon"></i> View Events</li>
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
        <div class="top-bar-organizer">
            <div>
                <h2>Welcome Back, Somchai!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="organizer-right">
                <div class="organizer-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Events">
                </div>

                <img src="../assets/profile2.png" class="organizer-profile">
            </div>
        </div>


        <!-- ========== EVENTS SECTION ========== -->
        <div class="organizer-section">

            <h3>My Events</h3>
            <p class="small-sub">Total tickets sold of all events</p>

            <div class="view-all-events">View All Events</div>

            <div class="events-row">

                <!-- CARD 1 -->
                <div class="event-card">
                    <img src="../assets/lykn.jpg" class="event-thumb">

                    <div class="event-info">
                        <p class="event-title">LYKN DUSK & DAWN</p>

                        <div class="event-sub">
                            <i class="fas fa-location-dot"></i> Impact Arena
                        </div>

                        <div class="event-time">
                            <i class="fas fa-clock"></i> 05:00 pm to 08:00 pm
                        </div>
                    </div>

                    <div class="event-stats">
                        <div class="stats-number">7865</div>
                        <div class="stats-label">Number of Tickets Sold</div>

                        <div class="event-stats-row">
                            <div class="stats-number small">345</div>
                            <div class="stats-label">Number of Tickets Remaining</div>
                            <span class="stats-arrow"><i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>


                <!-- CARD 2 -->
                <div class="event-card">
                    <img src="../assets/event1.webp" class="event-thumb">

                    <div class="event-info">
                        <p class="event-title">RIIZING LOUD IN BKK</p>

                        <div class="event-sub">
                            <i class="fas fa-location-dot"></i> Impact Arena
                        </div>

                        <div class="event-time">
                            <i class="fas fa-clock"></i> 04:00 pm to 07:00 pm
                        </div>
                    </div>

                    <div class="event-stats">
                        <div class="stats-number">12345</div>
                        <div class="stats-label">Number of Tickets Sold</div>

                        <div class="event-stats-row">
                            <div class="stats-number small">345</div>
                            <div class="stats-label">Number of Tickets Remaining</div>
                            <span class="stats-arrow"><i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>


                <!-- CARD 3 -->
                <div class="event-card">
                    <img src="../assets/event6.webp" class="event-thumb">

                    <div class="event-info">
                        <p class="event-title">ALDI FANCON IN BKK</p>

                        <div class="event-sub">
                            <i class="fas fa-location-dot"></i> Thunder Dome
                        </div>

                        <div class="event-time">
                            <i class="fas fa-clock"></i> 06:00 pm to 08:00 pm
                        </div>
                    </div>

                    <div class="event-stats">
                        <div class="stats-number">4567</div>
                        <div class="stats-label">Number of Tickets Sold</div>

                        <div class="event-stats-row">
                            <div class="stats-number small">127</div>
                            <div class="stats-label">Number of Tickets Remaining</div>
                            <span class="stats-arrow"><i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>

            </div><!-- events-row -->

        </div><!-- organizer-section -->

    </main>

</div>

</body>
</html>
