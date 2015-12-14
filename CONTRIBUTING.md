#Contributing
The project is currently in a prototype stage.

The backend is almost finished and is just lacking some functionality that we originally intended to include.

As we focused on the backend, the frontend currently needs a lot of work.

As we are students who also work in part time jobs our time to develop on the project is currently slightly restricted
and therefore we are looking for skilled developers with the following skillset:

* FRONTEND:
    * Skilled Angularjs developers
    * Designers skilled with the usage of Twitter Bootstrap
    
* BACKEND:
    * Skilled Zend Framework 2 Developers
    * We use the DOCTRINE ORM for the Database so it would be good if you have knowledge in that as well or if you would
      read through a guide for this (e.g. http://ocramius.github.io/presentations/doctrine-orm-and-zend-framework-2/#/1)
    
#How to set-up the project

##Minimum Requirements
We are running the Project on a local XAMPP installation but of course you can use any Server you like as long as PHP is available
and the PHP Version is above version 5.5.

The Server also needs to have a MYSQL-Database. You can find the current Database-Structure with some sample-data
in the languageteachers.sql file included in the repository.



##Setup
In order to get the application to communicate with the database you need to set up a database-user.
The user setup can be found in the global.php file which can be config/autoload folder. 

If you don't want to create a new user, copy the global.php file, rename it to local.php and rewrite the access data to your mysql user.
Do the same for the doctrine.global.php file in case you don't create a new user.

You will also have to set up two vhosts on your server in the following manner (one pointing to the backend, one pointing to the frontend):
(The following vhosts are for windows - LINUX and MAC users might have to change some things here (most likely with how
the path to the application has to be written))

DON'T CHANGE THE SERVERNAME FOR THE BACKEND AS IT IS USED IN ANGULARJS TO ACCESS THE BACKEND!!
HOWEVER, YOU CAN CHANGE THE FRONTEND SERVERNAME TO WHATEVER YOU WANT! 
    
    FRONTEND
    <VirtualHost *:80>
         ServerName language.teacher.se
         DocumentRoot "PATH/TO/YOUR/APPLICATIONFOLDER/public/app"
         SetEnv APPLICATION_ENV "development"
         <Directory "PATH/TO/YOUR/APPLICATIONFOLDER/public/app">
             DirectoryIndex index.html
             AllowOverride All
             Order allow,deny
             Allow from all
         </Directory>
     </VirtualHost>
     
    BACKEND
    <VirtualHost *:80>
         ServerName language.teacher.backend.se
         DocumentRoot "PATH/TO/YOUR/APPLICATIONFOLDER/public"
         SetEnv APPLICATION_ENV "development"
         <Directory "PATH/TO/YOUR/APPLICATIONFOLDER/public">
             DirectoryIndex index.php
             AllowOverride All
             Order allow,deny
             Allow from all
         </Directory>
     </VirtualHost>

You access the Front-End with the URL http://language.teacher.se or whatever URL you setup in your vhost for your frontend.

For more information please take a look into the CONTACT.md file where you can see the contact details.