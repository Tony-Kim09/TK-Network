# TK-NETWORK

## What I Created

By using everything I learned from Oreily's "Learning PHP, MySQL, JavaScript with JQuery, CSS & HTML5" textbook, and with its guide, I created a simple social networking website.

## What I added to the site

- 1-pass hashing algorithm in PHP to securely store User password
- Used regular expression to ensure only permitted special characters are used
- Redirect users to proper page after registration and login

## What I Learned

- Creating and utilizing user database through MySQL
- Knowing how to properly query the database for desired information
- Storing and resizing user profile images for future retrieval whenever they open a users profile page
- Used Javascript for async call to check if the desired username is already taken for the registration page
- Properly and securely storing user messages and description into the database
- Ensure the program cannot be easily hacked through injection

## What I will improve

- Better design patterns when building websites
- Use designs such as MVC to seperate code and manage them better
- Modernize UI and fix design 
- Instead of leaving messages, create a live chatting system

## How to test the site yourself

First you need to have MySQL installed in your computer. Then in the functions.php file, fill in the dbuser/dbpass so it can properly access the database.
Before you run the setup.php file in the mysql folder, you will need to create a database with a name of your choice and fill in the dbname section in the functions.php file as well. 
After the database has been created and the dbname/dbuser/dbpass has been filled, run the file /mysql/setup.php file to initialize all the necessary tables. 
