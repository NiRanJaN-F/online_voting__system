<?php
include 'db.php';

header('Content-Type: application/json');

$query = "SELECT name, votes FROM candidates ORDER BY votes DESC";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'name' => $row['name'],
        'votes' => $row['votes']
    ];
}

echo json_encode($data);
?>
