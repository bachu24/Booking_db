<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Payments</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/edit-payment.css">

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
            <li onclick="location.href='manage-users.php'"><i class="fas fa-user icon"></i> Manage Users</li>
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar-check icon"></i> Manage Events</li>
            <li class="active"><i class="fas fa-credit-card icon"></i> Manage Payments</li>
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
            <img src="../assets/profile3.png" class="profile">
        </div>

        <!-- BACK BUTTON -->
        <a href="manage-payments.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>


        <!-- MAIN WHITE BOX -->
        <div class="edit-wrapper">

            <!-- WARNING TEXT -->
            <p class="warning-text">
                For data integrity and privacy reasons,<br>
                only payment status and remarks can be updated*
            </p>

            <!-- TITLE -->
            <h1 class="title">Edit Payments</h1>

            <!-- GRID -->
            <div class="edit-grid">

                <div class="label">PAYMENT ID</div>
                <div class="value">
                    <input type="text" value="#PAY93658" disabled>
                </div>

                <div class="label">CUSTOMER NAME</div>
                <div class="value">
                    <input type="text" value="Jane Doe" disabled>
                </div>

                <div class="label">EVENT NAME</div>
                <div class="value">
                    <input type="text" value="ALDI FANCON IN BKK" disabled>
                </div>

                <div class="label">TICKET SEATS</div>
                <div class="value">
                    <input type="text" value="B1-A12, B1-A13" disabled>
                </div>

                <div class="label">AMOUNT (฿)</div>
                <div class="value">
                    <input type="text" value="12,000" disabled>
                </div>

                <div class="label">TRANSACTION DATE</div>
                <div class="value">
                    <input type="text" value="25 Jun 2025, 04.54 PM" disabled>
                </div>

                <div class="label">METHOD</div>
                <div class="value">
                    <input type="text" value="Mobile Banking" disabled>
                </div>

                <div class="label">PAYMENT STATUS</div>
                <div class="value">
                    <select>
                        <option selected>Success</option>
                        <option>Pending</option>
                        <option>Failed</option>
                    </select>
                    <i class="fa-solid fa-pen icon-edit"></i>
                </div>

                <div class="label">NOTES / REMARKS</div>
                <div class="value">
                    <input type="text" placeholder="Enter remarks...">
                    <i class="fa-solid fa-pen icon-edit"></i>
                </div>

            </div>

            <!-- SAVE BUTTON -->
            <button class="save-btn">Save</button>

        </div>

    </main>
</div>

</body>
</html>
