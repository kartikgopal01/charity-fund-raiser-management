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

// Get campaign ID from query parameter
$campaign_id = isset($_GET['campaign_id']) ? intval($_GET['campaign_id']) : 0;

// Fetch campaign details
$sql = "SELECT Name FROM Campaign WHERE CampaignID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $campaign_id);
$stmt->execute();
$result = $stmt->get_result();
$campaign = $result->fetch_assoc();

if (!$campaign) {
    die("Campaign not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_name = $_POST['donor_name'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    // Insert donation
    $sql = "INSERT INTO Donation (DonorID, Amount, Date, PaymentMethod) VALUES (NULL, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $amount, $payment_method);
    $stmt->execute();

    // Get the ID of the new donation
    $donation_id = $stmt->insert_id;

    // Insert donation receipt
    $sql = "INSERT INTO DonationReceipt (DonationID, IssueDate, Amount, DonorName) VALUES (?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ids", $donation_id, $amount, $donor_name);
    $stmt->execute();

    // Fetch the receipt details
    $receipt_id = $stmt->insert_id;
    $sql = "SELECT * FROM DonationReceipt WHERE ReceiptID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $receipt = $stmt->get_result()->fetch_assoc();

    echo "<div class='container mt-5'>
            <div class='alert alert-success'>Thank you for your donation!</div>
            <h2 class='mb-4'>Donation Receipt</h2>
            <ul class='list-group'>
                <li class='list-group-item'><strong>Receipt ID:</strong> " . htmlspecialchars($receipt['ReceiptID']) . "</li>
                <li class='list-group-item'><strong>Donor Name:</strong> " . htmlspecialchars($receipt['DonorName']) . "</li>
                <li class='list-group-item'><strong>Donation Amount:</strong> " . htmlspecialchars(number_format($receipt['Amount'], 2)) . "</li>
                <li class='list-group-item'><strong>Issue Date:</strong> " . htmlspecialchars($receipt['IssueDate']) . "</li>
            </ul>
          </div>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate to Campaign</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Charity Organization</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Donation Form -->
    <div class="container mt-5">
        <h1 class="mb-4">Donate to Campaign</h1>
        <form action="donate.php?campaign_id=<?= htmlspecialchars($campaign_id) ?>" method="post">
            <div class="form-group">
                <label for="donor_name">Donor Name:</label>
                <input type="text" class="form-control" id="donor_name" name="donor_name" required>
            </div>
            <div class="form-group">
                <label for="amount">Donation Amount :</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="UPI">UPI</option>
                    <option value="Card">Card</option>
                </select>
            </div>

            <!-- Card Details Section (Hidden by default) -->
            <div id="card_details" style="display: none;">
                <div class="form-group">
                    <label for="card_number">Card Number:</label>
                    <input type="text" class="form-control" id="card_number" name="card_number">
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date:</label>
                    <input type="text" class="form-control" id="expiry_date" name="expiry_date">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" class="form-control" id="cvv" name="cvv">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Donate</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Show card details form if "Card" is selected
        document.getElementById('payment_method').addEventListener('change', function () {
            var cardDetails = document.getElementById('card_details');
            if (this.value === 'Card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    </script>
</body>
</html>
