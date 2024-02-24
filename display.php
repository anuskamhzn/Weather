<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="Styl.css">
    <link rel="icon" type="image/x-icon"
        href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScrXCugIz7TBAwgZYIZrj0Ggdiw-qsJGkXxibR8xA&s">
</head>

<body>

    <div class="background-image" data-weather=""></div>
    <!-- background-image -->
    <form method="post">
        <div class="weekly-buttons">
            <button name="selected_date" value="<?php echo date('Y-m-d'); ?>">Today</button>
            <?php
            // Generate buttons for the last 6 days (excluding today)
            for ($i = 1; $i <= 6; $i++) {
                $date = date('Y-m-d', strtotime("-$i day"));
                echo "<button name='selected_date' value='$date'>" . date('l', strtotime("-$i day")) . "</button>";
            }
            ?>
        </div>
    </form>
    <!-- form -->

</body>

</html>
<!-- -------------------------------------------------------------------------------------------------------------- -->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weather";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Error";
}

// Bring back the selected date from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_date = $_POST["selected_date"];
} else {
    $selected_date = date("Y-m-d"); // Set today's date as default
}

// Retrieve the city from the query string
if (isset($_GET["city"])) {
    $city = $_GET["city"];
} else {
    $city = ""; // Set an empty value if the city parameter is not provided
}

// Query the database for the weather information for the last 7 days
$sql = "SELECT * FROM weather WHERE city='$city' and date BETWEEN DATE_SUB('$selected_date', INTERVAL 7 DAY) AND '$selected_date' ORDER BY date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch and display the weather data for each day
    while ($row = $result->fetch_assoc()) {
        $location = $row["city"];
        $icon = $row["icon"];
        $temp = $row["temperature"];
        $pressure = $row['pressure'];
        $wind_speed = $row["wind_speed"];
        $humidity = $row["humidity"];
        $condition = $row["weather_condition"];
        $date = $row["date"];

        // Display the weather information for each day
        echo "<div class='container'>";
        echo "<h1 class='location'>$location</h1>";
        echo "<div class='weather'>";
        echo "<div class='icon'><img src='https://openweathermap.org/img/w/$icon.png' alt='weather icon'></div>";
        echo "<div class='temp'>" . round($temp) . "&deg;C</div>";
        echo "<div class='condition'>$condition</div>";
        echo "</div>";
        echo "<p id='date'>DATE: $date</p>";
        echo "</div>";

        echo "<div class='wind'>";
        echo "<p>Wind Speed:</p>";
        echo "<p class='speed'>$wind_speed</p>";
        echo "</div>";
        echo "<div class='humidity'>";
        echo "<p>Humidity:</p>";
        echo "<p class='humi'>$humidity%</p>";
        echo "</div>";
        echo "<div class='humidity'>";
        echo "<p>Pressure:</p>";
        echo "<p class='press'>$pressure hPa</p>";
        echo "</div>";


    }
} else {
    echo "No results";
}

// Close the database connection
$conn->close();
?>