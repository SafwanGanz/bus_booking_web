<?php
require_once 'config.php';

$pageTitle = 'Bus Routes';
$breadcrumb = 'Bus Routes';

// Fetch all bus routes
try {
    $stmt = $conn->prepare("SELECT * FROM bus_routes ORDER BY departure_location");
    $stmt->execute();
    $routes = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $routes = [];
}

include 'header.php';
?>

<div class="container content">
    <div class="page-header">
        <h1>Bus Routes</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bus Routes</li>
            </ol>
        </nav>
    </div>

    <section class="popular-routes-section">
        <h2>Explore All Routes</h2>
        <p>Find the perfect bus route for your next journey.</p>
        <div class="row">
            <?php if (count($routes) > 0): ?>
                <?php foreach ($routes as $route): ?>
                    <div class="col-md-4">
                        <div class="route-card" data-aos="fade-up">
                            <img src="assets/img/bus-route-placeholder.jpg" alt="Route Image">
                            <div class="route-card-body">
                                <h4><?php echo htmlspecialchars($route['departure_location']); ?> to <?php echo htmlspecialchars($route['arrival_location']); ?></h4>
                                <p>Price: ₹<?php echo number_format($route['price'], 2); ?></p>
                                <a href="index.php?from=<?php echo urlencode($route['departure_location']); ?>&to=<?php echo urlencode($route['arrival_location']); ?>" class="btn">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No routes available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>