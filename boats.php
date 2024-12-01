<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Check if the user is logged in
$isEmployee = ($_SESSION['is_employee']);

include "db_connection.php";
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Marina Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

    <?php if (!$isLoggedIn): ?>
        <div class="content" style="margin-left:10px;">
            <h1>Not Logged in!</h1>
            <p>You are not logged in. To register a boat, please log in using the buttons at the top right!</p>
        </div>
    <?php elseif ($isEmployee == 1): ?>
        <div class="content" style="margin-left:10px; width:50%">
            <h4>Boats Table</h4>
            <!-- BOAT TABLE ------------------------------------------------------------------------------------------------->

        <?php
        $query = "
        SELECT 
            boats.id AS boat_id,
            boats.name AS boat_name,
            boats.size AS size,
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
                  </tr>";
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

    </div>
    <?php elseif ($isEmployee == 0): ?>
    <div class="content" style="margin-left:10px;">
        <h1>Customer View</h1>

        <?php
        // Handle form submission for registering a boat
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $boat_name = $_POST['name'];
            $boat_size = $_POST['size'];
            $user_id = $_SESSION['id'];
    
            if (!empty($boat_name) && !empty($boat_size)) {
                $query = "INSERT INTO boats (name, size, user_id) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $boat_name, $boat_size, $user_id);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Your boat has been successfully registered!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
                }

                $stmt->close();
            } else {
                echo "<div class='alert alert-warning'>Please fill out all fields.</div>";
            }
        }
        ?>

        <!-- Form for registering a boat -->
            <form action="" method="POST">
                <div class="mb-3" style="width: 50%;">
                    <label for="name" class="form-label">Boat Name:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter boat name"
                        required>
                </div>

                <div class="mb-3" style="width: 25%;">
                    <label for="size" class="form-label">Boat Size:</label>
                    <select id="size" name="size" class="form-select" required>
                        <option value="" disabled selected>Select a size class</option>
                        <option value="Class1">Class 1 (Small)</option>
                        <option value="Class2">Class 2 (Medium)</option>
                        <option value="Class3">Class 3 (Large)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Register Boat</button>
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