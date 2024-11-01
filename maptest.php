<?php
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
            font-size: 14px;
            color: white;
            cursor: pointer;
        }

        .open { background-color: green; }
        .closed { background-color: lightgray; border: 2px solid red; color: darkred; }
    </style>
</head>
<body>

<h2>Marina Spot Availability</h2>
<div class="map-container" id="marina-map">
    <!-- Positioned spots on the marina map -->
    <!-- Creates a div for the styled spot, which is just a circle. Checks the created list and echoes the number (hardcoded). 
     If the spot is open, then allow an onclick request which goes to the function requestspot below.-->
    <div class="spot <?php echo $spotOccupy[0]; ?>" style="top: 340px; left: 270px;" <?php if ($spotOccupy[0] == "open") { echo 'onclick="requestSpot(\'Spot 1\')"'; } ?>>Spot 1</div>
    <div class="spot <?php echo $spotOccupy[1]; ?>" style="top: 120px; left: 350px;" <?php if ($spotOccupy[1] == "open") { echo 'onclick="requestSpot(\'Spot 2\')"'; } ?>>Spot 2</div>
    <div class="spot <?php echo $spotOccupy[2]; ?>" style="top: 80px; left: 500px;" <?php if ($spotOccupy[2] == "open") { echo 'onclick="requestSpot(\'Spot 3\')"'; } ?>>Spot 3</div>
    <div class="spot <?php echo $spotOccupy[3]; ?>" style="top: 200px; left: 730px;" <?php if ($spotOccupy[3] == "open") { echo 'onclick="requestSpot(\'Spot 4\')"'; } ?>>Spot 4</div>
</div>

<script>
function requestSpot(spot) {
    const isAvailable = confirm(`Would you like to request ${spot}?`);
    if (isAvailable) {
        alert(`Request for ${spot} submitted.`);
    }
}
</script>

</body>
</html>