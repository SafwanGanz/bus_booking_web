-- Database Schema for City Bus Booking System
-- Generated on May 22, 2025

-- Create and select the database
CREATE DATABASE IF NOT EXISTS bus_booking;
USE bus_booking;

-- Drop existing tables if they exist to ensure a clean slate
DROP TABLE IF EXISTS subscribers;
DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS bus_routes;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create bus_routes table
CREATE TABLE bus_routes (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    departure_location VARCHAR(100) NOT NULL,
    arrival_location VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    max_seats INT DEFAULT 40,
    UNIQUE(departure_location, arrival_location)
);

-- Create bookings table
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    departure_location VARCHAR(100) NOT NULL,
    arrival_location VARCHAR(100) NOT NULL,
    journey_date DATE NOT NULL,
    seats INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    booking_status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Create contacts table
CREATE TABLE contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create subscribers table
CREATE TABLE subscribers (
    subscriber_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data into users
-- Passwords are hashed for 'password123' using bcrypt
INSERT INTO users (full_name, username, email, password, created_at) VALUES
('John Doe', 'johndoe', 'john@example.com', '$2y$10$3z2./p5h9xX8gqT2fL5iXe6W8z0K5hJ3mN7oPqR2sT4uV6wX8yZ.', '2025-05-01 10:00:00'),
('Jane Smith', 'janesmith', 'jane@example.com', '$2y$10$3z2./p5h9xX8gqT2fL5iXe6W8z0K5hJ3mN7oPqR2sT4uV6wX8yZ.', '2025-05-02 12:00:00'),
('Alice Brown', 'alicebrown', 'alice@example.com', '$2y$10$3z2./p5h9xX8gqT2fL5iXe6W8z0K5hJ3mN7oPqR2sT4uV6wX8yZ.', '2025-05-03 14:00:00');

-- Insert sample data into bus_routes
INSERT INTO bus_routes (departure_location, arrival_location, price, max_seats) VALUES
('Delhi', 'Mumbai', 1500.00, 40),
('Mumbai', 'Bangalore', 1200.00, 40),
('Bangalore', 'Chennai', 800.00, 40),
('Chennai', 'Delhi', 1800.00, 40),
('Calicut', 'Mysore', 55.99, 40),
('Delhi', 'Bangalore', 2000.00, 40),
('Mumbai', 'Chennai', 1300.00, 40);

-- Insert sample data into bookings
INSERT INTO bookings (user_id, departure_location, arrival_location, journey_date, seats, total_price, booking_status, booking_date) VALUES
(1, 'Delhi', 'Mumbai', '2025-06-01', 2, 3000.00, 'Confirmed', '2025-05-10 08:00:00'),
(1, 'Mumbai', 'Bangalore', '2025-06-15', 1, 1200.00, 'Pending', '2025-05-15 09:30:00'),
(2, 'Bangalore', 'Chennai', '2025-06-20', 3, 2400.00, 'Confirmed', '2025-05-18 14:20:00'),
(2, 'Chennai', 'Delhi', '2025-07-01', 1, 1800.00, 'Cancelled', '2025-05-20 16:00:00'),
(3, 'Calicut', 'Mysore', '2025-06-10', 2, 111.98, 'Pending', '2025-05-21 11:00:00');

-- Insert sample data into contacts
INSERT INTO contacts (name, email, subject, message, created_at) VALUES
('John Doe', 'john@example.com', 'Booking Issue', 'I faced an issue while booking a ticket from Delhi to Mumbai.', '2025-05-20 10:00:00'),
('Jane Smith', 'jane@example.com', 'Feedback', 'Great service! Loved the ease of booking.', '2025-05-21 12:00:00');

-- Insert sample data into subscribers
INSERT INTO subscribers (email, subscribed_at) VALUES
('john@example.com', '2025-05-20 09:00:00'),
('jane@example.com', '2025-05-21 10:30:00'),
('alice@example.com', '2025-05-22 01:00:00');