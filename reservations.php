<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Check if the user is logged in
$isEmployee = ($_SESSION['is_employee']);

include "dbconfig.php";
$con = mysqli_connect($host, $username, $password, $dbname) or die("<br>Cannot connect to DB:$dbname on $host\n");
$query = "SELECT * FROM test;";
$result = mysqli_query($con, $query);

//Initialize spot information in a list. Put data from database into the list.
$spotOccupy = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Populate `$spotOccupy` with 'open' or 'closed' based on the 'open' column value.
    $spotOccupy[] = $row['open'] == 1 ? 'open' : 'closed';
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Marina Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .map-container {
            position: relative;
            width: 908px;
            height: 593px;
            background-image: url('images/marina_map.png');
            background-size: cover;
            border: 1px solid #ccc;
        }

        .spot {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            cursor: pointer;
        }

        .open {
            background-color: green;
        }

        .closed {
            background-color: lightgray;
            border: 2px solid red;
            color: darkred;
        }
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

    <h2>Marina Spot Availability</h2>
    <div class="map-container" id="marina-map">
        <!-- Positioned spots on the marina map -->
        <!-- Creates a div for the styled spot, which is just a circle. Checks the created list and echoes the number (hardcoded). 
     If the spot is open, then allow an onclick request which goes to the function requestspot below.-->
        <div class="spot <?php echo $spotOccupy[0]; ?>" style="top: 340px; left: 270px;" <?php if ($spotOccupy[0] == "open") {
               echo 'onclick="requestSpot(\'Spot 1\')"';
           } ?>>Spot 1</div>
        <div class="spot <?php echo $spotOccupy[1]; ?>" style="top: 120px; left: 350px;" <?php if ($spotOccupy[1] == "open") {
               echo 'onclick="requestSpot(\'Spot 2\')"';
           } ?>>Spot 2</div>
        <div class="spot <?php echo $spotOccupy[2]; ?>" style="top: 80px; left: 500px;" <?php if ($spotOccupy[2] == "open") {
               echo 'onclick="requestSpot(\'Spot 3\')"';
           } ?>>Spot 3</div>
        <div class="spot <?php echo $spotOccupy[3]; ?>" style="top: 200px; left: 730px;" <?php if ($spotOccupy[3] == "open") {
               echo 'onclick="requestSpot(\'Spot 4\')"';
           } ?>>Spot 4</div>
    </div>


    <?php if (!$isLoggedIn): ?>
        <div class="content" style="margin-left:10px;">
            <h1>Not Logged in!</h1>
            <p>You are not logged in. To access the reservations feature of the site, please log in using the option at the
                top right.</p>
        </div>
    <?php elseif ($isEmployee == 1): ?>
        <div class="content" style="margin-left:10px;">
            <h1>Employee View</h1><br>

            <h4>Spot Table</h4>
            <?php
            $query = "SELECT * FROM spots;";
            $result = mysqli_query($con, $query);

            if (!$result) {
                echo "Error executing query: " . mysqli_error($con);
            } else {
                echo "<table border='1'>";
                echo "<tr>
                    <th>ID</th>
                    <th>Lot Name</th>
                    <th>Boat ID</th>
                    <th>Owner ID</th>
                    <th>Is Occupied</th>
                    <th>Occupied Until</th>
                  </tr>";  // Table headers
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['lot_name']) . "</td>
                        <td>" . htmlspecialchars($row['boat_id']) . "</td>
                        <td>" . htmlspecialchars($row['owner_id']) . "</td>
                        <td>" . htmlspecialchars($row['is_occupied']) . "</td>
                        <td>" . htmlspecialchars($row['occupied_until']) . "</td>
                      </tr>";
                }
                echo "</table>";
            }
            ?>
            <br>
            <h4>Boats Table</h4>
            <?php
            $query = "SELECT * FROM boats;";
            $result = mysqli_query($con, $query);

            if (!$result) {
                echo "Error executing query: " . mysqli_error($con);
            } else {
                echo "<table border='1'>";
                echo "<tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>User ID</th>
                  </tr>";  // Table headers
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['size']) . "</td>
                        <td>" . htmlspecialchars($row['user_id']) . "</td>
                      </tr>";
                }
                echo "</table>";
            }
            ?>
            <br>
            <h4>Reservations Table</h4>
            <?php
            $query = "SELECT * FROM reservations;";
            $result = mysqli_query($con, $query);

            if (!$result) {
                echo "Error executing query: " . mysqli_error($con);
            } else {
                echo "<table border='1'>";
                echo "<tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Spot Requested</th>
                    <th>Date Requested</th>
                  </tr>";  // Table headers
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['spot_requested']) . "</td>
                        <td>" . htmlspecialchars($row['date_requested']) . "</td>
                      </tr>";
                }
                echo "</table>";
            }
            ?>
            <br>
        </div>
    <?php elseif ($isEmployee == 0): ?>
        <div class="content" style="margin-left:10px;">
            <h1>Customer View</h1>
            <form action="reservation_handler.php" method="POST">
                <div class="mb-3" style="width: 25%">
                    <label for="spot" class="form-label">Spot Number:</label>
                    <select id="spot" name="spot" class="form-select" required>
                        <option value="" disabled selected>Select a spot</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Request Spot</button>
            </form>
        </div>
    <?php endif; ?>



    <script>
        function requestSpot(spot) {
            const isAvailable = confirm(`Would you like to request ${spot}?`);
            if (isAvailable) {
                let requestemail = prompt('Enter your email address: (NOTE THIS DOESNT DO ANYTHING!!! CLicking this button should open a form somewhere on the screen)');

            }
        }
    </script>

</body>

</html>