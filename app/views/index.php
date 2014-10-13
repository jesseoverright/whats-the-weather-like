<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" lang="en" />
    <title>What's the Weather Like?</title>
    <meta name="description" content="What's the Weather Like?">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/main.css" />
    <script src="js/scripts.min.js"></script>


</head>
<body>
<div id="container" role="main">

    <form id="location-form" method="POST">

        <label for="location">What's the weather like in...</label>
        <input id="location" name="location" type="text" placeholder="City, STATE">
        <button type="submit">Submit</button>

    </form>

    <div class="weather"></div>

    <div class="comments"></div>

</div>
<footer role="footer">Weather data brought to you by <img src="img/wundergroundLogo_4c_horz.png" alt="weather underground"></footer>

</body>
</html>