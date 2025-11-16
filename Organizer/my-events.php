<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Events</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/my-events.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
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
            <li onclick="location.href='new-event.php'"><i class="fas fa-calendar-plus icon"></i> New Event</li>
            <li class="active"><i class="fas fa-eye icon"></i> View Events</li>
            <li onclick="location.href='settings.php'"><i class="fas fa-gear icon"></i> Settings</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>

        <div class="help-box">
            <i class="fas fa-circle-question help-icon"></i> Help & Support
        </div>
    </aside>


    <!-- MAIN CONTENT -->
    <main class="content">

        <!-- TOP BAR -->
        <div class="top-bar">
            <div>
                <h2>Welcome Back, Somchai!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="right">
                <div class="search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Events">
                </div>

                <img src="../assets/profile2.png" class="profile">
            </div>
        </div>

        <!-- BACK BUTTON -->
        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="event-layout">

            <!-- LEFT SIDE — DETAILS -->
            <div class="event-details" id="details-box">

                <h2 class="details-title">Events Details</h2>

                <div class="details-card">
                    <img id="event-image" src="../assets/event1.webp" class="event-image">

                    <span class="status-badge green" id="event-status">Available</span>

                    <div class="info-list">
                        <p><i class="fas fa-location-dot"></i> <b>Venue</b><br><span id="event-venue">Thunder Dome</span></p>
                        <p><i class="fas fa-door-open"></i> <b>Gates Open</b><br><span id="event-gate">03:00 PM</span></p>
                        <p><i class="fas fa-ticket"></i> <b>Tickets on sale</b><br><span id="event-sale">Saturday, Feb 25, 2025, 10:00 AM</span></p>
                        <p><i class="fas fa-coins"></i> <b>Price</b><br><span id="event-price">6900 / 5900 / 5000</span></p>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-edit"><i class="fas fa-pen"></i> Edit</button>
                    <button class="btn-delete"><i class="fas fa-trash"></i> Delete</button>
                </div>

            </div>

            <!-- RIGHT SIDE — LIST -->
            <div class="event-list">

                <div class="list-header">
                    <h2>My Events</h2>
                    <button class="print-btn"><i class="fas fa-print"></i> Print</button>
                </div>

                <!-- LABELS -->
                <div class="list-labels">
                    <span>Events</span>
                    <span>Date</span>
                    <span>Time</span>
                    <span>Status</span>
                </div>

                <!-- ITEMS -->
                <div id="event-items">

                    <div class="event-item active" onclick="selectEvent(0, this)">
                        <span>RIIZING LOUD IN BKK</span><span>18/2/25</span><span>04:00 PM</span>
                        <span><span class="dot green"></span>Available</span>
                    </div>

                    <div class="event-item" onclick="selectEvent(1, this)">
                        <span>THE DRAM SHOW4 IN BKK</span><span>20/2/25</span><span>05:00 PM</span>
                        <span><span class="dot green"></span>Available</span>
                    </div>

                    <div class="event-item" onclick="selectEvent(2, this)">
                        <span>LYKN DUSK & DAWN</span><span>12/2/25</span><span>06:00 PM</span>
                        <span><span class="dot green"></span>Available</span>
                    </div>

                    <div class="event-item" onclick="selectEvent(3, this)">
                        <span>GMMTV STARLYMPICS</span><span>24/2/25</span><span>07:00 PM</span>
                        <span><span class="dot green"></span>Available</span>
                    </div>

                    <div class="event-item" onclick="selectEvent(4, this)">
                        <span>KHEMJIRA'S FINAL EP</span><span>23/2/25</span><span>08:00 PM</span>
                        <span><span class="dot red"></span>Sold Out</span>
                    </div>

                </div>

                <!-- VIEW ALL -->
                <div class="view-all">
                    <a href="#">Show All My Events <i class="fas fa-chevron-down"></i></a>
                </div>

            </div>

        </div>

    </main>

</div>


<script>
/* STATIC DATA */
const eventData = [
    {
        img: "../assets/event1.webp",
        venue: "Thunder Dome",
        gate: "03:00 PM",
        sale: "Saturday, Feb 25, 2025, 10:00 AM",
        price: "6900 / 5900 / 5000",
        status: "Available"
    },
    {
        img: "../assets/event2.webp",
        venue: "Impact Arena",
        gate: "02:00 PM",
        sale: "Saturday, Feb 20, 2025, 10:00 AM",
        price: "4500 / 3900 / 3500",
        status: "Available"
    },
    {
        img: "../assets/lykn.jpg",
        venue: "Impact Arena",
        gate: "04:00 PM",
        sale: "Saturday, Feb 12, 2025, 11:00 AM",
        price: "6900 / 5900 / 5000",
        status: "Available"
    },
    {
        img: "../assets/event4.jpg",
        venue: "Muang Thong",
        gate: "05:00 PM",
        sale: "Saturday, Feb 24, 2025, 10:00 AM",
        price: "3900 / 3200 / 2500",
        status: "Available"
    },
    {
        img: "../assets/khemjira.jpg",
        venue: "Central World",
        gate: "06:00 PM",
        sale: "Saturday, Feb 23, 2025, 09:30 AM",
        price: "5900 / 5200 / 4500",
        status: "Sold Out"
    }
];

/* SELECT EVENT */
function selectEvent(index, element) {

    document.querySelectorAll(".event-item").forEach(i => i.classList.remove("active"));
    element.classList.add("active");

    document.getElementById("event-image").src = eventData[index].img;
    document.getElementById("event-venue").innerText = eventData[index].venue;
    document.getElementById("event-gate").innerText = eventData[index].gate;
    document.getElementById("event-sale").innerText = eventData[index].sale;
    document.getElementById("event-price").innerText = eventData[index].price;

    let badge = document.getElementById("event-status");
    badge.innerText = eventData[index].status;
    badge.className = "status-badge " + (eventData[index].status === "Sold Out" ? "red" : "green");
}
</script>

</body>
</html>
