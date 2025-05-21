<?php
require_once 'config.php';

$pageTitle = 'Contact Us';
$breadcrumb = 'Contact Us';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = validateInput($_POST['name']);
    $email = validateInput($_POST['email']);
    $subject = validateInput($_POST['subject']);
    $message = validateInput($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error'] = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            $_SESSION['success'] = "Message sent successfully! We'll get back to you soon.";
            header("Location: contact.php");
            exit;
        } catch(PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }
}

include 'header.php';
?>

<div class="container content">
    <div class="page-header">
        <h1>Contact Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>

    <section class="contact-section" data-aos="fade-up">
        <div class="row">
            <div class="col-md-6">
                <h2>Get in Touch</h2>
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <form action="contact.php" method="post">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Contact Information</h2>
                <ul class="contact-info">
                    <li><i class="fas fa-phone"></i> +91 123 456 7890</li>
                    <li><i class="fas fa-envelope"></i> support@citybusbooking.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Travel Lane, City Center, India</li>
                </ul>
                <h4 class="mt-4">Follow Us</h4>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>