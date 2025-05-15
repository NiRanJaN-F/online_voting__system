<?php
include 'config.php';
session_start();

$error = "";

// OTP verification
if (isset($_POST['otp_submit'])) {
    $enteredOtp = $_POST['otp_input'];
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        $_SESSION['user_id'] = $_SESSION['pending_user']['id'];
        $_SESSION['username'] = $_SESSION['pending_user']['username'];
        unset($_SESSION['pending_user'], $_SESSION['otp']);
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid OTP!";
    }
}

// Main login
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['otp_submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['pending_user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $email
                ];
                header("Location: login.php?otp=1");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #d3d3d3; /* Medium light grey */
      min-height: 100vh;
      padding: 30px;
      color: #333;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.85);
      border-radius: 20px;
      border: 1px solid rgba(0, 0, 0, 0.05);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      padding: 30px;
    }
    .form-label {
      color: #2c3e50;
    }
    .form-control {
      background-color: rgba(255, 255, 255, 0.6);
      border: 1px solid #ccc;
      color: #333;
    }
    .form-control:focus {
      background-color: #fff;
      border-color: #3498db;
      box-shadow: none;
    }
    .btn-primary {
      background-color: #3498db;
      border: none;
    }
    .btn-primary:hover {
      background-color: #2980b9;
    }
    .btn-success {
      background-color: #27ae60;
      border: none;
    }
    .btn-success:hover {
      background-color: #1e8449;
    }
    .btn-link {
      color: #c0392b;
    }
    .btn-link:hover {
      color: #922b21;
    }
    .alert-danger {
      background-color: rgba(255, 0, 0, 0.1);
      color: #a94442;
      border: 1px solid #e74c3c;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="glass-card">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (!empty($error)) { ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <?php if (!isset($_GET['otp']) && !isset($_POST['otp_submit'])): ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
          <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="btn-link">Register here</a></p>
        <?php else: ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Enter OTP</label>
              <input type="text" name="otp_input" class="form-control" required />
            </div>
            <button type="submit" name="otp_submit" class="btn btn-success w-100">Verify OTP</button>
          </form>
          <div class="text-center mt-3">
            <button type="button" id="resendOtpBtn" class="btn btn-link">Didn't receive OTP? Click to resend</button>
            <div class="d-flex justify-content-center align-items-center mt-2">
              <div id="spinner" class="spinner-border text-primary me-2" role="status" style="display: none; width: 1.2rem; height: 1.2rem;">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p id="resendMsg" class="text-success mb-0"></p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const resendBtn = document.getElementById("resendOtpBtn");
  const msgBox = document.getElementById("resendMsg");
  const spinner = document.getElementById("spinner");

  function startCooldown() {
    let secondsLeft = 20;
    resendBtn.disabled = true;
    resendBtn.innerText = `Resend available in ${secondsLeft}s`;
    const countdown = setInterval(() => {
      secondsLeft--;
      resendBtn.innerText = `Resend available in ${secondsLeft}s`;
      if (secondsLeft <= 0) {
        clearInterval(countdown);
        resendBtn.disabled = false;
        resendBtn.innerText = "Didn't receive OTP? Click to resend";
      }
    }, 1000);
  }

  function sendOtpAndHandleCooldown() {
    spinner.style.display = "inline-block";
    msgBox.innerText = "";
    fetch("send_otp.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: ""
    })
    .then(response => response.text())
    .then(data => {
      spinner.style.display = "none";
      msgBox.innerText = data;
      if (!data.includes("Please wait")) {
        startCooldown();
      }
    })
    .catch(() => {
      spinner.style.display = "none";
      msgBox.innerText = "Error sending OTP.";
    });
  }

  if (resendBtn) {
    resendBtn.addEventListener("click", sendOtpAndHandleCooldown);
  }

  <?php if (isset($_GET['otp'])): ?>
  sendOtpAndHandleCooldown();
  <?php endif; ?>
});
</script>
</body>
</html>
