<?php
    require_once 'header.php';
    
    //Exit File if not logged in
    if (!$loggedin){
        die("</div></body></html>");
    }
    
    //View members messages or user's message
    if (isset($_GET['view'])){
        $view = sanitizeString($_GET['view']);
    } else {
        $view = $user;
    }

    //Post Message on the board
    if(isset($_POST['text'])){
        $text = sanitizeString($_POST['text']);
        
        if ($text !=""){
            //PM determines whether it is private or public message
            $pm = substr(sanitizeString($_POST['pm']), 0, 1);
            $time = time();
            queryMysql("INSERT INTO messages VALUES(NULL, '$user', '$view', '$pm', '$time', '$text')");
        }
    }
    
    if($view != ""){
        
        //If viewing User's Messages
        if ($view == $user){
            $name1 = $name2 = "Your";
        } else{
            
        //Viewing Member's messages
            $name1 = "<a href='members.php?view=$view'>$view</a>'s";
            $name2 = "$view's";
        }
        
        echo "<h3>$name1 Messages</h3>";
        
        //Display the owner of the current page's profile
        showProfile($view);
        
        //Display UI for User to use for posting message
        echo <<<_END
            <form method='post' action='messages.php?view=$view'>
                <fieldset data-role="controlgroup" data-type="horizontal">
                    <legend>Type here to leave a message</legend>
                    <input type='radio' name='pm' id='public' value='0' checked='checked'>
                    <label for="public">Public</label>
                    <input type='radio' name='pm' id='private' value='1'>
                    <label for="private">Private</label>
                </fieldset>
                <textarea name='text' maxlength='300'></textarea>
                <input data-transition='slide' type='submit' value='Post Message'>
            </form><br>
        _END;
        
        //Default Timezone will be based on UTC
        date_default_timezone_set('UTC');
        
        //Give option to delete messages
        if(isset($_GET['erase'])){
            $erase = sanitizeString($_GET['erase']);
            queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$user'");
        }
        
        //Obtain all messages received by the Owner of the page
        $query = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
        $result = queryMysql($query);
        $num = $result->num_rows;
        
        for ($i = 0; $i < $num; $i++){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            //Only public messages or author and recipient of the messages will be displayed
            if ($row['pm'] == 0 || $row['auth'] == $user || $row ['recip'] == $user){
                echo date('M jS \'y g:ia:', $row['time']);
                echo " <a href='messages.php?view=" . $row['auth'] . "'>" . $row['auth'] . "</a> ";
                
                //Display Public Message
                if ($row['pm'] == 0){
                    echo "wrote: &quot;" . $row['message'] . "&quot; ";
                } else {
                
                //Display private message as Whispered
                    echo "whispered: <span class='whisper'>&quot;" . $row['message'] . "&quot;</span> ";
                }
                
                //Give the recipient an option to delete the message
                if ($row['recip'] == $user){
                    echo "[<a href='messages.php?view=$view" . "&erase=" . $row['id'] . "'>erase</a>";
                }
                
                echo "<br>";
            }
        }
    }
    
    //If no rows were returned from the query
    if (!$num){
        echo "<br><span class='info'> No Messages yet</span><br><br>";
    }
    
    //Reload page
    echo "<br><a data-role='button'
            href='messages.php?view=$view'>Refresh Messages</a>";
?>
<!-- Close the HTML tags in Header.php -->
</div><br>
</body>
</html>
