<?php
// 1. Start Session & Security Check
session_start();

// Ensure user is logged in and is an Organizer
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Organizer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php"); 
    exit();
}

require '../db_connect.php';

$organizer_id = $_SESSION['user_id'];
$organizer_name = $_SESSION['username'] ?? 'Organizer';
$error = "";

// 2. Fetch Venues (For the dropdown list)
$venues = [];
$v_sql = "SELECT venue_id, name FROM Venue ORDER BY name ASC";
$v_result = $conn->query($v_sql);
if ($v_result->num_rows > 0) {
    while($v = $v_result->fetch_assoc()) {
        $venues[] = $v;
    }
}

// 3. Handle Form Submission (INSERT)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get Data
    $name = trim($_POST['name']);
    $datetime_local = $_POST['datetime_local'];
    $venue_id = intval($_POST['venue_id']);
    $total_seats = intval($_POST['total_seats']);
    $price = floatval($_POST['price']);
    $details = trim($_POST['details']); // Store details in a new column (not currently in DB)
    
    // Split datetime-local into separate date and time columns 
    $date = date('Y-m-d', strtotime($datetime_local));
    $time = date('H:i:s', strtotime($datetime_local)); 
    
    // Image Handling Setup
    $event_image = "default.jpg"; // Default image 

    // A. Validation
    if (empty($name) || empty($date) || empty($venue_id) || $total_seats <= 0 || $price < 0) {
        $error = "Please fill in all required event details (Name, Date/Time, Venue, Seats, Price).";
    } 
    // B. Image Upload Logic
    elseif (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = "event_" . time() . "." . $ext;
            $upload_path = "../assets/" . $new_filename;

            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $upload_path)) {
                $event_image = $new_filename;
            }
        } else {
            $error = "Invalid image file type.";
        }
    }
    
    // C. Database Insertion
    if (empty($error)) {
        // NOTE: The 'details' input needs a new column in your Event table (e.g., description). 
        // We will skip inserting it for now, but you can uncomment this if you ALTER the table:
        // $sql = "INSERT INTO Event (name, date, time, venue_id, organizer_id, total_seats, available_seats, price, event_image, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Use current schema columns [cite: 212-218]
        $sql = "INSERT INTO Event (name, date, time, venue_id, organizer_id, total_seats, available_seats, price, event_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        // Types: sss i i i i d s (name, date, time, venue_id, organizer_id, total_seats, available_seats, price, event_image)
        $stmt->bind_param("sssiisids", $name, $date, $time, $venue_id, $organizer_id, $total_seats, $total_seats, $price, $event_image);

        if ($stmt->execute()) {
            echo "<script>alert('Event created successfully!'); window.location.href='view-events.php';</script>";
            exit();
        } else {
            $error = "Error creating event: " . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/new-event.css?v=<?php echo time(); ?>">
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
            <li class="active" onclick="location.href='new-event.php'"><i class="fas fa-calendar-plus icon"></i> New Event</li>
            <li onclick="location.href='view-events.php'"><i class="fas fa-eye icon"></i> View Events</li>
            <li onclick="location.href='../home.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>


    <main class="content">

        <div class="top-bar">
            <div>
                <h2>Welcome Back, <?php echo htmlspecialchars($organizer_name); ?>!</h2>
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

        <button onclick="history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </button>


        <div class="event-box">

            <h1 class="title">Create Event</h1>
            
            <?php if($error): ?>
                <p style="color: red; text-align: center; margin-bottom: 10px; font-weight: 500;"><?php echo $error; ?></p>
            <?php endif; ?>

            <label class="upload-container" for="eventImage">
                <input type="file" id="eventImage" name="event_image" hidden onchange="previewImage(event)">
                <div class="upload-box">
                    <img id="imagePreview" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <i class="fas fa-plus-circle upload-icon" id="uploadIcon"></i>
                    <p id="uploadText">Upload Image</p>
                </div>
            </label>

            <form method="POST" action="" enctype="multipart/form-data">

                <label class="input-label">EVENT NAME</label>
                <input type="text" name="name" class="input-line" required>

                <label class="input-label">DATE/TIME</label>
                <input type="datetime-local" name="datetime_local" class="input-line" required>

                <label class="input-label">VENUE</label>
                <select name="venue_id" class="input-line select-line" required>
                    <option value="" disabled selected>Select Venue...</option>
                    <?php foreach($venues as $v): ?>
                        <option value="<?php echo $v['venue_id']; ?>"><?php echo htmlspecialchars($v['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="input-label">NUMBER OF SEATS</label>
                <input type="number" name="total_seats" class="input-line" required min="1">

                <label class="input-label">TICKET PRICE (THB)</label>
                <input type="number" name="price" step="0.01" class="input-line" required min="0">

                <label class="input-label">EVENT DETAILS ( GATES OPEN, RULES, ETC. )</label>
                <textarea name="details" class="input-line" rows="4"></textarea>

                <div class="btn-center">
                    <button type="submit" class="save-btn">Save</button>
                </div>

            </form>

        </div>

    </main>

</div>

<script>
    // JS for Image Preview
    function previewImage(event) {
        const reader = new FileReader();
        const preview = document.getElementById('imagePreview');
        const icon = document.getElementById('uploadIcon');
        const text = document.getElementById('uploadText');

        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
            icon.style.display = 'none';
            text.style.display = 'none';
        };
        
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

</body>
</html>