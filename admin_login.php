<?php
session_start();
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id']; // Store admin session
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #6c757d; /* Mid grey */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #1F4959; /* Slate blue */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand {
            font-weight: bold;
            color: #ffffff;
        }

        .navbar-brand:hover {
            color: #5C7C89; /* Muted teal hover */
        }

        .page-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 70px;
            padding-bottom: 30px;
        }

        .card {
            background-color: #5C7C89; /* Muted teal */
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            color: #ffffff;
            width: 100%;
            max-width: 450px; /* Slightly larger */
        }

        .card-header {
            background-color: #1F4959;
            border-bottom: none;
            text-align: center;
        }

        h4 {
            font-weight: bold;
            color: #ffffff;
        }

        .btn-primary {
            background-color: #1F4959; /* Slate blue */
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #242424; /* Dark gray */
            transform: scale(1.05);
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .alert {
            margin-bottom: 20px;
        }

        a {
            color: #ffffff;
            text-decoration: underline;
        }

        a:hover {
            color: #242424;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Online Voting System</a>
    </div>
</nav>

<!-- Login Form Wrapper -->
<div class="page-wrapper">
    <div class="card shadow">
        <div class="card-header">
            <h4>Admin Login</h4>
        </div>
        <div class="card-body">
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="mt-3 text-center">
                <a href="admin_register.php">Don't have an account? Register here</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
