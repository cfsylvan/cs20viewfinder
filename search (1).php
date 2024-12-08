<?php
session_start();

// set variables if users input something, set an empty string if not
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

// sift through the results to search by genre/director
$results = $data['Search'];
$filteredResults = [];

foreach ($results as $movie) {
    // get each movie's IMDB code, query the api again to fetch movie details
    $movieDetailsUrl = $baseUrl . "?apikey=$apiKey&i=" . $movie['imdbID'];
    $movieDetailsResponse = file_get_contents($movieDetailsUrl);
    $movieDetails = json_decode($movieDetailsResponse, true);

    //if a genre or director is inputted, use the movieDetails to see if each result matches the user's input
    if ($genre != "" || $director != "") {
            if ($genre == ""){
                $matchesGenre = true;
            } else {
                $matchesGenre = false;
                $genresFound = explode(",", $movieDetails['Genre']);
                foreach ($genresFound as $item){
                    if (trim(strtolower($genre)) == trim(strtolower($item))){
                        $matchesGenre = true;
                        break;
                    }
                }
            }
            
            //check to see if user input director is in imdb
            if ($director == ""){
                $matchesDirector = true;
            } else {
                $matchesDirector= false;
                $directorFound = explode(" ", $movieDetails['Director']);
                foreach ($directorFound as $item){
                    if (trim(strtolower($director)) == trim(strtolower($item))){
                        $matchesDirector = true;
                        break;
                    }
                }
            }
            
            if ($matchesGenre && $matchesDirector) {
                //add to filtered results array
                $filteredResults[] = $movieDetails;
            }
        } 
        else {
            //if a user didn't input any criteria, automatically add the movie to the filtered results
            $filteredResults[] = $movieDetails;
        } 
    
    
} 
$results = $filteredResults;

echo "<h1>ViewFinder</h1>";
//include the search form at the top of the page
include "searchForm.php";
//if there are no results, print an error. if there are results, print them
if (empty($results)){
    echo "No results found. Please try again.";
} else {
    echo "<ul>";
    foreach ($results as $movie) {
        $title = $movie['Title'];
        $year = $movie['Year'];
        $imdbID = $movie['imdbID'];
        echo "<li><strong>$title</strong> ($year) -";
        //print a 'view details' page and put the imdb ID in the url so that details can be printed on a separate page
        echo "<img src='" . $movie['Poster'] . "'>";
        echo $movie['Plot']. "</li>";
        echo "<form action='add_favorite.php' method='GET'> <input type='submit' name='addFavorites' value='Add to Favorites'>";
        }
        echo "</ul>";
}
?>