# City Bus Booking System

## Overview
The City Bus Booking System is a web-based application designed to allow users to book bus tickets, explore routes, manage bookings, and contact support. The project has been redesigned to match the aesthetic and functionality of a modern Travel Agency website, featuring a clean UI, responsive design, and secure backend. It includes user authentication, booking management, a contact form, newsletter subscription, and more.

This `README.md` provides a detailed guide to understanding the project, setting it up locally using XAMPP, and running it effectively.

---

## Features
- **User Authentication**: Register, login, update profile, and logout functionalities.
- **Bus Booking**: Search for bus routes, book tickets, view and cancel bookings.
- **Route Exploration**: View all available bus routes with pricing.
- **Contact Form**: Submit inquiries or feedback via a contact form.
- **Newsletter Subscription**: Subscribe to receive updates via email.
- **Responsive Design**: Fully responsive layout compatible with mobile, tablet, and desktop devices.
- **Modern UI**: Styled to match a professional Travel Agency website with animations (via AOS library).
- **Secure Backend**: Input validation, password hashing, and SQL injection prevention using PDO.

---

## Project Structure
Below is the directory structure of the project:

```
city-bus-booking/
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet for the website
│   ├── img/
│   │   ├── bus-hero.jpg       # Hero section background (1200x600)
│   │   ├── bus-route-placeholder.jpg  # Route card image (400x200)
│   │   ├── about-us.jpg       # About Us page image (600x400)
│   │   ├── page-header-bg.jpg # Page header background (1200x300)
│   │   └── world-map-bg.png   # Happy Customers section background (1200x600)
│   ├── js/
│   │   └── script.js          # Custom JavaScript for dynamic functionality
│   └── vendor/                # Third-party libraries (Bootstrap, Font Awesome, AOS)
│       ├── bootstrap/
│       ├── fontawesome/
│       └── aos/
├── about.php                  # About Us page
├── cancel_booking.php         # Script to cancel a booking
├── config.php                 # Database connection and utility functions
├── contact.php                # Contact Us page with form
├── db.sql                     # Database schema and sample data
├── footer.php                 # Common footer for all pages
├── get_destinations.php       # API to fetch arrival locations based on departure
├── get_price.php              # API to fetch price for a selected route
├── header.php                 # Common header for all pages
├── index.php                  # Homepage with booking form and sections
├── login.php                  # User login page
├── logout.php                 # Script to log out users
├── my_booking.php             # Page to view user's bookings
├── profile.php                # Page to update user profile
├── register.php               # User registration page
├── routes.php                 # Page to view all bus routes
├── terms.php                  # Privacy Policy page
└── view_booking.php           # Page to view booking details
```

---

## Prerequisites
To run this project locally, you need the following installed on your system:

