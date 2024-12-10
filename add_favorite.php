<?php 
    session_start();
    
    $username = $_SESSION['username'];
    
    $title = $_GET['Title'];
    $year = $_GET['Year'];
    $imdbID = $_GET['imdbID'];
    $poster_url = $_GET['Poster'];
    $plot = $_GET['Plot'];
    $db_server = "localhost";// your server
    $db_userid = "uqmwlannrmfwn"; // your user id
    $db_pw = "cs20final2"; // your pw
    $db= "db2kfob4fzh8gt"; // your database

    // Create connection
    $conn = new mysqli($db_server, $db_userid, $db_pw, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //check if movie is a duplicate favorite
    $check_duplicate ="SELECT * FROM movies WHERE imdbID = '" . $imdbID . "' and username = '" . $username . "'";
    $result = $conn->query($check_duplicate);
    //get result
    $row = $result->fetch_assoc();
    $duplicate = ($row != null);

    if ($duplicate){
        echo "<h1>This movie is already added to your Favorites. </h1>";
    } else {
        // Use a prepare so movies with ' or " can still be inserted into the sql.
        $s = $conn->prepare("INSERT INTO movies (username, title, year, plot, poster_url, imdbID) 
        VALUES (?, ?, ?, ?, ?, ?)");
    
        $s->bind_param("ssisss", $username, $title, $year, $plot, $poster_url, $imdbID);
        $s->execute();
        $s->close();
        $conn->close();
    
        echo "<h1>Movie successfully added to your Favorites!</h1>";
    }
    echo "<button id=viewFavorites>View Favorites</button>";
    echo "<button id=searchAgain>Search for Another Movie</button>";
        
    //process buttons
    echo 
    "<script type='text/javascript'>   
        document.getElementById('viewFavorites').onclick = function () {
        location.href = '/final/viewFavorites.php';
        };

        document.getElementById('searchAgain').onclick = function () {
        location.href = '/final/searchForm.php';
        };
    </script>";

?>
