## Base source code

- Solution: https://docs.google.com/spreadsheets/d/1wWP2VowVLbkSXIhC-zO6e6zocZAVuE42nL4TpF5czhU/edit#gid=0
- Checklist: https://docs.google.com/spreadsheets/d/106iVKIWRXAyS6u28a8NN3a4HE6lUrhNzTRLTqUhXZTU/edit#gid=0
- Convention laravel: https://github.com/alexeymezenin/laravel-best-practices#follow-laravel-naming-conventions

## Run migration and make dummy data
- Run migration: ```php artisan migrate:fresh```
- Make dummy data <br>
Open BaseFactory.php file, copy comment line. Sample: ```factory(App\Models\BusinessManagement::class,5)->create()``` <br>
Open cmd, type: ```php artisan tinker``` <br>
Paste copied line and enter

## Make router

- Run command:  ```php artisan laroute:generate```

## Make roles

- Run command: ```php artisan command:generate-gate-provider```