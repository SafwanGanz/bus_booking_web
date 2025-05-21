<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'My Bookings';
$breadcrumb = 'My Bookings';

// Fetch user's bookings
try {
    $stmt = $conn->prepare("
        SELECT b.*, r.price 
        FROM bookings b
        LEFT JOIN bus_routes r ON b.departure_location = r.departure_location AND b.arrival_location = r.arrival_location
        WHERE b.user_id = ? ORDER BY b.journey_date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $bookings = [];
}

include 'header.php';
?>

<div class="container content">
    <div class="page-header">
        <h1>My Bookings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Bookings</li>
            </ol>
        </nav>
    </div>

    <section class="booking-section">
        <div class="status-container" data-aos="fade-up">
            <h3>Your Bookings</h3>
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-item">
                        <div class="booking-details">
                            <p><strong>From:</strong> <?php echo htmlspecialchars($booking['departure_location']); ?></p>
                            <p><strong>To:</strong> <?php echo htmlspecialchars($booking['arrival_location']); ?></p>
                            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($booking['journey_date'])); ?></p>
                            <p><strong>Seats:</strong> <?php echo $booking['seats']; ?></p>
                            <p><strong>Status:</strong> <span class="status-<?php echo strtolower($booking['booking_status']); ?>"><?php echo $booking['booking_status']; ?></span></p>
                        </div>
                        <div class="booking-actions">
                            <a href="view_booking.php?id=<?php echo $booking['booking_id']; ?>" class="btn btn-info btn-sm">Details</a>
                            <?php if ($booking['booking_status'] == 'Pending'): ?>
                                <a href="cancel_booking.php?id=<?php echo $booking['booking_id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-bookings">
                    <p>You haven't made any bookings yet.</p>
                    <a href="index.php#booking-section" class="btn btn-primary">Book Now</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>