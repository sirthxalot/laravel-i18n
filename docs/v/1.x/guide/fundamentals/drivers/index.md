---
title: About I18n Drivers
---

Database Driver
--------------------------------------------------------------------------------

The I18n database driver is the default driver and is meant to 
load translations from a database connection. This driver works 
with Eloquent models and/or database migrations, with all the 
goodies we artisans know and love so much.

Translations and languages are database records and can be found 
within their tables.

::: warning
If you are changing the migrations or models we may not support 
you anymore. We are always interested in different routes and love 
to explore them, but sometimes we may not have the time for this. 
So changing the database structure of I18n is possible but only on 
your own responsibility.
:::

### Database Migrations

The [migrations] will be loaded within the package by default. This 
allows to follow our convention and ensures that everything works 
as expected without focusing on the database too much. All you need 
to do is to migrate and you are done:

```sh
php artisan migrate
```

If you would like to change the database structure for languages 
and/or translations you will need to publish the migrations first:

```sh
php artisan vendor:publish --tag=i18n-migration
```

### Customize Models

If you are extending or replacing the language/translation models, 
you will need to specify your new models in this package's 
`config/i18n.php` file:

```php
return [
    'database' => [
        'models' => [
            'language' => \Sirthxalot\Laravel\I18n\Models\Language::class,
            'translation' => \Sirthxalot\Laravel\I18n\Models\Translation::class,
        ]
    ]
];
```

#### Extending Models

More than often you only will need to extend a model, which adds 
new functionality while keeping the fundamentals. So all you need 
to do is to create a new model that extends the I18n models. Here 
is an example of how to do that with the language model:


:::: code-group
::: code-group-item Language
```php
<?php namespace App\Models;

use Sirthxalot\Laravel\I18n\Models\Language as I18nLanguage;

class Language extends I18nLanguage
{
    //...
}
```
:::
::: code-group-item Translation
```php
<?php namespace App\Models;

use Sirthxalot\Laravel\I18n\Models\Translation as I18nTranslation;

class Translation extends I18nTranslation
{
    //...
}
```
:::
::::

So that's easy, but don't forget to update the configuration. 

#### Replacing Models

While it is possible to replace model things may get complicated 
and is only meant in rare cases where a master is doing his work.

- Language Model
  - New models must implement the `Sirthxalot\Laravel\I18n\Contracts\Models\LanguageContract` contract
  - It must have a fillable locale attribute.
- Translation Model
  - New models must implement the `Sirthxalot\Laravel\I18n\Contracts\Models\TranslationContract` contract
  - It must have a fillable key attribute.
  - It must have a fillable locale attribute.
  - It must have a fillable message attribute.

File Driver
--------------------------------------------------------------------------------

The I18n file driver acts pretty much the same as the default 
Laravel translation workflow. It also loads the translations from 
`.php` or `.json` files. In fact, it does not even touch Laravel's 
default translator.

In addition, the file driver can also scan the `lang/` directory 
for any language even within the vendor directories. All a language 
needs is a `{locale}/` directory or `{locale}.json` file.

<!--                            that's all folks!                            -->

[migrations]: https://laravel.com/docs/10.x/migrations