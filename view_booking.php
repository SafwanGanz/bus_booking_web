<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'Booking Details';
$breadcrumb = 'Booking Details';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Booking ID is required";
    header("Location: my_booking.php");
    exit;
}

$booking_id = intval($_GET['id']);

try {
    $stmt = $conn->prepare("
        SELECT b.*, r.price 
        FROM bookings b
        LEFT JOIN bus_routes r ON b.departure_location = r.departure_location AND b.arrival_location = r.arrival_location
        WHERE b.booking_id = ? AND b.user_id = ?
    ");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if (!$booking) {
        $_SESSION['error'] = "Booking not found or you don't have permission to view it";
        header("Location: my_booking.php");
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: my_booking.php");
    exit;
}

include 'header.php';
?>

<div class="container content">
    <div class="page-header">
        <h1>Booking Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="my_booking.php">My Bookings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Booking Details</li>
            </ol>
        </nav>
    </div>

    <section class="booking-details-container" data-aos="fade-up">
        <div class="booking-card">
            <div class="booking-header">
                <h2>Booking #<?php echo $booking['booking_id']; ?></h2>
                <span class="status-badge status-<?php echo strtolower($booking['booking_status']); ?>">
                    <?php echo $booking['booking_status']; ?>
                </span>
            </div>
            <div class="journey-info">
                <div class="journey-route">
                    <div class="location">
                        <span class="label">From</span>
                        <h4><?php echo htmlspecialchars($booking['departure_location']); ?></h4>
                    </div>
                    <div class="location">
                        <span class="label">To</span>
                        <h4><?php echo htmlspecialchars($booking['arrival_location']); ?></h4>
                    </div>
                </div>
                <p><strong>Journey Date:</strong> <?php echo date('F j, Y', strtotime($booking['journey_date'])); ?></p>
                <p><strong>Seats:</strong> <?php echo $booking['seats']; ?></p>
                <p><strong>Total Price:</strong> ₹<?php echo number_format($booking['total_price'], 2); ?></p>
                <p><strong>Booked On:</strong> <?php echo date('F j, Y, g:i a', strtotime($booking['booking_date'])); ?></p>
            </div>
            <div class="actions">
                <?php if ($booking['booking_status'] == 'Pending'): ?>
                    <a href="cancel_booking.php?id=<?php echo $booking['booking_id']; ?>" class="btn btn-danger">Cancel Booking</a>
                <?php endif; ?>
                <a href="my_booking.php" class="btn btn-secondary">Back to Bookings</a>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>