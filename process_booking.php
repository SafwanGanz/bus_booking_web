<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    error_log("Booking attempt without login");
    header("Location: login.php");
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = validateInput($_POST['from']);
    $to = validateInput($_POST['to']);
    $date = validateInput($_POST['date']);
    $seats = intval($_POST['seats']);
    
    // Validate input
    if (empty($from) || empty($to) || empty($date) || $seats <= 0) {
        error_log("Invalid input: from=$from, to=$to, date=$date, seats=$seats");
        $_SESSION['error'] = "All fields are required and seats must be greater than 0";
        header("Location: index.php");
        exit;
    }
    
    // Check if departure and arrival are the same
    if ($from === $to) {
        error_log("Same departure and arrival: $from");
        $_SESSION['error'] = "Departure and arrival locations cannot be the same";
        header("Location: index.php");
        exit;
    }
    
    // Check if date is in the future
    $today = date('Y-m-d');
    if ($date < $today) {
        error_log("Past date selected: $date");
        $_SESSION['error'] = "Please select a future date";
        header("Location: index.php");
        exit;
    }
    
    try {
        // Start a transaction
        $conn->beginTransaction();

        // Check if the route exists
        $stmt = $conn->prepare("SELECT route_id, price, COALESCE(max_seats, 40) as max_seats FROM bus_routes WHERE departure_location = ? AND arrival_location = ?");
        $stmt->execute([$from, $to]);
        $route = $stmt->fetch();
        
        if (!$route) {
            $conn->rollBack();
            error_log("Route not found: from=$from, to=$to");
            $_SESSION['error'] = "Selected route is not available";
            header("Location: index.php");
            exit;
        }

        // Check seat availability
        $stmt = $conn->prepare("SELECT COALESCE(SUM(seats), 0) as total_booked FROM bookings WHERE departure_location = ? AND arrival_location = ? AND journey_date = ? AND booking_status = 'Confirmed'");
        $stmt->execute([$from, $to, $date]);
        $result = $stmt->fetch();
        $total_booked = $result['total_booked'];

        if ($total_booked + $seats > $route['max_seats']) {
            $conn->rollBack();
            error_log("Seats unavailable: requested=$seats, booked=$total_booked, max={$route['max_seats']}");
            $_SESSION['error'] = "Not enough seats available for this route on the selected date";
            header("Location: index.php");
            exit;
        }

        // Calculate total price
        $total_price = $route['price'] * $seats;

        // Insert booking
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, departure_location, arrival_location, journey_date, seats, total_price, booking_status) VALUES (?, ?, ?, ?, ?, ?, 'Confirmed')");
        $stmt->execute([$_SESSION['user_id'], $from, $to, $date, $seats, $total_price]);

        // Commit transaction
        $conn->commit();
        
        error_log("Booking successful: user_id={$_SESSION['user_id']}, from=$from, to=$to, date=$date, seats=$seats, total_price=$total_price");
        header("Location: index.php?booking=success");
        exit;
    } catch(PDOException $e) {
        $conn->rollBack();
        error_log("Database error in booking: " . $e->getMessage());
        $_SESSION['error'] = "Database error: Unable to process booking. Please try again.";
        header("Location: index.php");
        exit;
    }
} else {
    error_log("Non-POST request to process_booking.php");
    header("Location: index.php");
    exit;
}
?>