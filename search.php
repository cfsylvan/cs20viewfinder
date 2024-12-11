<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <main>
        <?php
        session_start();

        // Set variables if users input something, set an empty string if not
        $title = isset($_GET['title']) ? $_GET['title'] : "";
        $genre = isset($_GET['genre']) ? $_GET['genre'] : "";
        $year = isset($_GET['year']) ? $_GET['year'] : "";
        $director = isset($_GET['director']) ? $_GET['director'] : "";

        $apiKey = 'fa4ca29';  
        $baseUrl = "http://www.omdbapi.com/";

        // Make the base URL
        $url = "$baseUrl?apikey=$apiKey";

        // If title and/or year are filled, add them to the URL
        if ($title != "") {
            $url = $url . "&s=" . urlencode($title);
        }
        if ($year != "") {
            $url = $url . "&y=" . urlencode($year);
        }

        // Fetch data from OMDb API
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        // Sift through the results to search by genre/director
        $results = $data['Search'];
        $filteredResults = [];

        foreach ($results as $movie) {
            // Get each movie's IMDB code, query the API again to fetch movie details
            $movieDetailsUrl = $baseUrl . "?apikey=$apiKey&i=" . $movie['imdbID'];
            $movieDetailsResponse = file_get_contents($movieDetailsUrl);
            $movieDetails = json_decode($movieDetailsResponse, true);

            // If a genre or director is inputted, use the movieDetails to see if each result matches the user's input
            if ($genre != "" || $director != "") {
                $matchesGenre = $genre == "" ? true : false;
                $genresFound = explode(",", $movieDetails['Genre']);
                foreach ($genresFound as $item) {
                    if (trim(strtolower($genre)) == trim(strtolower($item))) {
                        $matchesGenre = true;
                        break;
                    }
                }

                $matchesDirector = $director == "" ? true : false;
                $directorFound = explode(" ", $movieDetails['Director']);
                foreach ($directorFound as $item) {
                    if (trim(strtolower($director)) == trim(strtolower($item))) {
                        $matchesDirector = true;
                        break;
                    }
                }

                if ($matchesGenre && $matchesDirector) {
                    $filteredResults[] = $movieDetails;
                }
            } else {
                $filteredResults[] = $movieDetails;
            }
        }

        $results = $filteredResults;

        // Include the search form at the top of the page
        include "searchForm.php";

        // If there are no results, print an error. If there are results, print them
        if (!empty($results)) {
            echo "<ul>";
            foreach ($results as $movie) {
                // Ensure movie data exists before rendering
                if (!empty($movie['Title']) && !empty($movie['Poster']) && !empty($movie['Plot'])) {
                    echo "<li>";
                    echo "<img src='" . htmlspecialchars($movie['Poster']) . "' alt='" . htmlspecialchars($movie['Title']) . "'>";
                    echo "<strong>" . htmlspecialchars($movie['Title']) . " (" . htmlspecialchars($movie['Year']) . ")</strong>";
                    echo "<p>" . htmlspecialchars($movie['Plot']) . "</p>";
                    echo "<form action='add_favorite.php' method='GET'> 
                        <input type='hidden' name='Title' value='" . htmlspecialchars($movie['Title']) . "'>
                        <input type='hidden' name='Year' value='" . htmlspecialchars($movie['Year']) . "'>
                        <input type='hidden' name='imdbID' value='" . htmlspecialchars($movie['imdbID']) . "'>
                        <input type='hidden' name='Poster' value='" . htmlspecialchars($movie['Poster']) . "'>
                        <input type='hidden' name='Plot' value='" . htmlspecialchars($movie['Plot']) . "'>
                        <input type='submit' name='addFavorites' value='Add to Favorites'>
                    </form>";
                    echo "</li>";
                }
            }
            echo "</ul>";
        } else {
            echo "<p>No results found. Please try again.</p>";
        }        
        ?>
    </main>
</body>
</html>

