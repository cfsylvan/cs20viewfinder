<?php 
    session_start();
    $_SESSION["username"];
    
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

    $conn->select_db($db);
    $sql = "SELECT poster_url FROM movies 
    JOIN favorites ON movies.movie_id = favorites.movie_id
    JOIN users ON users.user_id = favorites.user_id 
    WHERE users.username = '$username'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<img src= '" . $row['poster_url'] . "'>";
?>