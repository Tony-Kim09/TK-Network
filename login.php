<?php

require_once 'header.php';
$error = $user = $pass = "";

if (isset($_POST['user'])) {
    //Sanitize User input for security
    $user = sanitizeString($_POST['user']);

    //Check for blank username
    if ($user == "") {
        $error = 'Please enter your username';
    } else {

        //Query MySql to see if Username Exists
        $result = queryMySQL("SELECT pass FROM members WHERE user = '$user'");
        $hashedPassword = "";

        if ($result->num_rows == 0) {
            $error = "Username does not exist";
        } else {
            $row = $result->fetch_array(MYSQLI_NUM);
            $hashedPassword = $row[0];

            if (!password_verify($_POST['pass'], $hashedPassword)) {
                $error = "Incorrect login information";
            } else {

                //If successfully logged in, redirect to Home Page
                $_SESSION['user'] = $user;
                $_SESSION['pass'] = $hashedPassword;
                header("Location: members.php?view=$user");
            }
        }
    }
}

echo <<<_END
            <form method='post' action='login.php'>
                <div data-role='fieldcontain'>
                    <label></label>
                    <span class='error'>$error</span>
                </div>
                <div data-role='fieldcontain'>
                    <label></label>
                    Please enter your details to log in
                </div>
                <div data-role='fieldcontain'>
                    <label>Username</label>
                    <input type='text' maxlength='16' name='user' value='$user'>
                </div>
                <div data-role='fieldcontain'>
                    <label>Password</label>
                    <input type='password' maxlength='16' name='pass' value='$pass'>
                </div>
                <div data-role='fieldcontain'>
                    <label></label>
                    <input data-transition='slide' type='submit' value='Login'>
                </div>
            </form>
        </div>
    </body>
    </html>
    _END;
?>