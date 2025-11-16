<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Users</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/add-user.css">
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

            <!-- ONLY Manage Users should be active -->
            <li class="active" onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>

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
                <h2>Welcome Back, Sainam!</h2>
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
        <button onclick="history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </button>

        <!-- PAGE CONTENT -->
        <div class="event-box">

            <h1 class="title">Add Users</h1>

            <!-- IMAGE UPLOAD -->
            <label class="upload-container">
                <input type="file" id="userImage" hidden>
                <div class="upload-box">
                    <i class="fas fa-plus upload-icon"></i>
                </div>
            </label>

            <form>

                <label class="input-label">FULL NAME</label>
                <input type="text" class="input-line">

                <label class="input-label">EMAILS</label>
                <input type="email" class="input-line">

                <label class="input-label">PASSWORD</label>
                <input type="password" class="input-line">

                <label class="input-label">ROLE</label>
                <select class="input-line select-line">
                    <option disabled selected>Select role…</option>
                    <option value="Customer">Customer</option>
                    <option value="Organizer">Organizer</option>
                    <option value="Admin">Admin</option>
                </select>

                <div class="btn-center">
                    <button class="save-btn">Save</button>
                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>
