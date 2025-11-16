<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/manage-users.css">

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
            <li class="active"><i class="fas fa-users icon"></i> Manage Users</li>
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar-check icon"></i> Manage Events</li>
            <li onclick="location.href='manage-payments.php'"><i class="fas fa-credit-card icon"></i> Manage Payments</li>
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
                <h2>Welcome Back, Admin!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="right">
                <div class="search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search users...">
                </div>

                <img src="../assets/profile3.png" class="profile">
            </div>
        </div>

        <!-- BACK BUTTON -->
        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="event-layout">

            <!-- LEFT SIDE — USER DETAILS -->
            <div class="event-details" id="details-box">

                <h2 class="details-title">Users Details</h2>

                <div class="details-card">
                    <img id="user-image" src="../assets/user1.png" class="event-image">

                    <span class="status-badge green" id="user-status">Active</span>

                    <div class="info-list">
                        <p><i class="fas fa-user"></i> <b>Full name</b><br><span id="user-name">James Johnson</span></p>
                        <p><i class="fas fa-envelope"></i> <b>Email</b><br><span id="user-email">jamesj@gmail.com</span></p>
                        <p><i class="fas fa-lock"></i> <b>Password</b><br><span id="user-pass">james12345</span></p>
                        <p><i class="fas fa-id-card"></i> <b>Role</b><br><span id="user-role">Organizer</span></p>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-edit"><i class="fas fa-pen"></i> Edit</button>
                    <button class="btn-delete"><i class="fas fa-trash"></i> Delete</button>
                </div>

            </div>

            <!-- RIGHT SIDE — USER LIST -->
            <div class="event-list">

                <div class="list-header">
                    <h2>All Users</h2>

                    <div class="right-btns">
                        <button class="add-btn"><i class="fas fa-plus"></i> Add</button>
                        <button class="print-btn"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>

                <!-- LABELS -->
                <div class="list-labels">
                    <span>Name</span>
                    <span>Role</span>
                    <span>Status</span>
                </div>

                <!-- ITEMS -->
                <div id="event-items">

                    <div class="event-item active" onclick="selectUser(0, this)">
                        <span>James</span>
                        <span>Organizer</span>
                        <span><span class="dot green"></span>Active</span>
                    </div>

                    <div class="event-item" onclick="selectUser(1, this)">
                        <span>Jane</span>
                        <span>Customer</span>
                        <span><span class="dot green"></span>Active</span>
                    </div>

                    <div class="event-item" onclick="selectUser(2, this)">
                        <span>Darin</span>
                        <span>Customer</span>
                        <span><span class="dot green"></span>Active</span>
                    </div>

                    <div class="event-item" onclick="selectUser(3, this)">
                        <span>Xinlong</span>
                        <span>Customer</span>
                        <span><span class="dot red"></span>Inactive</span>
                    </div>

                </div>

                <div class="view-all">
                    <a href="#">Show All Users <i class="fas fa-chevron-down"></i></a>
                </div>

            </div>

        </div>

    </main>

</div>


<script>
/* STATIC USER DATA */
const userData = [
    {
        img: "../assets/user1.png",
        name: "James Johnson",
        email: "jamesj@gmail.com",
        pass: "james12345",
        role: "Organizer",
        status: "Active"
    },
    {
        img: "../assets/user2.png",
        name: "Jane Williams",
        email: "janew@hotmail.com",
        pass: "jane2048",
        role: "Customer",
        status: "Active"
    },
    {
        img: "../assets/user3.png",
        name: "Darin Smith",
        email: "darin@gmail.com",
        pass: "darin9090",
        role: "Customer",
        status: "Active"
    },
    {
        img: "../assets/user4.png",
        name: "Xinlong Wang",
        email: "xinlong@gmail.com",
        pass: "xlw2024",
        role: "Customer",
        status: "Inactive"
    }
];

/* SELECT USER */
function selectUser(index, element) {

    document.querySelectorAll(".event-item").forEach(i => i.classList.remove("active"));
    element.classList.add("active");

    document.getElementById("user-image").src = userData[index].img;
    document.getElementById("user-name").innerText = userData[index].name;
    document.getElementById("user-email").innerText = userData[index].email;
    document.getElementById("user-pass").innerText = userData[index].pass;
    document.getElementById("user-role").innerText = userData[index].role;

    let badge = document.getElementById("user-status");
    badge.innerText = userData[index].status;
    badge.className = "status-badge " + (userData[index].status === "Inactive" ? "red" : "green");
}
</script>

</body>
</html>
