<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/edit-event-admin.css">

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
            <li onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>

            <li class="active"><i class="fas fa-calendar-check icon"></i> Manage Events</li>

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
                <h2>Welcome Back, Sainam!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>

            <div class="right">
                <div class="search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search events...">
                </div>
                <img src="../assets/profile3.png" class="profile">
            </div>
        </div>

        <!-- BACK BUTTON -->
        <a href="manage-events.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- WHITE EDIT BOX -->
        <div class="edit-wrapper">

            <!-- TITLE -->
            <h1 class="title">Edit Event</h1>

            <!-- IMAGE (right side) -->
            <div class="image-box">
                <img src="../assets/event6.webp" id="event-img">
                <label class="change-btn" for="img-upload">
                    <i class="fas fa-rotate"></i>
                </label>
                <input type="file" id="img-upload" hidden>
            </div>

            <!-- GRID CONTENT -->
            <div class="edit-grid">

                <div class="label">EVENT NAME</div>
                <div class="value">
                    <input type="text" value="ALD1 FANCON IN BKK">
                    <i class="fas fa-pen icon-edit"></i>
                </div>

                <div class="label">DATE</div>
                <div class="value">
                    <input type="text" value="25/6/25">
                    <i class="fas fa-pen icon-edit"></i>
                </div>

                <div class="label">VENUE</div>
                <div class="value">
                    <input type="text" value="Thunder Dome, Muang Thong Thani">
                    <i class="fas fa-pen icon-edit"></i>
                </div>

                <div class="label">STATUS</div>
                <div class="value">
                    <select>
                        <option selected>Available</option>
                        <option>Sold Out</option>
                        <option>Closed</option>
                    </select>
                    <i class="fas fa-pen icon-edit"></i>
                </div>

                <div class="label">TIME</div>
                <div class="value">
                    <input type="text" value="04:00 PM">
                    <i class="fas fa-pen icon-edit"></i>
                </div>

                <div class="label">GATES OPEN</div>
                <div class="value">
                    <input type="text" value="03:00 PM">
                    <i class="fas fa-pen icon-edit"></i>
                </div>

                <div class="label">PRICE</div>
                <div class="value">
                    <input type="text" 
                    value="6,900 / 5,900 / 5,000 / 4,500 / 3,000 / 2,000 / 1,500">
                    <i class="fas fa-pen icon-edit"></i>
                </div>

            </div>

            <button class="save-btn">Save</button>

        </div>

    </main>

</div>

</body>
</html>
