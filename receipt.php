<?php
$servername = "localhost:3305";
$username = "root";
$password = "Kartik@123";
$dbname = "CharityDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get receipt ID from query parameter
$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

// Fetch receipt details
$sql = "SELECT * FROM DonationReceipt WHERE ReceiptID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receipt_id);
$stmt->execute();
$result = $stmt->get_result();
$receipt = $result->fetch_assoc();

if (!$receipt) {
    die("Receipt not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">DONATE INDIA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mailto:kartikgopal01@gmail.com">Contact</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Receipt Details -->
    <div class="container mt-5">
        <h2 class="mb-4">Donation Receipt</h2>
        <ul class="list-group">
            <li class="list-group-item"><strong>Receipt ID:</strong> <?= htmlspecialchars($receipt['ReceiptID']) ?></li>
            <li class="list-group-item"><strong>Donor Name:</strong> <?= htmlspecialchars($receipt['DonorName']) ?></li>
            <li class="list-group-item"><strong>Donation Amount:</strong> <?= htmlspecialchars(number_format($receipt['Amount'], 2)) ?></li>
            <li class="list-group-item"><strong>Issue Date:</strong>
