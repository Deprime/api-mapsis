# Mapsis service api

## Migration generator[https://github.com/kitloong/laravel-migrations-generator]

To create migrations for all the tables, run:

```terminal
php artisan migrate:generate
```

You can specify the tables you wish to generate using:

```terminal
php artisan migrate:generate --tables="table1,table2,table3,table4,table5"
```

## Seeder generator[https://github.com/orangehill/iseed]

```terminal
php artisan iseed my_table,another_table
```

### force

Optional parameter which is used to automatically overwrite any existing seeds for desired tables

```terminal
php artisan iseed users --force
```

## Database

```terminal
php artisan migrate
```

```terminal
php artisan db:seed
```

```terminal
php artisan db:seed --class UserSeeder
php artisan db:seed --class PageDemoSeeder
```

## Octane

For working with Octane on Windows systems we must use [Sail](https://laravel.com/docs/9.x/sail) package.
[Doc](https://laravel.com/docs/9.x/octane#roadrunner-via-laravel-sail)

Working URL http://localhost:80/

### Using

Run Sail server on local machine

```terminal
bash ./vendor/bin/sail up
```

##### Run shells in current Docker container 

```terminal
bash ./vendor/bin/sail root-shell
bash ./vendor/bin/sail shell
```

##### Publishing Docker settings. 

**Do not forget change running command in supervisord.conf to set up Octane for preferred PHP version**

```terminal
./vendor/bin/sail artisan sail:publish
```
=======
```terminal
php artisan scout:import "App\Models\Post"
```

[Algolia Laravel Doc](https://www.algolia.com/doc/framework-integration/laravel/searching/server-side-search/?client=php)
