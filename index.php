<?php
session_start();
$servername = "localhost:3305";
$username = "root";
$password = "Kartik@123";
$dbname = "CharityDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch campaign data
$sql = "SELECT CampaignID, Name, Description, StartDate, EndDate, Budget FROM Campaign";
$result = $conn->query($sql);

$campaigns = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $campaigns[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charity Campaigns</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">DONATE INDIA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mailto:kartikgopal01@gmail.com">Contact</a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#loginModal">Admin Login</button>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Carousel -->
<div id="charityCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#charityCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#charityCarousel" data-slide-to="1"></li>
        <li data-target="#charityCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="image.png" class="d-block w-100" alt="Charity Image 1">
        </div>
        <div class="carousel-item">
            <img src="donate.jpg" class="d-block w-100" alt="Charity Image 2">
        </div>
        <div class="carousel-item">
            <img src="images.png" class="d-block w-100" alt="Charity Image 3">
        </div>
    </div>
    <a class="carousel-control-prev" href="#charityCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#charityCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>


   
    <div class="container mt-5">
        <h1 class="text-center mb-4">Charity Campaigns</h1>
        <div class="row">
            <?php foreach ($campaigns as $campaign): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($campaign['Name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($campaign['Description']) ?></p>
                            <p class="card-text"><small class="text-muted">Start Date: <?= htmlspecialchars($campaign['StartDate']) ?></small></p>
                            <p class="card-text"><small class="text-muted">End Date: <?= htmlspecialchars($campaign['EndDate']) ?></small></p>
                            <p class="card-text"><strong>Budget: <?= htmlspecialchars(number_format($campaign['Budget'], 2)) ?></strong> INR</p>
                            <a href="donate.php?campaign_id=<?= htmlspecialchars($campaign['CampaignID']) ?>" class="btn btn-primary">Donate Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Admin Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="login.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase">Contact Us</h5>
                <p>Email: <a href="mailto:kartikgopal01@gmail.com">kartikgopal01@gmail.com</a></p>
                <p>Phone: +91-7760404689</p>
                <p>Address: Bengle, Sirsi, Karnataka, 581318</p>
            </div>
            
            <!-- Social Media Links -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase">Follow Us</h5>
                <a href="facebook.com" class="btn btn-primary btn-floating m-1" role="button"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="btn btn-primary btn-floating m-1" role="button"><i class="fab fa-twitter"></i></a>
                <a href="#" class="btn btn-primary btn-floating m-1" role="button"><i class="fab fa-instagram"></i></a>
                <a href="#" class="btn btn-primary btn-floating m-1" role="button"><i class="fab fa-linkedin-in"></i></a>
            </div>
            
            <!-- Legal Information -->
            <div class="col-lg-4 col-md-12 mb-4">
                <h5 class="text-uppercase">Legal</h5>
                <p><a href="#">Privacy Policy</a></p>
                <p><a href="#">Terms of Service</a></p>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center p-3 bg-dark text-white">
        &copy; <?= date("Y") ?> Charity Organization. All rights reserved.
    </div>
</footer>

<!-- Font Awesome (for social media icons) -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
