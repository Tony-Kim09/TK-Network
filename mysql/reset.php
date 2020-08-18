<!DOCTYPE html>
<html>
    <!-- Make sure the database is created beforehand on MySQL -->
    <head>
        <title>Setting up database</title>
    </head>
    <body>
        <h3>Setting up...</h3>
        <?php
        require_once '../functions.php';
        dropTable('members');
        dropTable('messages');
        dropTable('friends');
        dropTable('profiles');
        createTable('members',
                    'user VARCHAR(16),
                    pass VARCHAR(255),
                    INDEX(user(6))');
        createTable('messages',
            'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            auth VARCHAR(16),
            recip VARCHAR(16),
            pm CHAR(1),
            time INT UNSIGNED,
            message VARCHAR(4096),
            INDEX(auth(6)),
            INDEX(recip(6))');
        createTable('friends',
                    'user VARCHAR(16),
                    friend VARCHAR(16),
                    INDEX(user(6)),
                    INDEX(friend(6))');
        createTable('profiles',
                    'user VARCHAR(16),
                    text VARCHAR(4096),
                    INDEX(user(6))');
        ?>
        ?>
        <br>...done.
    </body>
</html>