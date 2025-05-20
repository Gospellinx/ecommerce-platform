<?php
session_start(); 

if (isset($_GET['city'])) {
    $city = htmlspecialchars($_GET['city']);
    $apiKey = '0f562c7a01a13207b070875583ad0b7a'; 
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

    // Check if data exists in session cache
    if (isset($_SESSION['weather_data'][$city]) && (time() - $_SESSION['weather_data'][$city]['timestamp']) < 300) {
        $weatherData = $_SESSION['weather_data'][$city]['data']; 
    } else {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Fix SSL issue: Use a proper CA certificate
        $cacertPath = __DIR__ . '/cacert.pem'; // Ensure you have downloaded cacert.pem
        if (file_exists($cacertPath)) {
            curl_setopt($ch, CURLOPT_CAINFO, $cacertPath);
        } else {
            // If the certificate file is missing, disable SSL verification (not recommended for production)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            die("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);
        $weatherData = json_decode($response, true);

        // Cache data for 5 minutes (300 seconds)
        if ($weatherData['cod'] == 200) {
            $_SESSION['weather_data'][$city] = [
                'data' => $weatherData,
                'timestamp' => time()
            ];
        }
    }

    // Display results
    if ($weatherData['cod'] == 200) {
        $icon = "http://openweathermap.org/img/wn/" . $weatherData['weather'][0]['icon'] . "@2x.png";
        echo "<div class='weather-container'>";
        echo "<h2>Weather in " . $weatherData['name'] . "</h2>";
        echo "<img src='$icon' alt='Weather icon'>";
        echo "<p><strong>Temperature:</strong> " . $weatherData['main']['temp'] . "°C</p>";
        echo "<p><strong>Weather:</strong> " . ucfirst($weatherData['weather'][0]['description']) . "</p>";
        echo "<p><strong>Humidity:</strong> " . $weatherData['main']['humidity'] . "%</p>";
        echo "<p><strong>Wind Speed:</strong> " . $weatherData['wind']['speed'] . " m/s</p>";
        echo "<a href='index.php' class='back-button'>Search Another City</a>";
        echo "</div>";
    } else {
        echo "<h3>City not found. Please try again.</h3>";
    }
} else {
    echo "<h3>Please enter a city name.</h3>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #74ebd5, #acb6e5);
    text-align: center;
    padding: 20px;
}

.weather-container {
    background: white;
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

img {
    width: 100px;
    margin: 10px 0;
}

p {
    font-size: 18px;
}

.back-button {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.back-button:hover {
    background: #0056b3;
}
</style>
