# PHP workshop test

A workshop respository test with `codeigniter` as server framework

# Installation

1. Copy the repository to a working PHP directory
2. Import the MySQL database schema stored in `application/database/schema.sql`
3. Set the database connection settings in database config file located in `application/config/database.php`
4. Create a folder named `uploads`in the repository root, make sure that PHP has permission to access and write under this directory as there will be the uploaded images stored
5. Import the data dump in database folder from a file called `data.sql`

Once all of the listed process is done the application should be able to run successfully by accessing the corresponding url path in the browser

**NOTE:** The data imported from the dump do not contain any images, the image shown to these articles are placeholders requested from an external API service, you are able to change their images by selecting an article and update the article with a selected image