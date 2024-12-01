<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Check if the user is logged in
$isEmployee = ($_SESSION['is_employee']);

include "db_connection.php";

$query = "SELECT * FROM test;";
$result = mysqli_query($conn, $query);

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
        <div class="content" style="margin-left:10px; width:50%">


            <h1>Employee View</h1><br>

            <h4>Spot Table</h4>

            <!-- Dropdown Form to Switch Lots -->
            <form method="GET" class="mb-3">
                <label for="lot_name" class="form-label">Select Lot:</label>
                <select name="lot_name" id="lot_name" class="form-select" onchange="this.form.submit()">
                    <option value="LOT A" <?php echo (isset($_GET['lot_name']) && $_GET['lot_name'] == 'LOT A') ? 'selected' : ''; ?>>LOT A</option>
                    <option value="LOT B" <?php echo (isset($_GET['lot_name']) && $_GET['lot_name'] == 'LOT B') ? 'selected' : ''; ?>>LOT B</option>
                    <option value="LOT C" <?php echo (isset($_GET['lot_name']) && $_GET['lot_name'] == 'LOT C') ? 'selected' : ''; ?>>LOT C</option>
                    <option value="LOT D" <?php echo (isset($_GET['lot_name']) && $_GET['lot_name'] == 'LOT D') ? 'selected' : ''; ?>>LOT D</option>
                </select>
            </form>

            <?php
            // Default to LOT A if no lot is selected
            $selected_lot = $_GET['lot_name'] ?? 'LOT A';

            // SQL Query to Fetch Data Based on Selected Lot
            $query = "
    SELECT 
        spots.id AS spot_id,
        spots.lot_name,
        spots.is_occupied,
        users.name AS owner_name,
        boats.name AS boat_name
    FROM spots
    LEFT JOIN users ON spots.owner_id = users.id
    LEFT JOIN boats ON spots.boat_id = boats.id
    WHERE spots.lot_name = '" . mysqli_real_escape_string($conn, $selected_lot) . "';
