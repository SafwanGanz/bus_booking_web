<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'My Profile';
$breadcrumb = 'My Profile';

// Fetch user details
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $user = [];
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = validateInput($_POST['full_name']);
    $email = validateInput($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if (empty($full_name) || empty($email)) {
        $_SESSION['error'] = "Full name and email are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    } else {
        try {
            $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user_data = $stmt->fetch();

            if (!password_verify($current_password, $user_data['password'])) {
                $_SESSION['error'] = "Current password is incorrect";
            } elseif (!empty($new_password) && $new_password !== $confirm_new_password) {
                $_SESSION['error'] = "New passwords do not match";
            } elseif (!empty($new_password) && strlen($new_password) < 8) {
                $_SESSION['error'] = "New password must be at least 8 characters long";
            } else {
                $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE user_id = ?");
                $stmt->execute([$full_name, $email, $_SESSION['user_id']]);

                if (!empty($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                    $stmt->execute([$hashed_password, $_SESSION['user_id']]);
                }

                $_SESSION['full_name'] = $full_name;
                $_SESSION['success'] = "Profile updated successfully";
                header("Location: profile.php");
                exit;
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }
}

include 'header.php';
?>

<div class="container content">
    <div class="page-header">
        <h1>My Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Profile</li>
            </ol>
        </nav>
    </div>

    <div class="auth-container" data-aos="fade-up">
        <div class="auth-form">
            <div class="auth-header">
                <h2>Update Profile</h2>
                <p>Manage your account details below.</p>
            </div>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <form action="profile.php" method="post">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password (Optional)</label>
                    <input type="password" id="new_password" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>