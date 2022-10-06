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
```
