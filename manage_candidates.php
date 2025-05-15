<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle candidate addition
if (isset($_POST['add_candidate'])) {
    $id = (int) $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $party = mysqli_real_escape_string($conn, $_POST['party']);
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id']);

    $check_query = "SELECT * FROM candidates WHERE id = $id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "Candidate ID already exists!";
    } else {
        $query = "INSERT INTO candidates (id, name, party, voter_id, votes) 
                  VALUES ($id, '$name', '$party', '$voter_id', 0)";
        if (mysqli_query($conn, $query)) {
            $success = "Candidate added successfully!";
        } else {
            $error = "Error adding candidate!";
        }
    }
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM candidates WHERE id = $delete_id";
    mysqli_query($conn, $query);
    header("Location: manage_candidates.php");
    exit();
}

// Fetch all candidates
$candidates = mysqli_query($conn, "SELECT * FROM candidates");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Candidates</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            min-height: 100vh;
            padding: 30px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
            padding: 25px;
            margin-bottom: 40px;
        }
        .glass-card h4 {
            font-weight: 600;
            color: #2c3e50;
        }
        table {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .table thead th {
            background-color: rgba(44, 62, 80, 0.7);
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4 text-dark">Manage Candidates</h2>

    <div class="glass-card">
        <h4>Add New Candidate</h4>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Candidate ID</label>
                <input type="number" name="id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Candidate Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Party Name</label>
                <input type="text" name="party" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Voter ID</label>
                <input type="text" name="voter_id" class="form-control" required>
            </div>
            <button type="submit" name="add_candidate" class="btn btn-success">Add Candidate</button>
        </form>
    </div>

    <div class="glass-card">
        <h4>Candidate List</h4>
        <table class="table table-bordered mt-3 text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Party</th>
                    <th>Voter ID</th>
                    <th>Votes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($candidates)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['party'] ?></td>
                        <td><?= $row['voter_id'] ?></td>
                        <td><?= $row['votes'] ?></td>
                        <td>
                            <a href="edit_candidate.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_candidates.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
