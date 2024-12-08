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
            <h1>ViewFinder</h1>
            <div class="username-display">
                <?php
                    if (isset($_POST["username"]) && !empty($_POST["username"])) {
                        echo "USERNAME: " . htmlspecialchars($_POST["username"]);
                    } else {
                        echo "No username provided.";
                    }
                ?>
            </div>
        </header>

        <!-- Search Form -->
        <form id="searchForm" action="search.php" method="GET">
            <h2>Search for a Movie</h2>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" placeholder="Movie Title">

            <label for="genre">Genre:</label>
            <input type="text" name="genre" id="genre" placeholder="Genre">

            <label for="director">Director:</label>
            <input type="text" name="director" id="director" placeholder="Director">

            <label for="year">Year:</label>
            <input type="text" name="year" id="year" placeholder="Year">

            <button type="submit">Search</button>
        </form>

        <!-- Results Section -->
        <div class="results-section">
            <h2>Results</h2>
            <div id="results">
                <!-- Movie cards will be dynamically populated by `search.php` -->
            </div>
        </div>
    </div>
</body>
</html>

