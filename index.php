<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Selection</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

  <style>
    /* Grey background gradient - subtle and neutral */
    body {
      background: linear-gradient(135deg, #d0d0d0, #a0a0a0);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #555555; /* medium grey */
      padding-top: 70px;
      min-height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start; /* align items top */
      flex-direction: column;
      gap: 15px; /* gap between elements */
      padding-top: 40px; /* slightly less padding for top */
    }

    /* Navbar removed brand text, but keep styling */
    .navbar {
      background-color: #5a5a5a;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      height: 50px;
      z-index: 10;
    }

    /* Title above the card */
    .main-header {
      width: 350px;
      margin: 0 auto;
      text-align: center;
      font-weight: 700;
      font-size: 2.5rem;
      color: #666666; /* medium grey */
      user-select: none;
      font-family: 'Segoe UI Black', 'Segoe UI', Tahoma, Geneva, Verdana,
        sans-serif;
      line-height: 1; /* single line */
    }

    /* Glass Effect for Card */
    .card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
      width: 350px;
      margin: 0 auto;
    }

    .card:hover {
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    h2 {
      color: #555555;
      font-weight: bold;
      margin-bottom: 1.5rem;
    }

    /* Button Styling */
    .btn-primary {
      background-color: #888888;
      border: none;
      font-weight: 600;
      transition: background-color 0.3s, transform 0.3s;
      border-radius: 30px;
      color: white;
    }

    .btn-primary:hover {
      background-color: #666666;
      transform: scale(1.05);
    }

    .btn-danger {
      background-color: #777777;
      border: none;
      font-weight: 600;
      transition: background-color 0.3s, transform 0.3s;
      border-radius: 30px;
      color: white;
    }

    .btn-danger:hover {
      background-color: #555555;
      transform: scale(1.05);
    }

    a.btn {
      width: 100%;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <!-- Navbar with no brand text -->
  <nav class="navbar"></nav>

  <div class="main-header">Online Voting System</div>
  <div class="card p-4 shadow-lg text-center">
    <h2>Select Login Type</h2>
    <a href="login.php" class="btn btn-primary btn-lg mb-3">User Login</a>
    <a href="admin_login.php" class="btn btn-danger btn-lg">Admin Login</a>
  </div>
</body>
</html>
