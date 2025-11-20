<?php
// 1. Start Session & Security
session_start();
// Ensure user is logged in
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Customer' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

// 2. Get Ticket ID
if (isset($_GET['id'])) {
    $ticket_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // 3. Update Status to 'Cancelled'
    // SECURITY: We add "AND user_id = ?" to ensure they can only cancel THEIR OWN ticket.
    $sql = "UPDATE Ticket SET status = 'Cancelled' WHERE ticket_id = ? AND user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $ticket_id, $user_id);
    
    if ($stmt->execute()) {
        // The Trigger 'restore_seat_on_cancel' runs automatically here!
        
        // Also update the Payment status to 'Refunded' (Optional but good practice)
        $pay_sql = "UPDATE Payment SET status = 'Refunded' WHERE ticket_id = ?";
        $pay_stmt = $conn->prepare($pay_sql);
        $pay_stmt->bind_param("i", $ticket_id);
        $pay_stmt->execute();
        
        echo "<script>alert('Ticket cancelled successfully. Seat restored.'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error cancelling ticket.'); window.location.href='profile.php';</script>";
    }
    
    $stmt->close();
} else {
    header("Location: profile.php");
}

$conn->close();
?>