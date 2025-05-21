<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Booking ID is required";
    header("Location: my_booking.php");
    exit;
}

$booking_id = intval($_GET['id']);

try {
    $stmt = $conn->prepare("SELECT booking_status FROM bookings WHERE booking_id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if (!$booking) {
        $_SESSION['error'] = "Booking not found or you don't have permission to cancel it";
        header("Location: my_booking.php");
        exit;
    }

    if ($booking['booking_status'] != 'Pending') {
        $_SESSION['error'] = "Only pending bookings can be cancelled";
        header("Location: my_booking.php");
        exit;
    }

    $stmt = $conn->prepare("UPDATE bookings SET booking_status = 'Cancelled' WHERE booking_id = ?");
    $stmt->execute([$booking_id]);

    header("Location: my_booking.php?booking=cancelled");
    exit;
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: my_booking.php");
    exit;
}
?>