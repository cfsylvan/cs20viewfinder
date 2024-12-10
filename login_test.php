<?php
    session_start();
    $logged_in = false;
    $not_unique = false; 
    $not_exist = false;

    if ($_SERVER["REQUEST_METHOD"] == 'POST'){
        //get information from the form
        $new = $_POST["new"];
        $username = $_POST["username"];
        $_SESSION["username"] = $username;

        //establish connection info
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
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
       
        if ($new == "y"){
            //if they are a new user, check if their username is unique
            if ($row != null){
                $not_unique = true;
            } else {
                //create the user
                $sql = "INSERT INTO users (username) VALUES ('$username')";
                $result = $conn->query($sql);

                //login successful
                $logged_in = true;
            }
        } else if ($new == "n"){
            //if they are a returning user, check if their username exists
            if ($row == null){
                $not_exist = true;
            } else{
                //login successful
                $logged_in = true;
            }
        }
    }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login Form</title>
<style type="text/css">
    .error {
        color: red;
    }

    #too-long{ 
        display: none;
    }
</style>
<script>
    function validate(){
        username = document.getElementById('username').value;
        if (username.length > 10){
            document.getElementById('too-long').style.display = 'block';
            return false;
        } else{
            return true;
        }
    }
</script>
</head>
<body>


	<h1>ViewFinder</h1>
    
    <?php if ($not_unique):?>
        <p class='error'>This username is taken. Please try another.</p>
    <?php endif;?>
    <?php if ($not_exist):?>
        <p class='error'>This username doesn't exist. Please enter it again or choose New User.</p>
    <?php endif;?>
    <?php if (!$logged_in):?>
        <h3>Please enter or register your username.</h3>
        <form method='post' onsubmit="return validate()">
            <div>Username</div> 
            <div><input type="text" id='username' name='username' /></div>
            <p>Your username should be less than 10 characters.</p>
            <p id="too-long" class='error'>Please enter a username less than 10 characters.</p>
            <input type="radio" id="new" name="new" value="y">
            <label for="new">New user</label><br>
            <input type="radio" id="returning" name="new" value="n">
            <label for="returning">Returning user</label><br>
            <input type = 'submit'>
        </form>
    <?php endif;?>
    <?php if ($logged_in){
        include 'searchForm.php';
    }?>
</body>
</html>
