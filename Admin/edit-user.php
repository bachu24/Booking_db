<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Users</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/edit-user.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <a href="manage-users.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- MAIN WHITE BOX -->
        <div class="edit-wrapper">

            <p class="warning-text">Admin can only edit user role or status*</p>

    <!-- TITLE ด้านบนซ้าย -->
            <!-- TITLE -->
        <h1 class="title">Edit User</h1>

<!-- IMAGE CENTER -->
        <div class="image-box">
            <img src="../assets/user1.png" id="event-img">
            <label class="change-btn" for="img-upload">
                <i class="fas fa-rotate"></i>
        </label>
        <input type="file" id="img-upload" hidden>
    </div>




            <div class="edit-grid">

                <div class="label">FULL NAME</div>
                <div class="value">
                    <input type="text" value="James Johnson" disabled>
                </div>

                <div class="label">EMAILS</div>
                <div class="value">
                    <input type="text" value="James.j@gmail.com" disabled>
                </div>

                <div class="label">PASSWORD</div>
                <div class="value">
                    <input type="text" value="james12345" disabled>
                </div>

                <div class="label">ROLE</div>
                <div class="value">
                    <select>
                        <option selected>Organizer</option>
                        <option>Customer</option>
                        <option>Admin</option>
                    </select>
                    <i class="fa-solid fa-pen icon-edit"></i>
                </div>

                <div class="label">STATUS</div>
                <div class="value">
                    <select>
                        <option selected>Active</option>
                        <option>Inactive</option>
                    </select>
                    <i class="fa-solid fa-pen icon-edit"></i>
                </div>

            </div>

            <button class="save-btn">Save</button>

        </div>

    </main>

</div>

</body>
</html>
