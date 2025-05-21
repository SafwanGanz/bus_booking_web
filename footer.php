</main>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h4>Together with City Bus Booking</h4>
                <p>Your trusted platform for booking city bus tickets online with ease and convenience.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Travel Specialists</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="routes.php">Routes</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4>Newsletter</h4>
                <p>Inspiration, ideas, news, and your feedback.</p>
                <form class="newsletter-form" action="subscribe.php" method="post">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <button type="submit">Submit</button>
                </form>
                <p class="mt-3">+91 123 456 7890<br>support@citybusbooking.com</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> City Bus Booking. All rights reserved.</p>
            <div>
                <a href="terms.php">Privacy Policy</a>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact</a>
                <a href="contact.php">Support</a>
            </div>
            <p>Designed by City Bus Booking</p>
        </div>
    </div>
</footer>

<!-- Back to Top -->
<a href="#" class="back-to-top"><i class="fas fa-chevron-up"></i></a>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

    // Back to top button
    window.addEventListener('scroll', () => {
        const backToTop = document.querySelector('.back-to-top');
        if (window.scrollY > 100) {
            backToTop.classList.add('active');
        } else {
            backToTop.classList.remove('active');
        }
    });
</script>
</body>
</html>