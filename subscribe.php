<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = validateInput($_POST['email']);

    if (empty($email)) {
        $_SESSION['error'] = "Email is required";
        header("Location: index.php");
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: index.php");
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT subscriber_id FROM subscribers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already subscribed";
            header("Location: index.php");
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->execute([$email]);

        $_SESSION['success'] = "Thank you for subscribing!";
        header("Location: index.php");
        exit;
    } catch(PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>