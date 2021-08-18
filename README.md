## About The Application

This application serves as a submission to the Color Elephant Laravel Developer Job Application.

This application uses laravel 8, so ensure you have minimum of PHP 7.4 installed as that's part of [laravel's requirements](https://laravel.com/docs/8.x) 

### To start the application: 

- Clone the repo
- Run `composer install`
- Create a `.env` file
- Copy contents in `.env.example` to `.env`
- Setup a `DB connection` of your choice
- Run `php artisan migrate`
- Start the application `php artisan serve`
- Start the queue service `php artisan queue:work`
- Check the [API documentation](https://documenter.getpostman.com/view/4827230/TzzAMbuH) to get started with the available endpoints

The application uses laravel queuing system so ensure your `QUEUE_CONNECTION = database` in your `.env` file

Once a transaction is created, the file is queued for processing, so we need to start up the queue processor by running `php artisan queue:work`. This then runs through the file uploaded in batches of `500` and uploads the records in the DB, this process runs in the background. Once the data import is completed per batch, any errors found are created as a record in the DB as well.
