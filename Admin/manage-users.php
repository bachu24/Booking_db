<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Handle User Deletion
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);
    
    // Prevent Admin from deleting themselves
    if ($id_to_delete != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM User WHERE user_id = ?");
        $stmt->bind_param("i", $id_to_delete);
        $stmt->execute();
        $stmt->close();
    }
    // Redirect to refresh the list
    header("Location: manage-users.php");
    exit();
}

// 3. Fetch All Users
$users = [];
$sql = "SELECT * FROM User ORDER BY user_id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        // --- IMAGE LOGIC ---
        // Check if user has an image; otherwise use default.jpg
        if (!empty($row['profile_image'])) {
            $row['img'] = "../assets/" . $row['profile_image'];
        } else {
            $row['img'] = "../assets/default.jpg"; 
        }

        // Default status fallback if column is empty
        if (empty($row['status'])) {
            $row['status'] = "Active";
        }
        
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/manage-users.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="layout">

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

    <main class="content">

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

        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="event-layout">

            <div class="event-details" id="details-box">
                <h2 class="details-title">Users Details</h2>

                <?php if (count($users) > 0): $first = $users[0]; ?>
                
                <div class="details-card">
                    <img id="user-image" src="<?php echo $first['img']; ?>" class="event-image" style="object-fit: cover;">
                    
                    <span class="status-badge green" id="user-status"><?php echo $first['status']; ?></span>

                    <div class="info-list">
                        <p><i class="fas fa-user"></i> <b>Full name</b><br><span id="user-name"><?php echo htmlspecialchars($first['name']); ?></span></p>
                        <p><i class="fas fa-envelope"></i> <b>Email</b><br><span id="user-email"><?php echo htmlspecialchars($first['email']); ?></span></p>
                        <p><i class="fas fa-lock"></i> <b>Password</b><br><span id="user-pass">********</span></p>
                        <p><i class="fas fa-id-card"></i> <b>Role</b><br><span id="user-role"><?php echo htmlspecialchars($first['role']); ?></span></p>
                    </div>
                </div>

                <div class="action-buttons">
                    <a class="btn-edit" id="btn-edit-link" href="edit-user.php?id=<?php echo $first['user_id']; ?>" >
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    
                    <a class="btn-delete" id="btn-delete-link" href="manage-users.php?delete_id=<?php echo $first['user_id']; ?>" onclick="return confirm('Are you sure?');">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>

                <?php else: ?>
                    <p>No users found.</p>
                <?php endif; ?>
            </div>

            <div class="event-list">
                <div class="list-header">
                    <h2>All Users</h2>
                    <div class="right-btns">
                        <button class="add-btn" onclick="location.href='add-user.php'"><i class="fas fa-plus"></i> Add</button>
                        <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>

                <div class="list-labels">
                    <span>Name</span>
                    <span>Role</span>
                    <span>Status</span>
                </div>

                <div id="event-items">
                    <?php foreach($users as $index => $u): ?>
                        <div class="event-item <?php echo ($index === 0) ? 'active' : ''; ?>" 
                             onclick="selectUser(<?php echo $index; ?>, this)">
                            
                            <span><?php echo htmlspecialchars($u['name']); ?></span>
                            <span><?php echo htmlspecialchars($u['role']); ?></span>
                            
                            <?php $color = ($u['status'] == 'Inactive') ? 'red' : 'green'; ?>
                            <span><span class="dot <?php echo $color; ?>"></span><?php echo $u['status']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="view-all">
                    <a href="#">Show All Users <i class="fas fa-chevron-down"></i></a>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
/* PASS PHP DATA TO JAVASCRIPT */
const userData = <?php echo json_encode($users); ?>;

function selectUser(index, element) {
    // 1. Handle UI Active Class
    document.querySelectorAll(".event-item").forEach(i => i.classList.remove("active"));
    element.classList.add("active");

    // 2. Get Data for clicked user
    const user = userData[index];

    // 3. Update Details View
    document.getElementById("user-image").src = user.img;
    document.getElementById("user-name").innerText = user.name;
    document.getElementById("user-email").innerText = user.email;
    document.getElementById("user-pass").innerText = "********"; 
    document.getElementById("user-role").innerText = user.role;

    let badge = document.getElementById("user-status");
    badge.innerText = user.status;
    // Update Badge Color
    badge.className = "status-badge " + (user.status === "Inactive" ? "red" : "green");

    // 4. Update Action Links
    document.getElementById("btn-edit-link").href = "edit-user.php?id=" + user.user_id;
    document.getElementById("btn-delete-link").href = "manage-users.php?delete_id=" + user.user_id;
}
</script>

</body>
</html>