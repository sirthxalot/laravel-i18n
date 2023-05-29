---
title: I18n Service
---

Introduction
--------------------------------------------------------------------------------

Use the application helper to resolve the I18n service:

```php
use Sirthxalot\Laravel\I18n\Translation;

$i18n = app()->make(Translation::class);
```

Locales
--------------------------------------------------------------------------------

The locale management sticks to the default behavior of Laravel. 
{{ $site.title }} offers some basic features used within the 
internationalization and translation process, e.g. loading the 
locale from HTTP session or GET parameter, but that's it. Most of 
the work will be done within the Laravel application itself:

```php
app()->setLocale('de');
app()->setLocale('de_CH');
```

Or:

```php
app()->getLocale()
```

Languages
--------------------------------------------------------------------------------

### Add New Language

Use the `addLanguage()` method to create a new language if it 
does not exist:

```php
$i18n->addLanguage('en_US');
```

:::details Technical Details
All incoming data will be [validated][validate add language], 
before creating a new language.

**Parameter**:

- **locale**: A <u>string</u> that defines the language's locale.

**Return**:  
Returns a <u>boolean</u> that determines if the language has been 
created (`true`) or not (`false`).

**Events**:  
[<Badge type="tip" text="LanguageCreated" vertical="middle" />][`LanguageCreated`]
:::

### List All Languages

Use the `allLanguages()` method to list all languages available:

```php
$i18n->allLanguages();
```

:::details Technical Details
**Return**:  
Returns an <u>array</u> that lists each language locale and translated 
name for the current locale:

```php
['en' => "English", 'en_US' => "en_US"]
```

If no languages were found, then it returns an <u>empty array</u>:

```php
[]
```
:::

### Checking Language Existence

Use the `languageExists()` method to determine if a language 
exists or not:

```php
$i18n->languageExists('en_US');
```

:::details Technical Details
**Parameter**:

- **locale**: Optional <u>string</u> that determines the language's locale.

**Return**:  
Returns a <u>boolean</u> that determines if the language exists 
(`true`) or not (`false`).
:::

### Remove Existing Language

Use the `removeLanguage()` method to remove an existing language:

```php
$i18n->removeLanguage('en_US');
```

:::warning
Always backup your file system and database before you delete any 
language.

Deleting a language also deletes its translations. This means if 
you are running the file driver, then it will delete all translation 
files from your disk that are corresponding to this language. And 
vice versa on the database driver - it will remove the language 
and all translation relationships.
:::

::::details Technical Details
**Parameter**:

- **locale**: A <u>string</u> that defines the language's locale.

**Return**:  
It returns a <u>boolean</u> that determines if the language was 
removed (`true`) or not (`false`).

**Events**:  
[<Badge type="tip" text="LanguageDeleted" vertical="middle" />][`LanguageDeleted`]
::::

Translations
--------------------------------------------------------------------------------

### Add New Translation

Use the `addTranslation()` method to create a new translation if 
it does not exist:

```php
$i18n->addTranslation('animals.dog', 'en_US', 'A dog barks.');
```

:::details Technical Details
All incoming data will be [validated][validate add translation], 
before creating a translation.

**Parameters**:

- **key**: A <u>string</u> that determines a missing [translation key].
- **locale**: A <u>string</u> that determines the translation's locale.
- **message**: A <u>string</u> that determines the translation message, could be empty `""`. 

**Return**:  
Returns a <u>boolean</u> that determines if the translation has been 
created (`true`) or not (`false`).

**Events**:  
[<Badge type="tip" text="TranslationCreated" vertical="middle" />][`TranslationCreated`]
:::

### List All Translations (Strict)

Use the `allTranslations()` method to list all translations available:

```php
$i18n->allTranslations();
```

:::details Technical Details
**Return**:  
Returns an <u>array</u> that lists all languages. Each language contains 
a list of the translation keys and translation messages found for 
a given language:

