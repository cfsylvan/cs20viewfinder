<?php 
    $username = $_REQUEST['username'];
    
    $title = $_REQUEST['title'];
    $director = $_REQUEST['director'];
    $year = $_REQUEST['year'];
    $plot = $_REQUEST['plot'];
    $poster_url = $_REQUEST['poster'];
    
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

    $sql = "INSERT INTO Movies (username, title, director, year, plot, poster_url) 
        VALUES ('$username', '$title', '$director', $year, '$plot', '$poster_url')";

    $conn->query($sql);
    $conn->close();
?>