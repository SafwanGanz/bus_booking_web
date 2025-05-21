/**
 * City Bus Booking - Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 1000,
        once: true
    });

    // Initialize back to top button
    const backToTopButton = document.querySelector('.back-to-top');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Theme toggle functionality
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if (currentTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'light');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                localStorage.setItem('theme', 'dark');
            }
        });

        // Apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        themeToggle.innerHTML = savedTheme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    }

    // Form validation and price update for booking form
    const bookingForms = document.querySelectorAll('#booking-form, #booking-form-details');
    bookingForms.forEach(bookingForm => {
        if (bookingForm) {
            const fromSelect = bookingForm.querySelector('#from');
            const toSelect = bookingForm.querySelector('#to');
            const dateInput = bookingForm.querySelector('#date');
            const seatsSelect = bookingForm.querySelector('#seats');
            const bookBtn = bookingForm.querySelector('#book-now-btn');
            const totalPriceDisplay = bookingForm.querySelector('#total-price');

            // Update price dynamically
            function updatePrice() {
                const from = fromSelect.value;
                const to = toSelect.value;
                const seats = parseInt(seatsSelect.value);

                if (from && to && from !== to) {
                    fetch(`get_price.php?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.price) {
                                const totalPrice = (data.price * seats).toFixed(2);
                                if (totalPriceDisplay) {
                                    totalPriceDisplay.innerHTML = `₹${totalPrice}`;
                                }
                            } else {
                                if (totalPriceDisplay) {
                                    totalPriceDisplay.innerHTML = 'Route not available';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Price fetch error:', error);
                            if (totalPriceDisplay) {
                                totalPriceDisplay.innerHTML = 'Error fetching price';
                            }
                        });
                } else {
                    if (totalPriceDisplay) {
                        totalPriceDisplay.innerHTML = 'Select route to see price';
                    }
                }
            }

            // Add event listeners for price updates
            fromSelect.addEventListener('change', updatePrice);
            toSelect.addEventListener('change', updatePrice);
            seatsSelect.addEventListener('change', updatePrice);

            // Form submission validation
            bookingForm.addEventListener('submit', function(e) {
                const from = fromSelect.value;
                const to = toSelect.value;
                const date = dateInput.value;
                const seats = parseInt(seatsSelect.value);

                if (from === to) {
                    e.preventDefault();
                    showAlert('Departure and arrival locations cannot be the same', 'error');
                    return false;
                }

                const today = new Date().toISOString().split('T')[0];
                if (date < today) {
                    e.preventDefault();
                    showAlert('Please select a future date', 'error');
                    return false;
                }

                if (seats <= 0) {
                    e.preventDefault();
                    showAlert('Please select a valid number of seats', 'error');
                    return false;
                }
            });

            // Prevent double submission
            if (bookBtn) {
                bookBtn.addEventListener('click', function() {
                    if (bookingForm.checkValidity()) {
                        this.disabled = true;
                        bookingForm.submit();
                    }
                });
            }

            // Set minimum date
            dateInput.min = new Date().toISOString().split('T')[0];
        }
    });

    // Newsletter form validation
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            const emailInput = this.querySelector('input[name="email"]');
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email) {
                e.preventDefault();
                showAlert('Please enter an email address', 'error');
                return false;
            }

            if (!emailRegex.test(email)) {
                e.preventDefault();
                showAlert('Please enter a valid email address', 'error');
                return false;
            }
        });
    }

    // Mobile menu toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    }

    // Dropdown toggles for mobile
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    if (window.innerWidth < 992) {
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdownMenu = this.nextElementSibling;
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                } else {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                    dropdownMenu.classList.add('show');
                }
            });
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu.show');
        if (dropdowns.length && !e.target.classList.contains('dropdown-toggle')) {
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Password toggle visibility
    const passwordFields = document.querySelectorAll('input[type="password"]');
    passwordFields.forEach(field => {
        const container = field.parentElement;

        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'btn password-toggle';
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        toggleBtn.style.position = 'absolute';
        toggleBtn.style.right = '10px';
        toggleBtn.style.top = '50%';
        toggleBtn.style.transform = 'translateY(-50%)';
        toggleBtn.style.border = 'none';
        toggleBtn.style.background = 'transparent';
        toggleBtn.style.color = 'var(--text-color-light)';
        toggleBtn.style.cursor = 'pointer';

        // Set parent container to relative position
        if (getComputedStyle(container).position === 'static') {
            container.style.position = 'relative';
        }

        container.appendChild(toggleBtn);

        // Toggle password visibility
        toggleBtn.addEventListener('click', function() {
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    });

    // Show popup for booking status
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('booking') === 'success') {
        showPopup('Booking Successful!', 'success');
    } else if (urlParams.get('booking') === 'cancelled') {
        showPopup('Booking Cancelled!', 'warning');
    }
});

/**
 * Show alert message
 * @param {string} message - The message to display
 * @param {string} type - The type of alert (success, warning, error)
 */
function showAlert(message, type = 'success') {
    let alertContainer = document.getElementById('alert-container');
    
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '20px';
        alertContainer.style.right = '20px';
        alertContainer.style.zIndex = '9999';
        document.body.appendChild(alertContainer);
    }
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    alert.role = 'alert';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    alertContainer.appendChild(alert);
    
    setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => {
            alertContainer.removeChild(alert);
            if (!alertContainer.hasChildNodes()) {
                alertContainer.remove();
            }
        }, 300);
    }, 5000);
}

/**
 * Show popup message
 * @param {string} message - The message to display
 * @param {string} type - The type of popup (success, warning, error)
 */
function showPopup(message, type = 'success') {
    const popup = document.getElementById('popup');
    if (popup) {
        popup.textContent = message;
        popup.className = `popup-message ${type}`;
        popup.style.display = 'block';
        
        // Reset animation
        popup.style.animation = 'none';
        popup.offsetHeight;
        popup.style.animation = 'fadeInOut 3.5s ease-in-out forwards';
        
        setTimeout(() => {
            popup.style.display = 'none';
        }, 3500);
    }
}
