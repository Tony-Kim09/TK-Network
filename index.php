<?php
    //TODO:: Use these constants to navigate through files after organizing them
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(__FILE__));    
        
    require_once 'header.php';
        
    echo "<div class='center'> Welcome to TK Network,";
    
    if($loggedin){
        echo " $user, you are logged in";
    } else {
       echo ' please sign up or log in';
    }
    
    echo <<<_END
            </div><br>
            </div>
            <div data-role="footer">
                <h4>Web App from <i><a href='https://github.com/Tony-Kim09/tk-social-media-proj'
                target='_blank'>Check out full project on my GitHub Page </a></i></h4>
            </div>
        </body>
    </html>
    _END;
?>
