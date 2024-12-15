<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Check if the user is logged in

// Set api key and URL
$apiKey = "5863d63c048b3d326e4588e048cf4de6"; //maybe keep this in a separate file or something
$city = "Delanco";
$apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=imperial";
// https://api.openweathermap.org/data/2.5/weather?q=Delanco&appid=5863d63c048b3d326e4588e048cf4de6&units=imperial

// Fetch weather data
$weatherData = file_get_contents($apiUrl);
if ($weatherData === false) {
    echo "Error fetching weather data.";
    exit;
}
// This puts everything into json format, can take things out from the array and use a variable.
// Most of the information in the json file can be found here --> https://openweathermap.org/current
//or the actual json file from the url --> https://api.openweathermap.org/data/2.5/weather?q=Delanco&appid=5863d63c048b3d326e4588e048cf4de6&units=imperial
$weatherArray = json_decode($weatherData, true);
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
                            <a class="nav-link" href="index.php">Home</a>
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
                                    <button onclick="window.location.href='account/login.php'" class="btn btn-warning">
                                        Account Settings
                                    </button>
                                    <form action="account/logout.php" method="post" style="display: inline;">
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
                                        <button onclick="window.location.href='account/login.php'" class="btn btn-primary">
                                            Login
                                        </button>
                                        <button onclick="window.location.href='account/login.php'" class="btn btn-primary">
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

    <div class="container">
        <?php
        // Check if data is valid
        if ($weatherArray && isset($weatherArray['main'])) {
            $temperature = $weatherArray['main']['temp'];
            $sealevel = $weatherArray['main']['sea_level'];
            $humidity = $weatherArray['main']['humidity'];
            $pressure = $weatherArray['main']['pressure'];
            $weatherDescription = $weatherArray['weather'][0]['description'];

            // Display data, Temporarily just echo statements, will be a nice chart or something later
            echo "<h1>Weather in {$city}</h1>";
            echo "<p>Temperature: {$temperature}°F</p>";
            echo "<p>Humidity: {$humidity}%</p>";
            echo "<p>Pressure: {$pressure} hPa</p>";
            echo "<p>Condition: {$weatherDescription}</p>";
            echo "<p>Sea Level (atmospheric pressure or something): {$sealevel}</p>";
        } else {
            echo "Error retrieving valid weather data.";
        }
        ?>
    </div>
</body>

</html>