```php
[
  'de' => [
    'Hello' => "Hallo :Name"
  ],
  'en' => [
    'Hello' => "Hello :Name",
    'animals.dog' => "",
    'i18n::languages.en' => "English"
  ],
  'fr' => [],
]
```

If no languages were found, then it returns an <u>empty array</u>:

```php
[]
```
:::

### List All Translations (Loose)

Use the `allTranslationsLoose()` method to list all translations, 
but also ensure that each translation key is represented:

```php
$i18n->allTranslationsLoose();
```

:::details Technical Details
**Return**:  
Returns an <u>array</u> that lists all languages. Each language 
contains a list of all translation keys and its translation message. 
If no translation message was found for a given language then its 
message is `null`:

```php
[
  'de' => [
    'Hello' => "Hallo :Name",
    'animals.dog' => null,
    'i18n::languages.en' => null
  ],
  'en' => [
    'Hello' => "Hello :Name",
    'animals.dog' => "",
    'i18n::languages.en' => "English"
  ],
  'fr' => [
    'Hello' => null,
    'animals.dog' => null,
    'i18n::languages.en' => null
  ],
]
```

If no languages were found, then it returns an <u>empty array</u>:

```php
[]
```
:::

### List All Translations Horizontal

Use the `allTranslationsHorizontal()` method to get a horizontal 
list of all translations:


```php
$i18n->allTranslationsHorizontal();
```

:::details Technical Details
Sometimes you may need a list of all translation keys with their 
languages and messages. This structure can be useful if you would 
like to show all translation messages next to each other, this 
is why we call it the horizontal list.

**Return**:  
Returns an <u>array</u> that lists all translation keys. Each translation 
key contains a list of the languages and translation messages:

```php
[
  'Hello' => [
    'de' => "Hallo :Name",
    'en' => "Hello :Name",
    'fr' => null,
  ],
  'animals.dog' => [
    'de' => null,
    'en' => "",
    'fr' => null,
  ],
  'i18n::languages.en' => [
    'de' => null,
    'en' => "English",
    'fr' => null
  ]
]
```

If no translation keys were found, then it returns an <u>empty array</u>:

```php
[]
```
:::

### List All Translation Keys

Use the `allTranslationKeys()` method to list all translation keys 
available:

```php
$i18n->allTranslationKeys();
```

:::details Technical Details
**Return**:  
Returns an <u>array</u> that lists each translation key available. 
Each item has a `null` value. This makes it easier to merge these 
keys with other translations.

```php
[
  'Hello' => null,
  'animals.dog' => null,
  'i18n::animals.dog' => null,
]
```

If no translation keys were found, then it returns an <u>empty array</u>:

```php
[]
```
:::

### List All Missing Translations

Use the `allMissingTranslations()` method to list all missing 
translations for a given locale:

```php
$i18n->allMissingTranslations('en_US');
```

:::details Technical Details
**Parameter**:

- **locale**: A <u>string</u> that determines the locale.

**Return**:  
Returns an <u>array</u> that lists all missing translation keys 
for a given locale:

```php
[
  "Unauthorized" => ""
  "pagination.previous" => ""
  "i18n::languages.zho" => ""
]
```

If no missing translations were found, then it returns an 
<u>empty array</u>:

```php
[]
```
:::

### Checking For Translation Existence

Use the `translationExists()` method to determine if a translation 
exists or not:

```php
$i18n->translationExists('animals.dog', 'en_US');
```

:::details Technical Details
If no locale is set it will search for the translation key in any 
language.

**Parameters**:

- **key**: A <u>string</u> that determines the [translation key].
- **locale**: Optional <u>string</u> that determines the translation's locale.

**Return**:  
Returns a <u>boolean</u> that determines if the translation exists 
(`true`) or not (`false`).
:::

### Guess Translation Key Type

Use the `translationKeyType()` method to guess the type for a 
given translation key:

```php
$i18n->translationKeyType('animals.dog'); // group
```

:::details Technical Details
**Return**:  
Returns a <u>string</u> that determines the translation key type
suggestion:

