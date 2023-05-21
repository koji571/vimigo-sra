# vimigo-sra  
A Laravel based application for a Student Registry App built with a REST API.

## Installation
Git clone the repository and then run composer install to get the vendor packages.

    composer install

Once that is done, you need to setup your database connection, in the .env file

Once done, finally you can run

    php artisan migrate --seed

This command will create all the tables and seed Student data that can be manipulated

After which, you can run

    php artisan passport:client --personal

Run the following command in your Laravel application's root directory to generate the personal access client.    
  
This command will generate a client ID and client secret for the personal access client. 

Finally to run the server, run the command

    php artisan serve

This will run the server on your local computer

## POSTMAN Collection
[POSTMAN link](/vimigo-sra.postman_collection.json)
## Video Presentation
(redacted)
YOUTUBE LINK : (redacted)

