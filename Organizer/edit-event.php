<?php
// 1. Start Session & Security Check
session_start();
// Ensure user is logged in AND is an Organizer (or Admin for testing)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Organizer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Get Event ID
if (!isset($_GET['id'])) {
    header("Location: view-events.php");
    exit();
}
$event_id = intval($_GET['id']);
$organizer_id = $_SESSION['user_id'];
$organizer_name = $_SESSION['username'] ?? 'Organizer';
$error = "";

// 3. Fetch Venues (For the dropdown list)
$venues = [];
$v_sql = "SELECT venue_id, name FROM Venue ORDER BY name ASC";
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
    $total_seats = intval($_POST['total_seats']);
    $available_seats = intval($_POST['available_seats']);
    $status = $_POST['status'];

    // A. Setup base query and parameters (8 core fields)
    $image_query_part = "";
    $params = [$name, $date, $time, $venue_id, $price, $status, $total_seats, $available_seats];
    $types = "sssidsii"; 

    // B. Check for Image Upload
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['event_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = "event_" . $event_id . "_" . time() . "." . $ext;
            $upload_path = "../assets/" . $new_filename;

            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $upload_path)) {
                $image_query_part = ", event_image = ?";
                $params[] = $new_filename; 
                $types .= "s";
            }
        }
    }
    
    // C. Add WHERE clause parameters
    $params[] = $organizer_id; 
    $params[] = $event_id; 
    $types .= "ii";

    // D. Update Database
    $sql = "UPDATE Event SET name=?, date=?, time=?, venue_id=?, price=?, status=?, total_seats=?, available_seats=?" 
            . $image_query_part 
            . " WHERE organizer_id=? AND event_id=?";
            
    $stmt = $conn->prepare($sql);
    
    if ($stmt->bind_param($types, ...$params)) {
        if ($stmt->execute()) {
            echo "<script>alert('Event Updated Successfully!'); window.location.href='view-events.php';</script>";
            exit();
        } else {
            $error = "Error executing update: " . $conn->error;
        }
    } else {
        $error = "Error binding parameters: Type string length mismatch.";
    }
    $stmt->close();
}

// 5. Fetch Existing Event Data (Security Check included in WHERE)
$stmt = $conn->prepare("SELECT * FROM Event WHERE event_id = ? AND organizer_id = ?");
$stmt->bind_param("ii", $event_id, $organizer_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) { 
    echo "Event not found or you do not have permission to edit it."; 
    exit(); 
}

// Image Fallback
$image_path = !empty($event['event_image']) ? "../assets/" . $event['event_image'] : "../assets/default.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/edit-event.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="layout">

    <aside class="sidebar">
        <div class="brand-box">
            <img src="../assets/logo-tickets.png" class="logo">
            <div class="brand-text"><h3>Evenity</h3><p>Booking Service</p></div>
        </div>
        <ul class="menu">
            <li onclick="location.href='dashboard.php'"><i class="fas fa-house icon"></i> Home</li>
            <li onclick="location.href='new-event.php'"><i class="fas fa-calendar-plus icon"></i> New Event</li>
            <li class="active" onclick="location.href='view-events.php'"><i class="fas fa-eye icon"></i> View Events</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>


    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, <?php echo htmlspecialchars($organizer_name); ?>!</h2>
                <p class="sub">Exclusive Events Await!</p>
            </div>
            <div class="right"><img src="../assets/profile2.png" class="profile"></div>
        </div>

        <a href="view-events.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="edit-wrapper">

            <h1 class="title">Edit Event</h1>

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
                    
                    <div class="label">TOTAL SEATS</div>
                    <div class="value">
                        <input type="number" name="total_seats" value="<?php echo $event['total_seats']; ?>" required>
                    </div>

                    <div class="label">AVAILABLE SEATS</div>
                    <div class="value">
                        <input type="number" name="available_seats" value="<?php echo $event['available_seats']; ?>" required>
                    </div>
                    
                    <div class="label">PRICE (THB)</div>
                    <div class="value">
                        <input type="number" step="0.01" name="price" value="<?php echo $event['price']; ?>" required>
                        <i class="fas fa-pen icon-edit"></i>
                    </div>

                    <div class="label">TIME / GATES OPEN</div>
                    <div class="value">
                        <input type="time" name="time" value="<?php echo $event['time']; ?>" required>
                        <i class="fas fa-pen icon-edit"></i>
                    </div>
                    
                    <div class="label">STATUS</div>
                    <div class="value">
                        <select name="status">
                            <option value="Available" <?php if(($event['status']??'') == 'Available') echo 'selected'; ?>>Available</option>
                            <option value="Sold Out" <?php if(($event['status']??'') == 'Sold Out') echo 'selected'; ?>>Sold Out</option>
                            <option value="Closed" <?php if(($event['status']??'') == 'Closed') echo 'selected'; ?>>Closed</option>
                        </select>
                        <i class="fas fa-pen icon-edit"></i>
                    </div>
                    
                    </div>

                <button type="submit" class="save-btn">Save</button>
            </form>
        </div>

    </main>

</div>

<script>
// JS for Image Preview
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