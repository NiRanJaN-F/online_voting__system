<?php
session_start();
include 'db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Google Font for Live Voting Results -->
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #e0eafc, #cfdef3);
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
      font-size: 1.6rem;
      color: #0d47a1 !important;
    }

    .nav-link {
      color: #333 !important;
      font-weight: 500;
    }

    .btn-outline-dark {
      border: 1px solid #333;
      color: #333;
    }

    .btn-outline-dark:hover {
      background-color: #333;
      color: #fff;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.75);
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }

    .card-header {
      background: rgba(13, 71, 161, 0.85);
      color: white;
      font-weight: 500;
      font-family: 'Ubuntu Condensed', sans-serif; /* Custom font for title */
      font-size: 1.5rem;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }

    .table thead th {
      background-color: #0d47a1;
      color: #fff;
    }
  </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Voting System</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav me-2">
        <li class="nav-item">
          <a class="nav-link" href="vote.php">Vote Now</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="results.php">View Results</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-dark ms-2" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Container -->
<div class="container">
  <h2 class="text-center text-dark mb-5">Welcome to the Voting System</h2>

  <!-- Voting Results -->
  <div class="glass-card p-4">
    <div class="card-header">
      Live Voting Results
    </div>
    <div class="card-body">
      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th>Candidate</th>
            <th>Votes</th>
          </tr>
        </thead>
        <tbody id="voteResults">
          <!-- Dynamic vote data -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// Live vote fetching
function fetchVotes() {
  $.ajax({
    url: "fetch_votes.php",
    method: "GET",
    dataType: "json",
    success: function(data) {
      let tableRows = "";
      data.forEach(function(candidate) {
        tableRows += `<tr><td>${candidate.name}</td><td>${candidate.votes}</td></tr>`;
      });
      $("#voteResults").html(tableRows);
    },
    error: function() {
      $("#voteResults").html('<tr><td colspan="2">Unable to fetch live results.</td></tr>');
    }
  });
}
setInterval(fetchVotes, 3000); // Refresh every 3 seconds
fetchVotes(); // Initial load
</script>

</body>
</html>
