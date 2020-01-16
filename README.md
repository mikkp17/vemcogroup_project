## Laravel code test
A web application with registration and login. Downloads page allows for downloading an excel spreadsheet which was defined by the test.

## Instructions
To start using this web application you must first start an instance of a MySQL database. The database information should be entered in the projects .env file.

Once the database has been set up, the following steps should be done: 

- Execute ´php artisan migrate´ in terminal to migrate database tables
- Execute 'php artisan serve' to start server instance
- Head to the address specified in terminal by holding down control and clicking it

Now you are ready to begin using the website. To access home, create or downloads page, you need to register or login. Once you are logged in, you can navigate the pages and test the download function by clicking on the file name.
The files are stored on the server in '{root of project}/storage/app'
