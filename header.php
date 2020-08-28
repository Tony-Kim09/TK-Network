<?php
    session_start();
    
    echo <<<_INIT
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <link rel='stylesheet' href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
            <link rel='stylesheet' href='style.css'>
            <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
            <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> 
    _INIT;
        
        require_once 'functions.php';
        
    //Set as Guest if not logged in
        $userStr = 'Welcome Guest';
        
        if(isset($_SESSION['user'])){
            $user = $_SESSION['user'];
            $loggedin = TRUE;
            $userStr = "Logged in as: $user";
        } else {
            $loggedin = FALSE;
        }
        
    //Title of the Website
        echo <<<_MAIN
            <title>TKNetwork Profile: $userStr</title>
        </head>
        <body>
            <div data-role='page'>
                <div data-role='header'>
                    <div id='logo'
                        class='center'>TK Network</div>
                    <div class='username'>$userStr</div>
                </div>
                <div data-role='content'>
                
        _MAIN;
        
    //Create a navigation bar if User is logged in
        if ($loggedin){
            echo <<<_LOGGEDIN
                <div class='center'>
                    <div class='center'>
                        <a data-role='button' data-inline='true' data-icon='home'
                          data-transition="slide" href='members.php?view=$user'>Home</a>
                        <a data-role='button' data-inline='true'
                           data-transition="slide" href='members.php'>Members</a>
                        <a data-role='button' data-inline='true'
                           data-transition="slide" href='friends.php'>Friends</a>
                        <a data-role='button' data-inline='true'
                           data-transition="slide" href='messages.php'>Messages</a>
                        <a data-role='button' data-inline='true'
                           data-transition="slide" href='profile.php'>Edit Profile</a>
                        <a data-role='button' data-inline='true'
                           data-transition="slide" href='logout.php'>Log out</a>
                    </div>
            _LOGGEDIN;
        } else {
    //If user is not logged in, show the registration/login page
            echo <<<_GUEST
               <div class='center'>
                    <a data-role='button' data-inline='true' data-icon='home'
                      data-transition='slide' href='index.php'>Home</a>
                    <a data-role='button' data-inline='true' data-icon='plus'
                      data-transition="slide" href='signup.php'>Sign Up</a>
                    <a data-role='button' data-inline='true' data-icon='check'
                      data-transition="slide" href='login.php'>Log In</a>
                </div>
                <p class='info'>(You must be logged in to use this app)</p>
            _GUEST;
        }
?>
        
