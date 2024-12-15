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
                        $query = "SELECT id, email FROM users";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['email']) . "</option>";
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

// LOGIC FOR CLEARING / UPDATING A TABLE
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['action'])) {
                    if ($_POST['action'] === 'clear') {
                        // Clear spot logic
                        if (!empty($_POST['spot_id'])) {
                            $spot_id = intval($_POST['spot_id']);

                            // Fetch the lot name associated with the spot
                            $lot_query = "SELECT lot_name FROM spots WHERE id = $spot_id";
                            $lot_result = mysqli_query($conn, $lot_query);
                            $lot_row = mysqli_fetch_assoc($lot_result);

                            if ($lot_row) {
                                $lot_name = $lot_row['lot_name'];

                                // Clear the spot
                                $query = "UPDATE spots SET is_occupied = 0, owner_id = NULL, boat_id = NULL WHERE id = $spot_id";
                                if (mysqli_query($conn, $query)) {
                                    echo "<div class='alert alert-success'>Spot $spot_id has been cleared.</div>";

                                    // Fetch emails from reservations table for the cleared lot
                                    $email_query = "SELECT email FROM reservations WHERE lot_requested = '" . mysqli_real_escape_string($conn, $lot_name) . "'";
                                    $email_result = mysqli_query($conn, $email_query);

                                    if ($email_result) {
                                        $subject = "New Spot Available in $lot_name";
                                        $message = "A new spot has just become available in $lot_name. Our employees will get in touch to arrange payment. \n\nThank you for choosing Hawk Island Marina!";
                                        $headers = "From: SoftwareProject@hawkislandmarina.net";

                                        // Send email to each user
                                        while ($email_row = mysqli_fetch_assoc($email_result)) {
                                            $to = $email_row['email'];
                                            if (mail($to, $subject, $message, $headers)) {
                                                echo "<div class='alert alert-info'>Notification sent to $to.</div>";
                                            } else {
                                                echo "<div class='alert alert-warning'>Failed to send notification to $to.</div>";
                                            }
                                        }
                                    } else {
                                        echo "<div class='alert alert-warning'>No reservations found for lot $lot_name.</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Failed to clear the spot. Error: " . mysqli_error($conn) . "</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Lot not found for Spot ID $spot_id.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-warning'>Please select a spot to clear.</div>";
                        }
                    } elseif ($_POST['action'] === 'assign') {
                        // Assign user and boat to spot logic
                        if (!empty($_POST['assign_spot_id']) && !empty($_POST['owner_id']) && !empty($_POST['boat_id'])) {
                            $spot_id = intval($_POST['assign_spot_id']);
                            $owner_id = intval($_POST['owner_id']);
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
            <!-- INSPECTION TABLE------------------------------------------------------------------------------------------------->
            <h4>Inspection Records</h4>
            <?php
            $query = "
                SELECT 
                    inspection.inspection_id, 
                    boats.name AS boat_name, 
                    inspection.inspection_date, 
                    inspection.notes 
                FROM inspection
                INNER JOIN boats ON inspection.boat_id = boats.id
                ORDER BY inspection.inspection_date DESC
            ";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<div class='alert alert-danger'>Error fetching inspection records: " . mysqli_error($conn) . "</div>";
            } else {
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>
                        <tr>
                            <th>Inspection ID</th>
                            <th>Boat Name</th>
                            <th>Inspection Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['inspection_id']) . "</td>
                            <td>" . htmlspecialchars($row['boat_name']) . "</td>
                            <td>" . htmlspecialchars($row['inspection_date']) . "</td>
                            <td>" . htmlspecialchars($row['notes']) . "</td>
                        </tr>";
                }
                echo "</tbody></table>";
            }
            // Display the message above the table
            if (!empty($message)) {
                echo $message;
            }
            ?>

            <form action="" method="POST" class="mt-4">
                <h4>Add Inspection Notes</h4>
                <div class="mb-3">
                    <label for="boat_id" class="form-label">Boat ID:</label>
                    <select id="boat_id" name="boat_id" class="form-select" required>
                        <option value="" disabled selected>Select a boat</option>
                        <?php
                        // Fetch all boats for the dropdown
                        $query = "SELECT id, name FROM boats";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>Boat " . htmlspecialchars($row['name']) . " (ID: " . htmlspecialchars($row['id']) . ")</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="inspection_date" class="form-label">Inspection Date:</label>
                    <input type="date" id="inspection_date" name="inspection_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Inspection Notes:</label>
                    <textarea id="notes" name="notes" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" name="action" value="add_inspection" class="btn btn-primary">Add Inspection Notes</button>
            </form>

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
            <!-- PRICING UPDATE TABLE------------------------------------------------------------------------------------------------->

            <h4>Service Pricing</h4>
            <?php
            $query = "SELECT service_name, price FROM services ORDER BY service_name";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<div class='alert alert-danger'>Error fetching services: " . mysqli_error($conn) . "</div>";
            } else {
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>
            <tr>
                <th>Service</th>
                <th>Price</th>
            </tr>
          </thead>
          <tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                <td>" . htmlspecialchars($row['service_name']) . "</td>
                <td>$" . htmlspecialchars($row['price']) . "</td>
              </tr>";
                }
                echo "</tbody></table>";
            }

            // Display the message if it exists
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']); // Clear the message after displaying it
            }
            ?>

            <h4>Update Service Pricing</h4>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="service_id" class="form-label">Service:</label>
                    <select id="service_id" name="service_id" class="form-select" required>
                        <option value="" disabled selected>Select a service</option>
                        <?php
                        // Fetch all services for the dropdown
                        $query = "SELECT service_id, service_name, price FROM services";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['service_id']) . "'>" . htmlspecialchars($row['service_name']) . " (Current Price: $" . htmlspecialchars($row['price']) . ")</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="new_price" class="form-label">New Price:</label>
                    <input type="number" id="new_price" name="new_price" class="form-control" step="0.01" min="0" required>
                </div>
                <button type="submit" name="action" value="update_service_price" class="btn btn-primary">Update Price</button>
            </form>

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
            <!-- Print Map button directly below Request Spot -->
            <div class="mt-3">
                <button onclick="printMap()" class="btn btn-primary">Print Map</button>
            </div>
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
    <script>
        function printMap() {
            const mapContainer = document.getElementById('marina-map');
            const clonedMap = mapContainer.cloneNode(true); // Clone the map container

            // Open a new window for printing
            const printWindow = window.open('', '_blank', 'width=908,height=593');
            printWindow.document.write(`
            <html>
                <head>
                    <title>Print Map</title>
                    <style>
                        body {
                            margin: 0;
                            padding: 0;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100%;
                        }
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
                </body>
            </html>
        `);

            // Append the cloned map container to the new window's body
            printWindow.document.body.appendChild(clonedMap);

            // Trigger the print dialog after the content has loaded
            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
</body>

</html>