";

            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<div class='alert alert-danger'>Error executing query: " . mysqli_error($conn) . "</div>";
            } else {
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                echo "<tr>
            <th>Spot ID</th>
            <th>Lot Name</th>
            <th>Is Occupied</th>
            <th>Owner Name</th>
            <th>Boat Name</th>
        </tr>";
                echo "</thead><tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                <td>" . htmlspecialchars($row['spot_id']) . "</td>
                <td>" . htmlspecialchars($row['lot_name']) . "</td>
                <td>" . ($row['is_occupied'] ? 'Yes' : 'No') . "</td>
                <td>" . htmlspecialchars($row['owner_name'] ?? 'N/A') . "</td>
                <td>" . htmlspecialchars($row['boat_name'] ?? 'N/A') . "</td>
            </tr>";
                }
                echo "</tbody></table>";
            }
            ?>
            <form action="" method="POST">
                <h4>Clear Spot</h4>
                <div class="mb-3" style="width: 25%;">
                    <label for="spot_id" class="form-label">Spot ID:</label>
                    <select id="spot_id" name="spot_id" class="form-select">
                        <option value="" disabled selected>Select a spot</option>
                        <?php
                        // Fetch all occupied spot IDs
                        $query = "SELECT id FROM spots WHERE is_occupied = 1";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['id']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="action" value="clear" class="btn btn-warning">Clear Spot</button>

                <hr>

                <h4>Assign User and Boat to a Spot</h4>
                <div class="mb-3" style="width: 25%;">
                    <label for="assign_spot_id" class="form-label">Spot ID:</label>
                    <select id="assign_spot_id" name="assign_spot_id" class="form-select">
                        <option value="" disabled selected>Select a spot</option>
                        <?php
                        // Fetch all unoccupied spot IDs
                        $query = "SELECT id FROM spots WHERE is_occupied = 0";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['id']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3" style="width: 25%;">
                    <label for="owner_id" class="form-label">Owner ID:</label>
                    <select id="owner_id" name="owner_id" class="form-select" onchange="updateBoats()">
                        <option value="" disabled selected>Select an owner</option>
                        <?php
                        // Fetch all user IDs
                        $query = "SELECT id, name FROM users";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3" style="width: 25%;">
                    <label for="boat_id" class="form-label">Boat ID:</label>
                    <select id="boat_id" name="boat_id" class="form-select">
                        <option value="" disabled selected>Select a boat</option>
                        <!-- Options will be dynamically loaded -->
                    </select>
                </div>

                <button type="submit" name="action" value="assign" class="btn btn-primary">Assign Spot</button>
            </form>

            <?php

            //LOGIC FOR CLEARING / UPDATING A TABLE
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['action'])) {
                    if ($_POST['action'] === 'clear') {
                        // Clear spot logic
                        if (!empty($_POST['spot_id'])) {
                            $spot_id = intval($_POST['spot_id']);
                            $query = "UPDATE spots SET is_occupied = 0, owner_id = NULL, boat_id = NULL WHERE id = $spot_id";
                            if (mysqli_query($conn, $query)) {
                                echo "<div class='alert alert-success'>Spot $spot_id has been cleared.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Failed to clear the spot. Error: " . mysqli_error($conn) . "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-warning'>Please select a spot to clear.</div>";
                        }
                    } elseif ($_POST['action'] === 'assign') {
                        // Assign user and boat to spot logic
                        if (!empty($_POST['assign_spot_id']) && !empty($_POST['owner_id']) && !empty($_POST['boat_id'])) {
                            $spot_id = intval($_POST['assign_spot_id']);
                            $owner_id = intval($_POST['owner_id']); // Use owner_id here
                            $boat_id = intval($_POST['boat_id']);
                            $query = "UPDATE spots SET is_occupied = 1, owner_id = $owner_id, boat_id = $boat_id WHERE id = $spot_id";
                            if (mysqli_query($conn, $query)) {
                                echo "<div class='alert alert-success'>Spot $spot_id has been assigned to Owner $owner_id with Boat $boat_id.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Failed to assign the spot. Error: " . mysqli_error($conn) . "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-warning'>Please fill out all fields to assign a spot.</div>";
                        }
                    }

                }
            }
            ?>


            <br>
            <!-- SPOT TABLE END------------------------------------------------------------------------------------------------->

        <h4>Boats Table</h4>
        <!-- BOAT TABLE ------------------------------------------------------------------------------------------------->

        <?php
        $query = "
        SELECT 
            boats.id AS boat_id,
            boats.name as boat_name,
            boats.size as size,
            users.email AS owner_name
        FROM boats
        LEFT JOIN users ON boats.user_id = users.id
            
            ";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "Error executing query: " . mysqli_error($conn);
        } else {
            echo "<table class='table table-bordered table-striped'>";
            echo "<tr>
                    <th>ID</th>
                    <th>Boat Name</th>
                    <th>Size</th>
                    <th>Owner</th>
                  </tr>";  // Table headers
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['boat_id']) . "</td>
                        <td>" . htmlspecialchars($row['boat_name']) . "</td>
                        <td>" . htmlspecialchars($row['size']) . "</td>
                        <td>" . htmlspecialchars($row['owner_name']) . "</td>
                      </tr>";
            }
            echo "</table>";
        }
        ?>
        <br>
        <!-- BOAT TABLE END------------------------------------------------------------------------------------------------->
        <!-- RESERVATIONS TABLE ------------------------------------------------------------------------------------------------->

        <h4>Reservations Table</h4>
        <?php
        $query = "SELECT * FROM reservations;";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "Error executing query: " . mysqli_error($conn);
        } else {
            echo "<table class='table table-bordered table-striped'>";
            echo "<tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Spot Requested</th>
                    <th>Date Requested</th>
                  </tr>";
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
        <!-- RESERVATIONS TABLE END------------------------------------------------------------------------------------------------->
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

        function updateBoats() {
            const ownerId = document.getElementById('owner_id').value;
            const boatSelect = document.getElementById('boat_id');

            // Clear existing options
            boatSelect.innerHTML = '<option value="" disabled selected>Select a boat</option>';

            if (ownerId) {
                fetch(`fetch_boats.php?owner_id=${ownerId}`)
                    .then(response => response.json())
                    .then(boats => {
                        // Populate Boat ID dropdown
                        boats.forEach(boat => {
                            const option = document.createElement('option');
                            option.value = boat.id;
                            option.textContent = boat.name;
                            boatSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching boats:', error);
                    });
            }
        }
    </script>

</body>

</html>