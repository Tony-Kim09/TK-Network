<?php
    //Necessary information to connect to MySQL
    $dbhost = 'localhost';
    $dbname = ''; //Create a database with a name of your choice
    $dbuser = ''; //Fill in your Username and Password to access your MySQL server
    $dbpass = '';
    
    /* Establish a connection to MySQL server using the credentials above */
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error){
        die("Could not connect to Database");
    }
    
    //Create new tables in the Database
    function createTable($name, $query){
        queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
        echo "Table '$name' created or already exists. <br>";
    }
    
    //Drop the members table to recreate it. Mainly used for testing purposes
    function dropTable($name){
        queryMysql("DROP TABLE IF EXISTS $name");
        echo "Table '$name' has been dropped. <br>";
    }
    
    //Query function that will execute SQL commands
    function queryMysql($query){
        global $connection;
        $result = $connection->query($query);
        if(!$result) {
            die("Something went wrong on our side! Error Code: 9921");
        }
        return $result;
    }
    
    //Remove cookie session and log user out
    function destroySession(){
        $_SESSION = array();
        
        if (session_id() != "" || isset($_COOKIE[session_name()])){
            setcookie(session_name(), '', time()-2592000, '/');
        }
        
        session_destroy();
    }
    
    /* Sanitize User Input for security */
    function sanitizeString($var){
        global $connection;
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $connection->real_escape_string($var);
    }
    
    //Check if user has profile photo or Profile description and display them
    function showProfile($user){
        if (file_exists("$user.jpg")){
            echo "<img src='$user.jpg' style='float:left; '>";
        }
        $result = queryMysql("SELECT * FROM profiles WHERE user = '$user'");
        
        if ($result->num_rows){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            echo stripslashes($row['text']) . "<br style ='clear:left;'><br>";
        }
        else {
            echo "<p>Nothing to see here, yet</p><br>";
        }
    }