- **"single"**: A [single translation key] e.g. "Hello :Name".
- **"group"**: A [group translation key] e.g. "animals.dog".
- **"vendor"**: A [vendor translation key] e.g. "i18n::animals.dog".
:::

### Set A Translation Message

Use the `setTranslation()` method to create or update a translation 
message:

```php
$i18n->setTranslation('animals.dog', 'en_US', 'A dog barks.');
```

:::details Technical Details
This method ensures that a translation will be created regardless 
if it already exists or no language was found. It is very useful 
if you are not sure if a language or translation exists but need 
to set a message anyway.

This does not mean that the incoming data will not be validated. 
All it does is check if a language exists, if not then it will be 
created. Next, it checks if the translation already exists if it 
exists then it calls the `updateTranslation()` method, otherwise 
if it does not exist it calls the `addTranslation()` method.

**Parameters**:

- **key**: A <u>string</u> that determines a missing [translation key].
- **locale**: A <u>string</u> that determines the translation's locale.
- **message**: A <u>string</u> that determines the translation message, could be empty `""`. 

**Return**:  
Returns a <u>boolean</u> that determines if the translation has 
been created/updated (`true`) or not (`false`).

**Events**:  
[<Badge type="tip" text="LanguageCreated" vertical="middle" />][`LanguageCreated`]&nbsp; 
[<Badge type="tip" text="TranslationCreated" vertical="middle" />][`TranslationCreated`]&nbsp;
[<Badge type="tip" text="TranslationUpdated" vertical="middle" />][`TranslationUpdated`]
:::

### Import Missing Translations

Use the `importMissingTranslations()` method to import all missing 
translations:

```php
importMissingTranslations('en_US');
```


:::details Technical Details
If no locale was given (`false`) it imports all missing translations 
for each language.

**Parameters**:

- **locale**: Optional <u>false</u> or <u>string</u> that determines the locale.
:::

### Update Existing Translation

Use the `updateTranslation()` method to update an existing 
translation message:

```php
$i18n->updateTranslation('animals.dog', 'en_US', 'A dog barks.');
```

:::details Technical Details
All incoming data will be [validated][validate update translation], 
before updating a translation.

**Parameters**:

- **key**: A <u>string</u> that determines an existing [translation key].
- **locale**: A <u>string</u> that determines the translation's locale.
- **message**: A <u>string</u> that determines the translation message, could be empty `""`. 

**Return**:  
Returns a <u>boolean</u> that determines if the translation has 
been updated (`true`) or not (`false`).

**Events**:  
[<Badge type="tip" text="TranslationUpdated" vertical="middle" />][`TranslationUpdated`]
:::

### Remove Existing Translation

Use the `removeTranslation()` method to remove an existing translation:

```php
$i18n->removeTranslation('animals.dog', 'en_US');
```

:::details Technical Details
**Parameters**:

- **key**: A <u>string</u> that determines an existing [translation key].
- **locale**: A <u>string</u> that determines the translation's locale.

**Return**:  
Returns a <u>boolean</u> that determines if the translation has 
been deleted (`true`) or not (`false`).

**Events**:  
[<Badge type="tip" text="TranslationDeleted" vertical="middle" />][`TranslationDeleted`]
:::

<!--                            that's all folks!                            -->

[`LanguageCreated`]:        ../../advanced/events/#language-created
[`LanguageDeleted`]:        ../../advanced/events/#language-deleted
[`TranslationCreated`]:     ../../advanced/events/#translation-created
[`TranslationUpdated`]:     ../../advanced/events/#translation-updated
[`TranslationDeleted`]:     ../../advanced/events/#translation-deleted
[validate add language]:    ../../usage/validation/form/request/add/language/
[validate add translation]: ../../usage/validation/form/request/add/translation/
[validate update translation]: ../../usage/validation/form/request/update/translation/
[single translation key]:   ../../fundamentals/i18n/#single-translations
[group translation key]:    ../../fundamentals/i18n/#group-translations
[vendor translation key]:   ../../fundamentals/i18n/#vendor-translations
[translation key]:          ../../fundamentals/i18n/#translation-types