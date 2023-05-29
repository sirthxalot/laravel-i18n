---
title: About Internationalization (i18n)
---

Introduction
-----------------------------------------------------------------

Internationalization ([I18n]) refers to the process of designing 
a software application so that it can be adapted to various languages 
and regions without engineering changes. For Web applications, this 
is of particular importance because the potential users may be 
worldwide. {{ $site.title }} offers a full spectrum of I18N 
features that support message translation, view translation, 
date and number formatting.

Most of these features are already implemented within the Laravel 
framework - take a peek at [Laravel's Localization feature]. 
{{ $site.title }} extends these features, to take advantage of a 
fully covered translation and internationalization workflow.

Locale
-----------------------------------------------------------------

Locale is a set of parameters that defines the user's language, 
country and any special variant preferences that the user wants 
to see in their user interface. It is usually identified by an ID 
consisting of a language ID and a region ID.

For example, the ID `en_US` stands for the locale of "English and 
the United States". This is the ISO-15897 standard that is recommended 
in the [Laravel documentation][Using Short Keys].

For consistency reasons, all locale IDs used in {{ $site.title }} 
should be canonicalized to the format of `ll_CC`, where `ll` is a 
two- or three-letter lowercase language code according to [ISO-639] 
and `CC` is a two-letter country code according to [ISO-3166]. 
More details about locales can be found in the [documentation of 
the ICU project].

Language
-----------------------------------------------------------------

A language and a locale may seem to be identical. And under the 
hood, they act the same but have different purposes. 

While a locale is meant to give information about a user's region 
or location. The language will be used to reference translations, 
to tell the system that a user can understand a text from a given 
locale.

Both are following the ISO-15897 standard.

Translations
-----------------------------------------------------------------

Typically, translation strings are stored in files within the 
`lang/` directory. Within this directory, there should be a 
subdirectory for each language supported by your application. 
This is the approach Laravel uses to manage translation strings 
for built-in Laravel features such as validation error messages.

### Translation Methods

Translation methods are used to determine which text needs to be 
translated. [Laravel's most common approach][laravel translation methods] 
is to use the `__()` method to translate strings:

```php
__('Hello :name!', ['name' => "Mickey"])
```

### Translation Types

#### Single Translations

Single translations (or [translation strings as keys]) are strings 
e.g. `"Hello World!"` which need to be translated. This approach 
is recommended for applications that have numerous translation 
strings.

For example, if your application has a German translation, you 
should create a `lang/de.json` file:

```json
{
    "I love programming.": "Ich liebe es zu programmieren."
}
```

Use the `I love programming.` translation key within the translation
method to retrieve the message:

```php
__('I love programming.');
```

#### Group Translations

Translations can be grouped in their `.php` translation files. 
This is the approach Laravel uses to manage translation strings 
for built-in Laravel features such as validation error messages.

For example, if your application has a German translation for 
validation messages, you should create a `lang/de/validation.php` 
file:

```php
return [
    'failed' => "Whoopsi something went wrong."
];
```

Use the `validation.failed` translation key within translation 
methods to retrieve the message:

```php
__('validation.failed');
```

:::tip Nested Translation Messages
All grouped translation (including vendors) can use a multidimensional 
array with nested keys. In that case the translation keys will be 
converted with [`Arr::dot()`] method. A translation key may look 
like `validation.first_name.required`.
:::

#### Vendor Translations

Some packages may ship with their own language files. Instead of 
changing the package's core files to tweak these lines, you may 
override them by placing files in the `lang/vendor/{package}/{locale}` 
directory.

So, for example, if you need to override the English translation 
strings in `validation.php` for a package named `a/b`, you should 
place a language file at: `lang/vendor/b/en/validation.php`. Within 
this file, you should only define the translation strings you wish 
to override. Any translation strings you don't override will still 
be loaded from the package's original language files.

Use a translation key like `b::validation.failed` within the 
translation method to retrieve the translation message:

```php
__('b::validation.failed')
```

:::warning Vendor And Single Translations?
Vendors could use single translations. However, these files are not 
stored within the `lang/vendor/` directory. Instead, they will be 
merged with the `lang/{locale}.json` file.
:::

### Translation Parameters

If you wish, you may define placeholders in your translation strings. 
All placeholders are prefixed with a `:`. For example, you may 
define a welcome message with a placeholder name:

```php
'welcome' => 'Welcome, :name',
```

To replace the placeholders when retrieving a translation string, 
you may pass an array of replacements as the second argument to 
the translation method:

```php
__('messages.welcome', ['name' => 'dayle']);
```

If your placeholder contains all capital letters, or only has its 
first letter capitalized, the translated value will be capitalized 
accordingly:

```php
'welcome' => 'Welcome, :NAME', // Welcome, DAYLE
'goodbye' => 'Goodbye, :Name', // Goodbye, Dayle
```

### Pluralization

Pluralization is a complex problem, as different languages have a 
variety of complex rules for pluralization; however, {{ $site.title }} 
can help you translate strings differently based on pluralization 
rules that you define. Using a `|` character, you may distinguish 
singular and plural forms of a string:

```php
'apples' => 'There is one apple|There are many apples',
```

Of course, pluralization is also supported when using [translation 
strings as keys]:

```json
{
    "There is one apple|There are many apples": "Hay una manzana|Hay muchas manzanas"
}
```

You may even create more complex pluralization rules which specify 
translation strings for multiple ranges of values:

```php
'apples' => '{0} There are none|[1,19] There are some|[20,*] There are many',
```

After defining a translation string that has pluralization options, 
you may use the `trans_choice()` function to retrieve the line for 
a given "count". In this example, since the count is greater than 
one, the plural form of the translation string is returned:

```php
trans_choice('messages.apples', 10);
```

You may also define placeholder attributes in pluralization strings. 
These placeholders may be replaced by passing an array as the third 
argument to the `trans_choice()` function:

```php
'minutes_ago' => '{1} :value minute ago|[2,*] :value minutes ago',
 
trans_choice('time.minutes_ago', 5, ['value' => 5]);
```

If you would like to display the integer value that was passed to 
the `trans_choice()` function, you may use the built-in `:count` 
placeholder:

```php
'apples' => '{0} There are none|{1} There is one|[2,*] There are :count',
```

<!--                            that's all folks!                            -->

[i18n]: https://en.wikipedia.org/wiki/Internationalization_and_localization
[iso-639]: https://www.loc.gov/standards/iso639-2/
[ISO-3166]: https://www.loc.gov/standards/iso639-2/
[documentation of the ICU project]: https://unicode-org.github.io/icu/userguide/locale/#the-locale-concept
[laravel's localization feature]: https://laravel.com/docs/10.x/localization
[laravel translation methods]: https://laravel.com/docs/10.x/localization#retrieving-translation-strings
[using short keys]: https://laravel.com/docs/10.x/localization#using-short-keys
[translation strings as keys]: https://laravel.com/docs/10.x/localization#using-translation-strings-as-keys
[translation strings as keys]: https://laravel.com/docs/10.x/localization#using-translation-strings-as-keys
[`Arr::dot()`]: https://laravel.com/docs/10.x/helpers#method-array-dot
