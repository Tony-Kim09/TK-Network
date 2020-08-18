<?php
    require_once 'functions.php';
    /* 
     * This file is run by a script in Signup.php
     * Async call is made to check if the current 
     * user input for Username is taken.
     */
    if(isset($_POST['user'])){
        /*
         * Checks whether input for the username contains characters
         * besides Alphabets, Numbers, dash(-), and underscore(_)
         * using regex 
         */
        
        $username = sanitizeString($_POST['user']);
        
        if (!preg_match('/[a-zA-Z0-9_-]{6,16}$/', $username)){
          
            echo "<span class='taken'>&nbsp;&#x2718; " . 
                    "Only A-Z, 0-9, - and _ are supported and should be between 6-16 characters</span>";
        } 

        else {
        
        //Check if the specified username already exists in the database
         
            $result = queryMysql("SELECT * FROM members WHERE user='$username'");

            if($result->num_rows){
                echo "<span class='taken'>&nbsp;&#x2718; " .
                        "The username '$username' is taken</span>";
            } else {
                echo "<span class='available'>&nbsp;&#x2714; " .
                        "The username '$username' is available</span>";
            }
        }
    }