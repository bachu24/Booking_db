<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Get Event ID
if (!isset($_GET['id'])) {
    header("Location: manage-events.php");
    exit();
}
$event_id = intval($_GET['id']);
$message = "";
$error = "";

// 3. Fetch Venues
$venues = [];
$v_sql = "SELECT venue_id, name FROM Venue";
$v_result = $conn->query($v_sql);
if ($v_result->num_rows > 0) {
    while($v = $v_result->fetch_assoc()) {
        $venues[] = $v;
    }
}

// 4. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue_id = $_POST['venue_id'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    // NEW: Capture Seat Data
    $total_seats = intval($_POST['total_seats']);
    $available_seats = intval($_POST['available_seats']);

    // A. Image Upload Logic
    $image_query_part = "";
    $params = [$name, $date, $time, $venue_id, $price, $status, $total_seats, $available_seats, $event_id];
    $types = "sssidsiii"; // Updated types string

    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['event_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = "event_" . $event_id . "_" . time() . "." . $ext;
            $upload_path = "../assets/" . $new_filename;

            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $upload_path)) {
                $image_query_part = ", event_image = ?";
                // Insert filename into params before ID
                array_splice($params, 8, 0, $new_filename); 
                $types = "sssidssiii"; 
            }
        }
    }

    // B. Update Database (Included Seats)
    $sql = "UPDATE Event SET name=?, date=?, time=?, venue_id=?, price=?, status=?, total_seats=?, available_seats=?" . $image_query_part . " WHERE event_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "<script>alert('Event Updated Successfully!'); window.location.href='manage-events.php';</script>";
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
    $stmt->close();
}

// 5. Fetch Existing Event Data
$stmt = $conn->prepare("SELECT * FROM Event WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) { echo "Event not found."; exit(); }

$image_path = !empty($event['event_image']) ? "../assets/" . $event['event_image'] : "../assets/default_event.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/edit-event-admin.css?v=<?php echo time(); ?>">
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
            <li onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>
            <li class="active" onclick="location.href='manage-events.php'"><i class="fas fa-calendar-check icon"></i> Manage Events</li>
            <li onclick="location.href='manage-payments.php'"><i class="fas fa-credit-card icon"></i> Manage Payments</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>

    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, Admin!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>
            <div class="right"><img src="../assets/profile3.png" class="profile"></div>
        </div>

        <a href="manage-events.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>

        <div class="edit-wrapper">
            <h1 class="title">Edit Event</h1>
            
            <?php if($error): ?>
                <p style="color:red; text-align:center; margin-bottom:10px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">

                <div class="image-box">
                    <img src="<?php echo $image_path; ?>" id="event-img" style="object-fit:cover;">
                    <label class="change-btn" for="img-upload">
                        <i class="fas fa-rotate"></i>
                    </label>
                    <input type="file" id="img-upload" name="event_image" hidden onchange="previewImage(event)">
                </div>

                <div class="edit-grid">

                    <div class="label">EVENT NAME</div>
                    <div class="value">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                        <i class="fas fa-pen icon-edit"></i>
                    </div>

                    <div class="label">DATE</div>
                    <div class="value">
                        <input type="date" name="date" value="<?php echo $event['date']; ?>" required>
                    </div>

                    <div class="label">VENUE</div>
                    <div class="value">
                        <select name="venue_id" required>
                            <?php foreach($venues as $v): ?>
                                <option value="<?php echo $v['venue_id']; ?>" 
                                    <?php if($event['venue_id'] == $v['venue_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($v['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="label">STATUS</div>
                    <div class="value">
                        <select name="status">
                            <option value="Available" <?php if(($event['status']??'') == 'Available') echo 'selected'; ?>>Available</option>
                            <option value="Sold Out" <?php if(($event['status']??'') == 'Sold Out') echo 'selected'; ?>>Sold Out</option>
                            <option value="Closed" <?php if(($event['status']??'') == 'Closed') echo 'selected'; ?>>Closed</option>
                        </select>
                    </div>

                    <div class="label">TOTAL SEATS</div>
                    <div class="value">
                        <input type="number" name="total_seats" value="<?php echo $event['total_seats']; ?>" required>
                    </div>

                    <div class="label">AVAILABLE SEATS</div>
                    <div class="value">
                        <input type="number" name="available_seats" value="<?php echo $event['available_seats']; ?>" required>
                    </div>
                    <div class="label">TIME / GATES OPEN</div>
                    <div class="value">
                        <input type="time" name="time" value="<?php echo $event['time']; ?>" required>
                    </div>

                    <div class="label">PRICE (THB)</div>
                    <div class="value">
                        <input type="number" step="0.01" name="price" value="<?php echo $event['price']; ?>" required>
                        <i class="fas fa-pen icon-edit"></i>
                    </div>

                </div>

                <button type="submit" class="save-btn">Save Changes</button>

            </form>

        </div>

    </main>

</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('event-img');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>