- **XAMPP**: A local server environment with Apache, MySQL (MariaDB), and PHP.
  - Download: [XAMPP Official Website](https://www.apachefriends.org/download.html)
  - Recommended version: XAMPP with PHP 7.4 or higher.
- **Web Browser**: Chrome, Firefox, or any modern browser.
- **Text Editor**: VS Code, Sublime Text, or any editor for code modifications.
- **MySQL Client**: phpMyAdmin (comes with XAMPP) or MySQL Workbench for database management.

---

## Setup Instructions

### Step 1: Install XAMPP
1. **Download and Install XAMPP**:
   - Visit the [XAMPP download page](https://www.apachefriends.org/download.html) and download the version for your operating system (Windows, macOS, or Linux).
   - Install XAMPP following the on-screen instructions.
   - Default installation directory:
     - Windows: `C:\xampp`
     - macOS: `/Applications/XAMPP`
     - Linux: `/opt/lampp`

2. **Start XAMPP**:
   - Open the XAMPP Control Panel.
   - Start the **Apache** and **MySQL** modules by clicking the "Start" buttons next to them.
   - Verify that both services are running (ports 80 for Apache and 3306 for MySQL should be free).

### Step 2: Clone or Download the Project
1. **Download the Project**:
   - If the project files are in a ZIP archive, extract them to a folder named `city-bus-booking`.
   - Alternatively, if using Git, clone the repository (if hosted on a Git platform):
     ```bash
     git clone <repository-url> city-bus-booking
     ```

2. **Place the Project in XAMPP’s `htdocs` Directory**:
   - Move the `city-bus-booking` folder to XAMPP’s `htdocs` directory:
     - Windows: `C:\xampp\htdocs\city-bus-booking`
     - macOS: `/Applications/XAMPP/htdocs/city-bus-booking`
     - Linux: `/opt/lampp/htdocs/city-bus-booking`

### Step 3: Set Up the Database
1. **Access phpMyAdmin**:
   - Open your browser and navigate to `http://localhost/phpmyadmin`.
   - This opens phpMyAdmin, which is included with XAMPP.

2. **Create the Database**:
   - In phpMyAdmin, click on the “New” button in the left sidebar.
   - Enter the database name as `bus_booking` and click “Create”.

3. **Import the Database Schema**:
   - Select the `bus_booking` database from the left sidebar.
   - Go to the “Import” tab at the top.
   - Click “Choose File” and select the `db.sql` file from the project directory (`city-bus-booking/db.sql`).
   - Click “Go” to import the schema.
   - This will create the necessary tables (`users`, `bus_routes`, `bookings`, `contacts`, `subscribers`) and insert sample data.

4. **Verify the Database**:
   - In phpMyAdmin, click on the `bus_booking` database.
   - You should see the following tables:
     - `users`
     - `bus_routes`
     - `bookings`
     - `contacts`
     - `subscribers`
   - Click on each table to view the sample data. For example, the `users` table should have entries like `johndoe` and `janesmith`.

### Step 4: Configure Database Connection
1. **Open `config.php`**:
   - Navigate to `city-bus-booking/config.php` and open it in your text editor.

2. **Verify Database Credentials**:
   - The default database connection settings in `config.php` are:
     ```php
     $host = 'localhost';
     $db_name = 'bus_booking';
     $username = 'root';
     $password = ''; // Default XAMPP password is empty
     ```
   - These settings should work with a default XAMPP installation. If you’ve changed your MySQL username or password, update these values accordingly.

3. **Test the Connection**:
   - The `config.php` file includes a try-catch block to establish a PDO connection. If there’s an error, it will log the issue and display a message.

### Step 5: Add Required Images
The project uses several images for visual elements. These must be placed in the `assets/img/` directory.

1. **List of Required Images**:
   - `bus-hero.jpg` (1200x600): Hero section background on the homepage.
   - `bus-route-placeholder.jpg` (400x200): Placeholder for route cards on the routes page.
   - `about-us.jpg` (600x400): Image for the About Us page.
   - `page-header-bg.jpg` (1200x300): Background for page headers.
   - `world-map-bg.png` (1200x600): Background for the Happy Customers section.

2. **Sourcing Images**:
   - **Option 1: Use Placeholders** (Quick Setup):
     - Use Placeholder.com to generate placeholder images:
       - `bus-hero.jpg`: `https://via.placeholder.com/1200x600.png?text=Bus+Hero`
       - `bus-route-placeholder.jpg`: `https://via.placeholder.com/400x200.png?text=Bus+Route`
       - `about-us.jpg`: `https://via.placeholder.com/600x400.png?text=About+Us`
       - `page-header-bg.jpg`: `https://via.placeholder.com/1200x300.png?text=Page+Header+Background`
       - `world-map-bg.png`: `https://via.placeholder.com/1200x600.png?text=World+Map+Background`
     - Download each image by visiting the URL, right-clicking, and selecting “Save Image As.”
     - Save them with the exact filenames in the `assets/img/` directory.
   - **Option 2: Use Free Stock Photos** (Recommended for Better Visuals):
     - Source images from free stock photo websites:
       - **Unsplash**: [unsplash.com](https://unsplash.com) (Search for “bus travel,” “city bus,” “world map”)
       - **Pexels**: [pexels.com](https://www.pexels.com) (Search for “bus station,” “abstract background”)
       - **Pixabay**: [pixabay.com](https://pixabay.com) (Search for “travel agency,” “team”)
     - Download images that match the recommended dimensions, rename them to the required filenames, and save them in `assets/img/`.

3. **Verify Image Placement**:
   - After adding the images, your `assets/img/` directory should look like this:
     ```
     assets/img/
     ├── bus-hero.jpg
     ├── bus-route-placeholder.jpg
     ├── about-us.jpg
     ├── page-header-bg.jpg
     ├── world-map-bg.png
     ```
   - Ensure the filenames match exactly, as they are referenced in the PHP and CSS files.

### Step 6: Run the Project
1. **Start XAMPP Services**:
   - Open the XAMPP Control Panel.
   - Ensure **Apache** and **MySQL** are running.

2. **Access the Website**:
   - Open your browser and navigate to:
     ```
     http://localhost/city-bus-booking/index.php
     ```
   - If you placed the project in a different folder, adjust the URL accordingly (e.g., `http://localhost/your_folder_name/index.php`).

3. **Test the Website**:
   - **Homepage**: Verify the hero section, booking form, and other sections load correctly.
   - **Login/Register**:
     - Use the sample user credentials from `db.sql`:
       - Username: `johndoe`, Password: `password123`
       - Username: `janesmith`, Password: `password123`
     - Alternatively, register a new user via the registration page.
   - **Booking**:
     - Search for a route (e.g., Delhi to Mumbai), book a ticket, and verify it appears in “My Bookings.”
   - **Routes Page**: Check that all routes display with their placeholder images.
   - **Contact Form**: Submit a message and verify it’s saved in the `contacts` table (via phpMyAdmin).
   - **Newsletter**: Subscribe with an email and check the `subscribers` table.

---

## Database Details
The database schema is defined in `db.sql` and includes the following tables:

1. **users**:
   - Stores user information (ID, full name, username, email, password, creation date).
   - Sample users: `johndoe`, `janesmith`, `alicebrown` (password: `password123`).

2. **bus_routes**:
   - Stores available bus routes with departure and arrival locations, price, and max seats.
   - Sample routes: Delhi to Mumbai (₹1500), Mumbai to Bangalore (₹1200), etc.

3. **bookings**:
   - Stores user bookings with details like user ID, route, journey date, seats, total price, and status.
   - Sample bookings: Includes confirmed, pending, and cancelled bookings.

4. **contacts**:
   - Stores messages submitted via the contact form.
   - Sample entries: Feedback and booking issue messages.

5. **subscribers**:
   - Stores email addresses for newsletter subscriptions.
   - Sample entries: `john@example.com`, `jane@example.com`.

### Accessing the Database
- Use phpMyAdmin (`http://localhost/phpmyadmin`) to view or modify the database.
- Database name: `bus_booking`
- Default credentials (in `config.php`):
  - Username: `root`
  - Password: `''` (empty)

---

## Dependencies
The project uses the following third-party libraries, included in the `assets/vendor/` directory:

- **Bootstrap 5**: For responsive layout and styling.
  - Included via CDN in `header.php`:
    ```html
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    ```
- **Font Awesome**: For icons.
  - Included via CDN in `header.php`:
    ```html
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    ```
- **AOS (Animate on Scroll)**: For scroll animations.
  - Included via CDN in `header.php`:
    ```html
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    ```

---

## Troubleshooting
1. **Website Doesn’t Load**:
   - Ensure Apache and MySQL are running in XAMPP.
   - Check the URL: `http://localhost/city-bus-booking/index.php`.
   - Verify the project folder is in `htdocs`.

2. **Database Connection Error**:
   - Check `config.php` for correct database credentials.
   - Ensure the `bus_booking` database exists and the `db.sql` file was imported correctly.
   - Verify MySQL is running and the port (3306) is not blocked.

3. **Images Not Displaying**:
   - Ensure all images are in the `assets/img/` directory with the correct filenames.
   - Check file permissions (e.g., `chmod 644` for files, `chmod 755` for directories on Linux).
   - Open the browser’s developer tools (F12 → Network tab) to see if image requests are failing.

4. **Booking Form Issues**:
   - Ensure the `get_destinations.php` and `get_price.php` scripts are working (test by accessing them directly, e.g., `http://localhost/city-bus-booking/get_destinations.php?from=Delhi`).
   - Check the browser console for JavaScript errors (F12 → Console tab).

5. **Login Fails**:
   - Use the sample credentials (`johndoe`, `password123`) or register a new user.
   - Ensure the password in the `users` table is hashed correctly (matches `password123`).

---

## Deployment Notes
To deploy this project on a live server (e.g., a shared hosting provider):

1. **Upload Files**:
   - Use FTP (e.g., FileZilla) to upload the `city-bus-booking` folder to your server’s public directory (e.g., `public_html`).

2. **Set Up the Database**:
   - Create a database on your hosting provider’s control panel (e.g., cPanel → MySQL Databases).
   - Import the `db.sql` file using phpMyAdmin.
   - Update `config.php` with your live database credentials:
     ```php
     $host = 'your_host'; // Often 'localhost' or a specific server address
     $db_name = 'your_database_name';
     $username = 'your_database_username';
     $password = 'your_database_password';
     ```

3. **Upload Images**:
   - Ensure all images are uploaded to `assets/img/` on the server.

4. **Set File Permissions**:
   - Set permissions to `644` for files and `755` for directories to ensure images and scripts are accessible.

5. **Test the Website**:
   - Access your domain (e.g., `http://yourdomain.com/index.php`) and test all features.

---

## Sample Credentials
- **Users**:
  - Username: `johndoe`, Password: `password123`
  - Username: `janesmith`, Password: `password123`
  - Username: `alicebrown`, Password: `password123`
- **Database Access** (via phpMyAdmin):
  - Username: `root`, Password: `''` (empty, default for XAMPP)

---

## Future Enhancements
- **Payment Integration**: Add a payment gateway (e.g., Razorpay, PayPal) for real transactions.
- **Admin Panel**: Create an admin dashboard to manage users, routes, and bookings.
- **Email Notifications**: Send booking confirmations and updates via email using PHPMailer.
- **Advanced Search**: Add filters for routes (e.g., by price, date, or availability).
- **Image Optimization**: Compress images and use a CDN for faster loading.

---

## License
This project is for educational purposes and can be used freely. Ensure proper attribution if you use third-party assets (e.g., images from Unsplash).

---

## Contact
For support or inquiries, please reach out:
- **Email**: support@safwanganz.tech
- **Phone**: +91 701 207 4386

Happy coding! 🚍
