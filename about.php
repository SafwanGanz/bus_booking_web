<?php
require_once 'config.php';

$pageTitle = 'About Us';
$breadcrumb = 'About Us';

include 'header.php';
?>

<div class="container content">
    <div class="page-header">
        <h1>About Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>

    <section class="about-section" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Explore Cities with City Bus Booking</h2>
                <p>We are a leading bus booking platform dedicated to providing seamless and hassle-free travel experiences. Our mission is to connect cities with reliable and affordable bus services, ensuring you reach your destination comfortably.</p>
                <p>With a focus on customer satisfaction, we offer a user-friendly platform to book bus tickets, check routes, and manage your bookings effortlessly. Whether you're traveling for work or leisure, City Bus Booking is here to make your journey smooth.</p>
                <a href="routes.php" class="btn btn-primary mt-3">Explore Routes</a>
            </div>
            <div class="col-md-6">
                <img src="assets/img/about-us.jpg" alt="About Us" class="img-fluid rounded" style="box-shadow: var(--box-shadow-light);">
            </div>
        </div>
    </section>

    <section class="why-best-section">
        <h2>Our Values</h2>
        <p>Discover what makes us the best choice for your bus travel needs.</p>
        <div class="why-best-grid">
            <div class="why-best-card" data-aos="fade-up">
                <i class="fas fa-bus"></i>
                <h4>Reliable Services</h4>
                <p>We partner with trusted bus operators to ensure safe and timely journeys.</p>
            </div>
            <div class="why-best-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-users"></i>
                <h4>Customer First</h4>
                <p>Your satisfaction is our priority, with 24/7 support for all your needs.</p>
            </div>
            <div class="why-best-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-money-bill-wave"></i>
                <h4>Affordable Pricing</h4>
                <p>Enjoy competitive prices with no hidden fees on all routes.</p>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>