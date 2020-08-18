<?php

require_once 'header.php';

//Run script to check if userinput for Username meets the requirements
echo <<<_END
    <script>
        function checkUser(user){
            if(user.value == ''){
                $('#used').html('&nbsp;');
                return;
            }
            $.post
            (
                'checkuser.php',
                { user: user.value },
                function (data){
                    $('#used').html(data);
                }
            )
        }
    </script>
_END;

$error = $user = $pass = "";

//If user is logged in, destroy current session and start new session
if (isset($_SESSION['user'])){
    destroySession();
}
if (isset($_POST['user'])) {
    
    //Sanitize Username before working with it
    //Password will be hashed before being stored in a variable
    $user = sanitizeString($_POST['user']);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
    //Check that both username and password have values
    if ($user == "" || $pass == ""){
        $error = 'Not all fields were entered<br><br>';
    } 
    //If user tries to submit using invalid characters, display an error
    elseif (!preg_match('/[a-zA-Z0-9_-]{6,16}$/', $user)){
        $error = 'Only A-Z, 0-9, - and _ are supported and should be between 3-16 characters';
    } else {
    
    //Check if the username already exists in the database
        $result = queryMysql("SELECT * FROM members WHERE user='$user'");
        if ($result->num_rows){
            $error = 'That username already exists<br><br>';
        }else {
    //Insert into table if username does not exist
            queryMysql("INSERT INTO members VALUES('$user', '$pass')");
            die("<h4>Account created</h4><a data-transition='slide' href='login.php'>Click here</a> to login.</div></body></html>");
        }
    }
}

//HTML form with two inputs for Username and Password.
//Min and Max character of 6 and 16 respectively for Username

echo <<<_END
        <div class='signupError'>$error</div>
        <form method='post' action='signup.php'>
        <div data-role='fieldcontain'>
            <label></label>
            Please enter your details to sign up
        </div>
        <div data-role='fieldcontain'>
            <label>Username</label>
            <input type='text' minlength='6' maxlength='16' name='user' value='$user'
            onBlur='checkUser(this)'>
        <label></label><div id='used'>&nbsp;</div>
        </div>
        <div data-role='fieldcontain'>
            <label>Password</label>
            <input type='password' name='pass' value='$pass'>
        </div>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Sign Up'>
        </div>
        </div>
    </body>
</html>
_END;
?>