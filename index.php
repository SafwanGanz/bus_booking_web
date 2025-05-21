<?php
require_once 'config.php';

// Get departure locations
try {
    $stmt = $conn->prepare("SELECT DISTINCT departure_location FROM bus_routes ORDER BY departure_location");
    $stmt->execute();
    $departure_locations = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $departure_locations = [];
}

// Get popular routes (top 3 by price for display)
try {
    $stmt = $conn->prepare("SELECT * FROM bus_routes ORDER BY price DESC LIMIT 3");
    $stmt->execute();
    $popular_routes = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $popular_routes = [];
}

// Get user's bookings if logged in
$userBookings = [];
if (isLoggedIn()) {
    try {
        $stmt = $conn->prepare("
            SELECT b.*, r.price 
            FROM bookings b
            LEFT JOIN bus_routes r ON b.departure_location = r.departure_location AND b.arrival_location = r.arrival_location
            WHERE b.user_id = ? ORDER BY b.journey_date DESC LIMIT 3
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $userBookings = $stmt->fetchAll();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
}

include 'header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>7-Day Tour<br>Amazing City Bus Journey</h1>
        <p>Book your bus tickets easily and explore cities with comfort and convenience.</p>
        <div class="search-form">
            <form id="booking-form" action="process_booking.php" method="post" class="d-flex flex-wrap gap-3 justify-content-center">
                <div class="form-group">
                    <label for="from">From</label>
                    <select id="from" name="from" required class="form-control">
                        <option value="">City or Station</option>
                        <?php foreach ($departure_locations as $location): ?>
                            <option value="<?php echo htmlspecialchars($location['departure_location']); ?>">
                                <?php echo htmlspecialchars($location['departure_location']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="to">To</label>
                    <select id="to" name="to" required class="form-control">
                        <option value="">City or Station</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Departing</label>
                    <input type="date" id="date" name="date" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="seats">Seats</label>
                    <select id="seats" name="seats" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</section>

<!-- Popular Routes Section -->
<section class="popular-routes-section">
    <div class="container">
        <h2>Popular Routes</h2>
        <p>Explore our most popular bus routes connecting major cities.</p>
        <div class="row">
            <?php foreach ($popular_routes as $route): ?>
                <div class="col-md-4">
                    <div class="route-card" data-aos="fade-up">
                        <img src="assets/img/bus-route-placeholder.jpg" alt="Route Image">
                        <div class="route-card-body">
                            <h4><?php echo htmlspecialchars($route['departure_location']); ?> to <?php echo htmlspecialchars($route['arrival_location']); ?></h4>
                            <p>Starting from ₹<?php echo number_format($route['price'], 2); ?></p>
                            <a href="index.php?from=<?php echo urlencode($route['departure_location']); ?>&to=<?php echo urlencode($route['arrival_location']); ?>" class="btn">See All</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Booking Section -->
<section id="booking-section" class="booking-section">
    <!-- Left Side: Form -->
    <div class="form-container" data-aos="fade-up">
        <h2>Book Your Bus</h2>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form id="booking-form-details" action="process_booking.php" method="post">
            <div class="form-group">
                <label for="from">From</label>
                <select id="from" name="from" required class="form-control">
                    <option value="">Select departure location</option>
                    <?php foreach ($departure_locations as $location): ?>
                        <option value="<?php echo htmlspecialchars($location['departure_location']); ?>">
                            <?php echo htmlspecialchars($location['departure_location']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
    
            <div class="form-group">
                <label for="to">To</label>
                <select id="to" name="to" required class="form-control">
                    <option value="">Select arrival location</option>
                </select>
            </div>
    
            <div class="form-group">
                <label for="date">Journey Date</label>
                <input type="date" id="date" name="date" required class="form-control">
            </div>
    
            <div class="form-group">
                <label for="seats">No. of Seats</label>
                <select id="seats" name="seats" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
    
            <div class="price-container">
                <p id="price-display">Price: <span id="total-price">Select route to see price</span></p>
            </div>
    
            <div class="actions">
                <?php if (isLoggedIn()): ?>
                    <button type="submit" class="btn btn-primary" id="book-now-btn">Book Now</button>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Login to Book</a>
                <?php endif; ?>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>

    <!-- Right Side: Booking Status -->
    <div class="status-container" data-aos="fade-up">
        <h3>Your Bookings</h3>
        <?php if (isLoggedIn()): ?>
            <div id="booking-list">
                <?php if (count($userBookings) > 0): ?>
                    <?php foreach ($userBookings as $booking): ?>
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
                        <a href="#booking-form" class="btn btn-primary">Book Now</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <p>Please login to view your bookings.</p>
                <a href="login.php" class="btn btn-primary">Login</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Happy Customers Section -->
<section class="happy-customers-section">
    <div class="container">
        <h2>Happy Customers</h2>
        <p>We take pride in serving our customers with the best travel experiences.</p>
        <div class="customer-stats">
            <div class="stat-item">
                <h4>Bus Routes</h4>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 94%" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="stat-item">
                <h4>Bookings</h4>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 87%" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="stat-item">
                <h4>Support</h4>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 91%" aria-valuenow="91" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why We Are The Best Section -->
<section class="why-best-section">
    <div class="container">
        <h2>Why We Are The Best</h2>
        <p>Discover why travelers choose City Bus Booking for their journeys.</p>
        <div class="why-best-grid">
            <div class="why-best-card" data-aos="fade-up">
                <i class="fas fa-ticket-alt"></i>
                <h4>Easy Booking</h4>
                <p>Book your bus tickets in just a few clicks with our user-friendly interface.</p>
            </div>
            <div class="why-best-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-map-marked-alt"></i>
                <h4>Multiple Routes</h4>
                <p>Choose from a wide range of routes connecting major cities.</p>
            </div>
            <div class="why-best-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-shield-alt"></i>
                <h4>Secure Payments</h4>
                <p>Your payment information is always secure with our encrypted system.</p>
            </div>
            <div class="why-best-card" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-headset"></i>
                <h4>24/7 Support</h4>
                <p>Our customer support team is available round the clock to assist you.</p>
            </div>
        </div>
    </div>
</section>

<!-- Vacation Packages Section -->
<section class="vacation-packages-section">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2>City Bus Packages</h2>
            <p>Explore our exclusive bus travel packages starting at just ₹55.99!</p>
            <p>From Calicut to Mysore</p>
        </div>
        <a href="routes.php" class="btn">Details</a>
    </div>
</section>

<!-- Popup for messages -->
<div id="popup" class="popup-message"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show popup if redirected
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('booking') === 'success') {
        showPopup('Booking Successful!', 'success');
    } else if (urlParams.get('booking') === 'cancelled') {
        showPopup('Booking Cancelled!', 'warning');
    }

    // Set minimum date for journey
    document.getElementById('date').min = new Date().toISOString().split('T')[0];
    
    // Calculate price when route is selected
    const fromSelect = document.getElementById('from');
    const toSelect = document.getElementById('to');
    const seatsSelect = document.getElementById('seats');
    const bookBtn = document.getElementById('book-now-btn');
    
    // Update destination options based on selected departure
    fromSelect.addEventListener('change', function() {
        const from = this.value;
        if (from) {
            fetch('get_destinations.php?from=' + encodeURIComponent(from))
                .then(response => response.json())
                .then(data => {
                    toSelect.innerHTML = '<option value="">Select arrival location</option>';
                    data.destinations.forEach(dest => {
                        toSelect.innerHTML += `<option value="${dest}">${dest}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error fetching destinations:', error);
                });
        } else {
            toSelect.innerHTML = '<option value="">Select arrival location</option>';
        }
        updatePrice();
    });
    
    function updatePrice() {
        const from = fromSelect.value;
        const to = toSelect.value;
        const seats = parseInt(seatsSelect.value);
        
        if (from && to) {
            fetch('get_price.php?from=' + encodeURIComponent(from) + '&to=' + encodeURIComponent(to))
                .then(response => response.json())
                .then(data => {
                    if (data.price) {
                        const totalPrice = (data.price * seats).toFixed(2);
                        document.getElementById('total-price').innerHTML = '₹' + totalPrice;
                    } else {
                        document.getElementById('total-price').innerHTML = 'Route not available';
                    }
                })
                .catch(error => {
                    console.error('Price fetch error:', error);
                    document.getElementById('total-price').innerHTML = 'Error fetching price';
                });
        } else {
            document.getElementById('total-price').innerHTML = 'Select route to see price';
        }
    }
    
    toSelect.addEventListener('change', updatePrice);
    seatsSelect.addEventListener('change', updatePrice);

    // Prevent double submission
    if (bookBtn) {
        bookBtn.addEventListener('click', function() {
            this.disabled = true;
            this.form.submit();
        });
    }
});
</script>

<?php include 'footer.php'; ?>