---
title: About Language Names
---

Introduction
--------------------------------------------------------------------------------

A language's locale is the perfect way, to inform a system about 
a language. However, they are hard to understand for humans. So, 
more than often you will need to convert a locale into a readable 
output e.g. "English" or "German". 

But wait there is no language name supported in any translation 
service method or even the database! So how do we manage to translate 
a locale into a readable name?

Well, this will be done through our `i18n::languages.{locale}` 
vendor translations.

Get Language Translation
--------------------------------------------------------------------------------

Use the `i18n_lang()` helper function, to translate a locale into 
a readable string for humans:

```php
i18n_lang('en_US');
```

This method expects the locale e.g. "en_US" and tries to translate 
it (using a key like `i18n::languages.en_US`). If the key is 
missing then it falls back to the locale ("en_US").

You could use the translations within the regular translation methods. 
However, this may lead to a problem since languages that have not 
been translated will return the key which is still even harder to 
read:

```php
__('i18n::languages.en_US');
```

Translate Languages
--------------------------------------------------------------------------------

### File Driver

Create a new `languages.php` file within the `lang/vendor/i18n/{locale}/` 
directory:

```php
return [
    'de_CH' => 'Swiss German',
];
```

That's it you already translated those locales in a given locale 
(here english).

### Database Driver

#### Without Existing Translations

If you don't have any translations loaded into the database or don't 
care if the translations may be overwritten then you should use 
the `i18n:sync` artisan command to synchronize drivers. But before 
you do that create the translations as described for the [file driver](#file-driver).

:::warning
Whenever you sync the file driver into the database it will overwrite 
existing translations in the database. So be careful when you synchronize 
drivers.
:::

After creating the translation file use the sync artisan command:

```sh
php artisan i18n:sync "file" "database"
```

Confirm with `yes` if you would like to sync all translations in 
all languages. Otherwise use the third argument to determine the 
locale you want to use:

```sh
php artisan i18n:sync "file" "database" "en"
```

#### With Existing Translations

If you can delete all translation files and directories you don't 
need or want to be overwritten and do the same as [without existing 
translations](#without-existing-translations).

Otherwise, you should use the artisan command meant to create or 
update a translation:

```sh
php artisan i18n:set-translation "i18n::languages.en" "en" "English"
```

Or you could also use the `setTranslation()` method within the I18n 
service.