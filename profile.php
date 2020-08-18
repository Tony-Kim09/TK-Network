<?php

    require_once 'header.php';

    //Exit file if not logged in
    if (!$loggedin) {
        die("</div></body></html>");
    }

    echo "<h3>Your Profile</h3>";

    //Retrieve User's Profile
    $result = queryMysql("SELECT * FROM profiles WHERE user = '$user'");

    //User Updates or Posts New Profile Text on submit
    if (isset($_POST['text'])) {
        $text = sanitizeString($_POST['text']);
        
    //Strip excess whitespace from User's text
        $text = preg_replace('/\s\s+/', ' ', $text);

    //If User profile text already exists, update it instead
        if ($result->num_rows) {
            queryMysql("UPDATE profiles SET text ='$text' WHERE user='$user'");
        } else {
    
    //Insert User's profile text into the database
            queryMysql("INSERT INTO profiles VALUES ('$user', '$text')");
        }
    } else {
    
    //When user is simply loading the profile page, fetch from database
        if ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $text = stripslashes($row['text']);
        } else {
    //If no rows were retrieved from the query, set default message
            $text = "Profile Desciption is not set yet";
        }
    }
    
    //Extra security measures to prevent injection attempts
    $text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

    //Check if user uploaded an image file
    if (isset($_FILES['image']['name'])) {
        //The file will be saved using the following destination and username
        $saveto = "$user.jpg";
        move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
        $typeok = TRUE;

        //Only accept filetypes of Gif, jpeg/pjpeg, and png files
        switch ($_FILES['image']['type']) {
            case "image/gif": $src = imagecreatefromgif($saveto);
                break;
            case "image/jpeg":
            case "image/pjpeg": $src = imagecreatefromjpeg($saveto);
                break;
            case "image/png": $src = imagecreatefrompng($saveto);
                break;
            default: $typeok = FALSE;
                break;
        }
        
        //Thumbnail Size for User Profile Picture
        if ($typeok) {
            list($w, $h) = getimagesize($saveto);

            $max = 100;
            $tw = $w;
            $th = $h;

            if ($w > $h && $max < $w) {
                $th = $max / $w * $h;
                $tw = $max;
            } elseif ($h > $w && $max < $h) {
                $tw = $max / $h * $w;
                $th = $max;
            } elseif ($max < $w) {
                $tw = $th = $max;
            }
            
        //Format the size of the image while mitigating blurred pixels
            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(array(-1, -1, -1),
                array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
            
            //Save the image file in the saveto location
            //Delete the temp files
            imagejpeg($tmp, $saveto);
            imagedestroy($tmp);
            imagedestroy($src);
        }
    }

    //Display user profile using showProfile function from the Functions.php
    showProfile($user);

    //Multi-part form with textbox and ability to upload a file
    echo <<<_END
            <form data-ajax='false' method='post'
                action='profile.php' enctype='multipart/form-data'>
            <h3>Enter or edit your details and/or upload an image</h3>
            <textarea name='text'>$text</textarea><br>
            Image: <input type='file' name='image' size='14'>
            <input type='submit' value='Save Profile'>
            </form>
            </div><br>
        </body>
    </html>
    _END;
?>