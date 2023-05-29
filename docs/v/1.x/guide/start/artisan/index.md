---
title: Artisan Console Commands
---

Add Language
--------------------------------------------------------------------------------

Use the `i18n:add-language` artisan command to create a new 
language:

```sh
php artisan i18n:add-language
```

List All Languages
--------------------------------------------------------------------------------

Use the `i18n:list-languages` artisan command to get a list of 
all languages:

```sh
php artisan i18n:list-languages
```

Import Missing Translations
--------------------------------------------------------------------------------

Use the `i18n:import-missing-translations` artisan command to 
import all missing translations.

```sh
php artisan i18n:import-missing-translations
```

List Missing Translations
--------------------------------------------------------------------------------

Use the `i18n:list-missing-translations` artisan command to get a 
list of all missing translations:

```sh
php artisan i18n:list-missing-translations
```

Set Translation
--------------------------------------------------------------------------------

Use the `i18n:set-translation` artisan command to create or update 
a translation or even language:

```sh
php artisan i18n:set-translation
```

Synchronize Translation Drivers
--------------------------------------------------------------------------------

Use the `i18n:sync` artisan command to synchronize one I18n driver 
into another:


```sh
php artisan i18n:sync
```

:::warning
Synchronizing means that all translations or languages found within 
one driver will be copied into another driver, regardless if they 
exist or not. Therefore it uses the set translation method which 
creates or updates a translation. 

This means that if you already edited a translation you will loose 
the message if the key is present. To avoid this you should always 
remove translations you don't want to update (within the from driver) 
before syncing (into the target driver).
:::