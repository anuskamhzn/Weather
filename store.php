<?php

$city = $_GET["city"];
$conn = mysqli_connect("localhost","root","","weather");
//fetch from api
$json_data = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=" .$city ."&units=metric&appid=b163dad8770ca1d50d3d6dbf9019971e");
//convert into json format
$data = json_decode($json_data,true);
//access the data 
$city = $data['name'];
$temp = $data['main']['temp'];
$humidity = $data['main']['humidity'];
$wind_speed =$data['wind']['speed'];
$pressure = $data['main']['pressure'];
$timestamp = $data['dt'];
$icon=$data['weather'][0]['icon'];
$condition=$data['weather'][0]['description'];
$date = gmdate("Y-m-d", $timestamp);

// Check if data for the selected date already exists in the database
$sqlCheck = "SELECT * FROM weather WHERE date = '$date' and city='$city'";
$resultCheck = mysqli_query($conn, $sqlCheck);

if (mysqli_num_rows($resultCheck) > 0) {
    // Data already exists for the selected date, update the existing data
    $sqlUpdate = "UPDATE weather SET city = '$city', temperature = '$temp', humidity = '$humidity', pressure = '$pressure', wind_speed = '$wind_speed', icon = '$icon', weather_condition = '$condition' WHERE date = '$date' and city='$city'";
    mysqli_query($conn, $sqlUpdate);
    echo "Data updated successfully";
} else {
    // Data doesn't exist for the selected date, insert a new record
    $sqlInsert = "INSERT INTO weather (city, temperature, humidity, pressure, wind_speed, date, icon, weather_condition) VALUES ('$city', '$temp', '$humidity', '$pressure', '$wind_speed', '$date', '$icon', '$condition')";
    mysqli_query($conn, $sqlInsert);
    echo "Data inserted successfully";
}
?>
