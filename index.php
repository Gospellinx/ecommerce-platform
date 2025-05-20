<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🌤 Weather Dashboard</h2>
        <form action="weather.php" method="GET">
            <label for="city">Enter City Name:</label>
            <input type="text" id="city" name="city" placeholder="e.g. Lagos" required>
            <button type="submit">Get Weather</button>
            <!-- i had to update this shit -->
        </form>
    </div>
</body>
</html>
