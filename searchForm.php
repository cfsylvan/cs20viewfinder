<?php 
    session_start();
    $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Movies</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="logo.png" alt="Logo">
            <div class="username-display">
                <?php
                    echo "USERNAME: ". $username;
                ?>
            </div>
        </header>

        <!-- Search Form -->
        <div class="search-form">
            <form id="searchForm" action="search.php" method="GET" onsubmit="return validateTitle()">
            <h2>Search for a Movie</h2>
            <label for="title">Title: (required) </label>
            <input type="text" name="title" id="title" placeholder="Movie Title" required>

            <label for="genre">Genre:</label>
            <input type="text" name="genre" id="genre" placeholder="Genre">

            <label for="director">Director:</label>
            <input type="text" name="director" id="director" placeholder="Director">

            <label for="year">Year:</label>
            <input type="text" name="year" id="year" placeholder="Year">

            <button type="submit">Search</button>
        </form>

        <!-- using another form so users can view favorites, setting action to a separate page that will display faves -->

        <div class="view-favorites-container">
        <form id="viewFavorites" action="viewFavorites.php" method="GET">
            <input type="submit" value="View Favorites">
        </form>
    </div>
</div>
        <!-- Results Section -->
        <div class="results-section">
            <h2>Results</h2>
            <div id="results">
                <!-- Movie cards will be dynamically populated by `search.php` -->
            </div>
        </div>
        <div id="shortTitle"></div>
    </div>

    <!--check if title is too short -->
    <script>
        function validateTitle(){
            let title = document.getElementById("title").value;
            if (title.length < 3){
                document.getElementById("shortTitle").innerHTML = "Please enter 3 or more characters in the title field.";
                return false;
            } else {
                document.getElementById("shortTitle").innerHTML = "";
                return true;
            }
        }
    </script>
</body>
</html>
