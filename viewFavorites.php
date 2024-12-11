<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Favorites</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
    session_start();
    $username = $_SESSION['username'];
    
    $db_server = "localhost";
    $db_userid = "uqmwlannrmfwn"; 
    $db_pw = "cs20final2"; 
    $db= "db2kfob4fzh8gt"; 

    //let user search for another movie
    echo 
    "<form action='searchForm.php' method='GET'>
        <input type='submit' value='Search for another Movie'>
    </form>";

    // Create connection
    $conn = new mysqli($db_server, $db_userid, $db_pw, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //make the query, select movies from current user
    $sql = "SELECT title, year, plot, poster_url FROM movies WHERE username = '$username'";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        extract($row);
        //display info
        echo "<img src='$poster_url'><br>";
        echo $title . "<br>". $year. "<br>". $plot. "<br>";
    } 
    $conn->close();
    ?>
</body>
</html>
