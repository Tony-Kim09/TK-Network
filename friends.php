<?php
    require_once 'header.php';
    
    //If User is not logged in, close header.php and exit
    if (!$loggedin){
        die ("</div></body></html>");
    }
    
    if (isset($_GET['view'])){
        $view = sanitizeString($_GET['view']);
    } else {
        $view = $user;
    }
    
    if ($view == $user){
        $name1 = $name2 = "Your";
        $name3 = "You are";
    } else {
        $name1 = "<a data-transition='slide'
                    href='members.php?view=$view'>$view</a>'s";
        $name2 = "$view's";
        $name3 = "$view is";
    }
    
    //Initialize arrays that will store user's followers and following
    $followers = array();
    $following = array();
    
    //Store User's followers into an array
    $result = queryMysql("SELECT * FROM friends WHERE user='$view'");
    $num = $result->num_rows;
    
    for ($i = 0; $i<$num; $i++){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $followers[$i] = $row['friend'];
    }
    
    
    //Store members that the User's are following into an array
    $result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
    $num = $result->num_rows;
    
    for ($j = 0; $j < $num; $j++){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $following[$j] = $row['user'];
    }
    
    //If both user and members are following each other, store in mutual array    
    $mutual = array_intersect($followers, $following);
    //Store rest in their respective arrays
    $followers = array_diff($followers, $mutual);
    $following = array_diff($following, $mutual);
    
    //Determines whether User has friends or not
    $friends = FALSE;
    
    echo "<br>";
    
    //Check if user has mutual friends and display them
    if(sizeof($mutual)){
        echo "<span class ='subhead'>$name2 mutual friends</span><ul>";
        foreach($mutual as $friend){
            echo "<li><a data-transition='slide'
                        href='members.php?view=$friend'>$friend</a>";
        }
        echo "</ul>";
        $friends = TRUE;
    }
    
    //Check if user has followers and display each followers
    if(sizeof($followers)){
        echo "<span class ='subhead'>$name2 followers</span><ul>";
        foreach($followers as $friend){
            echo "<li><a data-transition='slide'
                        href='members.php?view=$friend'>$friend</a>";
        }
        echo "</ul>";
        $friends = TRUE;
    }
    
    //Check for members the user is following
    if(sizeof($following)){
        echo "<span class ='subhead'>$name3 following</span><ul>";
        foreach($following as $friend){
            echo "<li><a data-transition='slide'
                        href='members.php?view=$friend'>$friend</a>";
        }
        echo "</ul>";
        $friends = TRUE;
    }
    
    //Run if user does not have friends
    if (!$friends){
        echo "<br>You don't have any friends yet.<br><br>";
    }
    
    //Open messages url using user's name
    echo "<a data-role='button' data-transition='slide'
            href='messages.php?view=$view'>View $name2 messages</a>";
?>

<!-- Closing html tags for Header.php -->
</div>
</body>
</html>
    
