<?php
include 'config.php';
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp_input'];
    $newPassword = $_POST['new_password'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        $email = $_SESSION['pending_user_email'];
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update password in database
        $query = "UPDATE users SET password='$hashedPassword' WHERE email='$email'";
        if ($conn->query($query) === TRUE) {
            unset($_SESSION['otp'], $_SESSION['pending_user_email']);
            header("Location: login.php");
            exit();
        } else {
            $error = "Error updating password.";
        }
    } else {
        $error = "Invalid OTP!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center mb-4">Reset Password</h2>

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Enter OTP</label>
                            <input type="text" name="otp_input" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
