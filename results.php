<?php
include 'db.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voting Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #bdc3c7, #2c3e50);
            min-height: 100vh;
            padding: 30px;
            color: #fff;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.07);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px) saturate(150%);
            -webkit-backdrop-filter: blur(10px) saturate(150%);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-bottom: 40px;
        }
        .table th, .table td {
            color: #000;  /* Changed to black for better visibility */
        }
        .table thead th {
            background-color: rgba(44, 62, 80, 0.9);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.25);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="glass-card">
        <h2 class="text-center mb-4">Voting Results</h2>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to fetch voting results from the database
                $query = "SELECT name, votes FROM candidates";
                $result = mysqli_query($conn, $query);

                // Error handling: Check if the query was successful
                if (!$result) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Displaying the results in the table
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['name']}</td><td>{$row['votes']}</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No results found.</td></tr>"; // Display message if no data
                }
                ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
