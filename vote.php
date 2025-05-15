<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Check if user has already voted
$checkVote = "SELECT * FROM votes WHERE user_id='$user_id'";
$result = $conn->query($checkVote);

$hasVoted = false;
if ($result->num_rows > 0) {
    $error = "You have already voted! Only one vote per user is allowed.";
    $hasVoted = true;
}

// Fetch candidates from database
$candidatesQuery = "SELECT * FROM candidates";
$candidatesResult = $conn->query($candidatesQuery);

// Handle Vote Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$hasVoted) {
    $candidate_id = $_POST['candidate_id'];

    if (empty($candidate_id)) {
        $error = "Invalid candidate!";
    } else {
        // Update vote count
        $voteQuery = "UPDATE candidates SET votes = votes + 1 WHERE id = '$candidate_id'";
        if ($conn->query($voteQuery) === TRUE) {
            // Record the vote
            $insertVote = "INSERT INTO votes (user_id, candidate_id) VALUES ('$user_id', '$candidate_id')";
            $conn->query($insertVote);

            $success = "Vote submitted successfully!";
            $hasVoted = true;
        } else {
            $error = "Error submitting vote: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Now</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d5e1e4, #c9e2e7); /* Soft light gradient */
            min-height: 100vh;
            padding: 30px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8); /* Subtle transparency */
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px) saturate(150%); /* Light blur effect */
            -webkit-backdrop-filter: blur(12px) saturate(150%);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 30px;
        }
        .table {
            background: rgba(255, 255, 255, 0.7); /* Light glass effect on the table */
            border-radius: 10px;
            backdrop-filter: blur(8px); /* Subtle blur effect */
            -webkit-backdrop-filter: blur(8px);
        }
        .table th, .table td {
            color: #333;
        }
        .table th {
            background-color: #34495e; /* Dark background for table header */
            color: white; /* White text for contrast */
        }
        .btn-primary {
            background-color: #6c757d; /* Gray button for neutral tone */
            border: 1px solid #6c757d;
        }
        .btn-primary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }
        .alert-danger {
            background-color: rgba(255, 0, 0, 0.1);
            color: #333;
            border: 1px solid #e74c3c;
        }
        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: #333;
            border: 1px solid #2ecc71;
        }
        .form-label {
            color: #2c3e50;
        }
        .btn-link {
            color: #3498db;  /* Light blue for link */
        }
        .btn-link:hover {
            color: #2980b9;  /* Darker blue on hover */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg p-4 glass-card">
                    <h2 class="text-center mb-4">Vote for Your Candidate</h2>

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php } ?>

                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Candidate ID</th>
                                <th>Candidate Name</th>
                                <th>Party</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $candidatesResult->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['party']; ?></td>
                                    <td>
                                        <?php if (!$hasVoted) { ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="candidate_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                                            </form>
                                        <?php } else { ?>
                                            <span class="text-muted">Vote Cast</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <p class="mt-3 text-center">
                        <a href="dashboard.php">Back to Dashboard</a> | 
                        <a href="logout.php" class="text-danger">Logout</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
