<?php
session_start();
include 'db.php'; 

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle candidate deletion safely
if (isset($_GET['delete_candidate'])) {
    $candidate_id = $_GET['delete_candidate'];
    
    // Delete votes related to this candidate first
    $delete_votes = "DELETE FROM votes WHERE candidate_id = '$candidate_id'";
    mysqli_query($conn, $delete_votes);
    
    // Now delete the candidate
    $delete_candidate = "DELETE FROM candidates WHERE id = '$candidate_id'";
    mysqli_query($conn, $delete_candidate);
    
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #c9d6ff, #e2e2e2);
            min-height: 100vh;
            padding-top: 70px;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.6) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            color: #0d47a1 !important;
        }

        .logout-btn {
            color: #fff;
            background-color: #d32f2f;
            border: none;
        }

        .logout-btn:hover {
            background-color: #b71c1c;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .action-buttons a {
            margin-bottom: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="ms-auto">
            <a href="admin_logout.php" class="btn logout-btn">Logout</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <div class="glass-panel">
        <h2 class="mb-4 text-dark">Admin Actions</h2>

        <div class="action-buttons d-flex flex-column align-items-start">
            <a href="reset_votes.php" class="btn btn-warning">Reset Votes</a>
            <a href="manage_candidates.php" class="btn btn-primary">Manage Candidates</a>
            <a href="manage_voters.php" class="btn btn-success">Manage Voters</a>
            <a href="results.php" class="btn btn-info text-white">View Results</a>
        </div>
    </div>
</div>

</body>
</html>
