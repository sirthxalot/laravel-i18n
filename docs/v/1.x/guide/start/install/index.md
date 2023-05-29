---
title: Installation Guide
---

Step-01: Use Composer
--------------------------------------------------------------------------------

You may install {{ $site.title }} into your application using the 
[Composer] package manager:

``` sh
composer require sirthxalot/laravel-i18n
```

You are now basically ready to go if you would like to run I18n 
on the default file driver.

Step-02: Publish File Groups
--------------------------------------------------------------------------------

Use the `i18n-config` tag to publish the `config/i18n.php` config file:

``` sh
php artisan vendor:publish --tag=i18n-config
```

Use the `i18n-lang` tag to publish the translation (lang) files:

``` sh
php artisan vendor:publish --tag=i18n-lang
```

Use the `i18n-migration` tag to publish the I18n database 
migrations:

``` sh
php artisan vendor:publish --tag=i18n-migration
```

Step-03: Database Setup
--------------------------------------------------------------------------------

Change the `i18n.driver` driver configuration to **database**, 
within the `config/i18n.php` file:

``` php
'driver' => "database"
```

Run the [database migration] to create the language and translation 
tables:

``` sh
php artisan migrate
```

Step-04: Synchronize Drivers
--------------------------------------------------------------------------------

If you would like to import all existing file translations into 
the database you can do that. Synchronization means that one driver 
(file) will copy everything into another (database). This means 
existing translations will be overwritten.

Let's begin by publishing all translation files from the application 
itself:

``` php
php artisan lang:publish
```

Also publish all vendor languages or create file translations you 
may want to import.

Run the `i18n:sync` artisan command when you are ready to sync 
the drivers:

``` sh
php artisan i18n:sync file database
```

Confirm the question with `yes`.

<!--                            that's all folks!                            -->

[composer]: https://getcomposer.org/
[database migration]: https://laravel.com/docs/10.x/migrations