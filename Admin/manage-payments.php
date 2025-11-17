<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/manage-payments.css">

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
            <li onclick="location.href='manage-users.php'"><i class="fas fa-user icon"></i> Manage Users</li>
            <li onclick="location.href='manage-events.php'"><i class="fas fa-calendar icon"></i> Manage Events</li>
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

        <!-- CONTENT WRAPPER -->
        <div class="payment-layout">

            <!-- LEFT SIDE — PAYMENT DETAILS -->
            <div class="payment-details">

                <h2 class="pd-title">Payment Details</h2>

                <div class="pd-card">

                    <p><b>Payment ID</b><br><span id="pd-id">#PAY93658</span></p>
                    <p><b>Customer Name</b><br><span id="pd-name">Jane Doe</span></p>
                    <p><b>Event Name</b><br><span id="pd-event">ALDI FANCON IN BKK</span></p>
                    <p><b>Ticket Seats</b><br><span id="pd-seats">B1–A12, B1–A13</span></p>
                    <p><b>Amount (฿)</b><br><span id="pd-amount">12,000</span></p>
                    <p><b>Transaction Date</b><br><span id="pd-date">25 Jun 2025, 04:54 PM</span></p>
                    <p><b>Method</b><br><span id="pd-method">Mobile Banking</span></p>
                    <p><b>Status</b><br><span id="pd-status">Success</span></p>

                </div>

                <div class="pd-actions">
                    <button class="btn-edit"><i class="fas fa-pen"></i> Edit</button>
                    <button class="btn-delete"><i class="fas fa-trash"></i> Delete</button>
                </div>

            </div>


            <!-- RIGHT SIDE — PAYMENT LIST -->
            <div class="payment-list">

                <div class="list-header">
                    <h2>All Payments</h2>
                    <button class="print-btn"><i class="fas fa-print"></i> Print</button>
                </div>

                <div class="list-labels">
                    <span>Payment ID</span>
                    <span>Date</span>
                    <span>Time</span>
                    <span>Status</span>
                </div>

                <div id="payment-items">

                    <div class="pay-item" onclick="selectPay(0, this)">
                        <span>#PAY12345</span><span>18/2/25</span><span>04:45 PM</span>
                        <span><span class="dot green"></span>Success</span>
                    </div>

                    <div class="pay-item" onclick="selectPay(1, this)">
                        <span>#PAY87549</span><span>20/2/25</span><span>05:42 PM</span>
                        <span><span class="dot green"></span>Success</span>
                    </div>

                    <div class="pay-item" onclick="selectPay(2, this)">
                        <span>#PAY98634</span><span>12/2/25</span><span>06:12 PM</span>
                        <span><span class="dot blue"></span>Pending</span>
                    </div>

                    <div class="pay-item" onclick="selectPay(3, this)">
                        <span>#PAY23654</span><span>24/2/25</span><span>07:36 PM</span>
                        <span><span class="dot blue"></span>Pending</span>
                    </div>

                    <div class="pay-item" onclick="selectPay(4, this)">
                        <span>#PAY19645</span><span>23/2/25</span><span>08:17 PM</span>
                        <span><span class="dot red"></span>Failed</span>
                    </div>

                    <div class="pay-item active" onclick="selectPay(5, this)">
                        <span>#PAY93658</span><span>25/6/25</span><span>04:54 PM</span>
                        <span><span class="dot green"></span>Success</span>
                    </div>

                </div>

                <div class="view-all">
                    <a href="#">Show All Payments <i class="fas fa-chevron-down"></i></a>
                </div>

            </div>

        </div>

    </main>

</div>


<script>

// PAYMENT DATA
const payData = [
    {
        id: "#PAY12345",
        name: "Jane Doe",
        event: "RIIZING LOUD IN BKK",
        seats: "B1-A12, B1-A13",
        amount: "9,500",
        date: "18 Feb 2025, 04:45 PM",
        method: "Credit Card",
        status: "Success"
    },
    {
        id: "#PAY87549",
        name: "Somchai YY",
        event: "THE DRAM SHOW4 IN BKK",
        seats: "A2-B10",
        amount: "12,000",
        date: "20 Feb 2025, 05:42 PM",
        method: "Mobile Banking",
        status: "Success"
    },
    {
        id: "#PAY98634",
        name: "Mint BC",
        event: "LYKN DUSK & DAWN",
        seats: "C2-D05",
        amount: "7,200",
        date: "12 Feb 2025, 06:12 PM",
        method: "Credit Card",
        status: "Pending"
    },
    {
        id: "#PAY23654",
        name: "Jirayut",
        event: "GMMTV STARLYMPICS",
        seats: "B12-B13",
        amount: "6,000",
        date: "24 Feb 2025, 07:36 PM",
        method: "Mobile Banking",
        status: "Pending"
    },
    {
        id: "#PAY19645",
        name: "June",
        event: "KHEMJIRA'S FINAL EP",
        seats: "E3-F03",
        amount: "3,200",
        date: "23 Feb 2025, 08:17 PM",
        method: "Mobile Banking",
        status: "Failed"
    },
    {
        id: "#PAY93658",
        name: "Jane Doe",
        event: "ALDI FANCON IN BKK",
        seats: "B1-A12, B1-A13",
        amount: "12,000",
        date: "25 Jun 2025, 04:54 PM",
        method: "Mobile Banking",
        status: "Success"
    }
];


// SELECT PAYMENT FUNCTION
function selectPay(index, el) {
    document.querySelectorAll(".pay-item").forEach(i => i.classList.remove("active"));
    el.classList.add("active");

    const d = payData[index];

    document.getElementById("pd-id").innerText = d.id;
    document.getElementById("pd-name").innerText = d.name;
    document.getElementById("pd-event").innerText = d.event;
    document.getElementById("pd-seats").innerText = d.seats;
    document.getElementById("pd-amount").innerText = d.amount;
    document.getElementById("pd-date").innerText = d.date;
    document.getElementById("pd-method").innerText = d.method;

    let statusEl = document.getElementById("pd-status");
    statusEl.innerText = d.status;

    statusEl.style.color =
        d.status === "Success" ? "#1e7b1e" :
        d.status === "Pending" ? "#1e40af" :
        "#b80000";
}

</script>

</body>
</html>
