<?php
    require_once 'header.php';

    //Exit if not logged in
    if (!$loggedin) {
        die("</div></body></html>");
    }

    //Show the clicked Members Profile Page
    if (isset($_GET['view'])) {
        $view = sanitizeString($_GET['view']);

        if ($view == $user) {
            $name = "Your";
        } else {
            $name = "$view's";
        }

        echo "<h3>$name Profile</h3>";
        showProfile($view);
        echo "<a class='button' data-transition='slide' 
                    href='messages.php?view=$view'>View $name messages</a>";
        die("</div></body></html>");
    }
        
    //Check if the member already exists as a friend. Insert into Friends table if not
    if (isset($_GET['add'])) {
        $add = sanitizeString($_GET['add']);

        $result = queryMysql("SELECT * FROM friends WHERE user = '$add' AND friend = '$user'");
        if (!$result->num_rows) {
            queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
        }
        
    }
    
    //Remove the member from the User's friend list
    elseif (isset($_GET['remove'])) {
        
      
        $remove = sanitizeString($_GET['remove']);
        queryMysql("DELETE FROM friends WHERE user = '$remove' AND friend = '$user'");
    }

    //Query Top 100 Users sorted Alphabetically
    $result = queryMysql("SELECT user FROM members ORDER BY user LIMIT 100");
    $num = $result->num_rows;

    //Start an Unordered List of Other Members
    echo "<h3>Other Members</h3><ul>";

    for ($j = 0; $j < $num; ++$j) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        
        //Skip if the current Row is the User itself
        if ($row['user'] == $user) {
            continue;
        }
        
        //Create a hyperlink to all the other members profile
        echo "<li><a data-transition='slide' href='members.php?view=" .
        $row['user'] . "'>" . $row['user'] . "</a>";
        $follow = "follow";
        
        //Search for all the members the User is following
        $result1 = queryMysql("SELECT * FROM friends WHERE
                    user='" . $row['user'] . "' AND friend='$user'");
        $t1 = $result1->num_rows;
        
        //Search for all the memebers following the User
        $result1 = queryMysql("SELECT * FROM friends WHERE
                               user='$user' AND friend='" . $row['user'] . "'");
        $t2 = $result1->num_rows;
        
        if (($t1 + $t2) > 1) {
            echo " &harr; is a mutual friend";
        } elseif ($t1) {
            echo " &larr; you are following";
        } elseif ($t2) {
            echo " &rarr; is following you";
            $follow = "Follow Back";
        }
        
        //Give an option to Follow a user back
        if (!$t1) {
            echo " [<a data-transition='slide'
                    href='members.php?add=" . $row['user'] . "'>$follow</a>]";
        } else {
            
        //If already following, give option to remove
            echo " [<a data-transition='slide'
                    href='members.php?remove=" . $row['user'] . "'>drop</a>]";
        }
    }
?>

<!-- Closing tags for Header.php -->
</ul></div>
</body>
</html>