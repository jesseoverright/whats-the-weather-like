<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" lang="en" />
    <title>Is it hot?</title>

    <link rel="stylesheet" href="css/main.css" />

    <!-- needs minify -->
    <script src="src/js/jquery-1.11.1.min.js"></script>
    <script src="src/js/scripts.js"></script>
</head>
<body>

<form id="location-form" method="POST">

    <label for="location">What's the weather like in...</label>
    <input id="location" name="location" type="text" placeholder="City, STATE">
    <button type="submit">Submit</button>

</form>

<div class="weather"></div>

<div class="comments"></div>

<footer>Weather data brought to you by <img src="src/img/wundergroundLogo_4c_horz.png" alt="weather underground"></footer>
    
</body>
</html>