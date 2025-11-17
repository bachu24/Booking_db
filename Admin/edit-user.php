<?php
// 1. Start Session & Security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Get User ID
if (!isset($_GET['id'])) {
    header("Location: manage-users.php");
    exit();
}
$user_id = intval($_GET['id']);
$message = "";
$error = "";

// 3. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_role = $_POST['role'];
    $new_status = $_POST['status'];

    // A. Handle Image Upload
    $image_query_part = ""; // default empty if no image uploaded
    $params = [$new_role, $new_status, $user_id];
    $types = "ssi";

    // Check if a file was uploaded without errors
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $filetype = $_FILES['profile_image']['type'];
        $filesize = $_FILES['profile_image']['size'];
        
        // Get extension
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Create unique name to prevent overwriting (e.g. user_5_12345.png)
            $new_filename = "user_" . $user_id . "_" . time() . "." . $ext;
            $upload_path = "../assets/" . $new_filename;

            // Move file from temp storage to our folder
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                // Update Query to include image
                $image_query_part = ", profile_image = ?";
                // Insert the image filename into the parameters array before the user_id
                array_splice($params, 2, 0, $new_filename); 
                $types = "sssi"; // Update types string
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid file type. Only JPG, PNG, and GIF allowed.";
        }
    }

    // B. Execute Database Update
    if (empty($error)) {
        $sql = "UPDATE User SET role = ?, status = ?" . $image_query_part . " WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "<script>alert('User Updated Successfully!'); window.location.href='manage-users.php';</script>";
            exit();
        } else {
            $error = "Database Error: " . $conn->error;
        }
        $stmt->close();
    }
}

// 4. Fetch Existing Data
$stmt = $conn->prepare("SELECT * FROM User WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) { echo "User not found."; exit(); }

// Determine Image Source
// If database has an image, use it. Otherwise use default.
$image_path = !empty($user['profile_image']) ? "../assets/" . $user['profile_image'] : "../assets/default.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/edit-user.css?v=<?php echo time(); ?>">
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
            <li class="active" onclick="location.href='manage-users.php'"><i class="fas fa-users icon"></i> Manage Users</li>
            <li onclick="location.href='../logout.php'"><i class="fas fa-right-from-bracket icon"></i> Logout</li>
        </ul>
    </aside>

    <main class="content">

        <div class="top-bar">
            <h2>Edit User</h2>
            <div class="right"><img src="../assets/profile3.png" class="profile"></div>
        </div>

        <a href="manage-users.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>

        <div class="edit-wrapper">

            <p class="warning-text">Admin can only edit user role or status*</p>
            <h1 class="title">Edit User</h1>
            
            <?php if($error): ?>
                <p style="color:red; text-align:center;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">

                <div class="image-box">
                    <img src="<?php echo $image_path; ?>" id="event-img" style="object-fit:cover;">
                    
                    <label class="change-btn" for="img-upload">
                        <i class="fas fa-rotate"></i>
                    </label>
                    
                    <input type="file" id="img-upload" name="profile_image" hidden onchange="previewImage(event)">
                </div>

                <div class="edit-grid">
                    <div class="label">FULL NAME</div>
                    <div class="value"><input type="text" value="<?php echo htmlspecialchars($user['name']); ?>" disabled></div>

                    <div class="label">EMAIL</div>
                    <div class="value"><input type="text" value="<?php echo htmlspecialchars($user['email']); ?>" disabled></div>

                    <div class="label">PASSWORD</div>
                    <div class="value"><input type="text" value="********" disabled></div>

                    <div class="label">ROLE</div>
                    <div class="value">
                        <select name="role">
                            <option value="Customer" <?php if($user['role']=='Customer') echo 'selected'; ?>>Customer</option>
                            <option value="Organizer" <?php if($user['role']=='Organizer') echo 'selected'; ?>>Organizer</option>
                            <option value="Admin" <?php if($user['role']=='Admin') echo 'selected'; ?>>Admin</option>
                        </select>
                        <i class="fa-solid fa-pen icon-edit"></i>
                    </div>

                    <div class="label">STATUS</div>
                    <div class="value">
                        <select name="status">
                            <option value="Active" <?php if(($user['status']??'Active')=='Active') echo 'selected'; ?>>Active</option>
                            <option value="Inactive" <?php if(($user['status']??'Active')=='Inactive') echo 'selected'; ?>>Inactive</option>
                        </select>
                        <i class="fa-solid fa-pen icon-edit"></i>
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