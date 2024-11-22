<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Check if the user is logged in
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hawk Island Marina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add custom styles here if needed */
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-body rounded" aria-label="Eleventh navbar example">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Hawk Island Marina</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExample09">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="weather.php">Weather (temporary)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reservations.php">Reserve a Space</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <?php if ($isLoggedIn): ?>
                            <li class="nav-item"> <!-- This part should be positioned at the right -->
                                <!-- Display options for logged-in users -->
                                <div class="text-center" style="margin-top: 5px;">
                                    <h5>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h5>
                                </div>
                            </li>
                            <li class="nav-item" style="margin-left: 5px;">
                                <div class="btn-container">
                                    <button onclick="window.location.href='login.php'" class="btn btn-warning">
                                        Account Settings
                                    </button>
                                    <form action="logout.php" method="post" style="display: inline;">
                                        <button type="submit" class="btn btn-danger">Logout</button>
                                    </form>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item" style="margin-top: 5px;">
                                <h5>You are not logged in!</h5>
                            </li>
                            <li class="nav-item" style="margin-left: 5px;">
                                <!-- This part should be positioned at the right -->
                                <!-- Display options for non-logged-in users -->
                                <div class="text-center">
                                    <div class="btn-container">
                                        <button onclick="window.location.href='login.php'" class="btn btn-primary">
                                            Login
                                        </button>
                                        <button onclick="window.location.href='login.php'" class="btn btn-primary">
                                            Create Account
                                        </button>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </nav>
    </div>
    <!--NAVBAR OVER-->

    <!--MAIN CONTENT -->
    <div class="container mt-3">
        <div class="col-12 bg-body">
            <div id="box" class="py-4  px-5">
                <h1>Hawk Island Marina</h1>
                <h3>A family owned - private marina</h3>
                <h6>Only 2 miles north of Philadelphia, PA & Camden, NJ</h6>
                <hr class="featurette-divider">
                <p>
                    Hawk Island Marina is conveniently located at the mouth of the Rancocas Creek. Offering quick and
                    easy access to both the Tacony and Burlington Bristol Bridges.
                </p>
                <p>
                    In October 2003, John and Karen purchased Hawk Island Marina. Both with family roots in Delanco, NJ,
                    and as long-time patrons of this marina, they knew that this would be a great opportunity to expand
                    upon the existing marina. They aimed to incorporate sound environmental policies while maintaining
                    the ecosystem and providing a safe, fun, and positive recreational experience.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>