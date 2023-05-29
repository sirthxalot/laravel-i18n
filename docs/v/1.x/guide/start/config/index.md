---
title: I18n Configuration
---

Database Connection
--------------------------------------------------------------------------------

A <u>string</u> that determines the [database connection] used for 
I18n models, migrations etc.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| `I18N_DB_CONNECTION` | `i18n.database.connection` | `"mysql"`

Language Table Name
--------------------------------------------------------------------------------

A <u>string</u> that determines the table name used to store 
language records.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| `I18N_DB_LANGUAGE_TABLE` | `i18n.database.tables.languages` | `"languages"`

Language Model
--------------------------------------------------------------------------------

A <u>string</u> that determines the class name used for the 
language model.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.database.models.language` | `\Sirthxalot\Laravel\I18n\Models\Language::class`

Translation Table Name
--------------------------------------------------------------------------------

A <u>string</u> that determines the table name used to store 
translation records.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| `I18N_DB_TRANSLATION_TABLE` | `i18n.database.tables.translations` | `"translations"`

Translation Model
--------------------------------------------------------------------------------

A <u>string</u> that determines the class name used for the 
translation model.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.database.models.translation` | `\Sirthxalot\Laravel\I18n\Models\Translations::class`


I18n Driver
--------------------------------------------------------------------------------

A <u>string</u> that determines the driver used for the I18n service.

**Supported Drivers**: "file" or "database"

| .env | config key | default value |
| :--- | :--------- | :------------ |
| `I18N_DRIVER` | `i18n.driver` | `"database"`

Locale Session Key
--------------------------------------------------------------------------------

A <u>string</u> that determines the session key that could be used 
in order to load the locale for a user.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.locale_sk` | `"i18n_locale"`

Translation Scan Paths
--------------------------------------------------------------------------------

An <u>array</u> that lists each directory path were the I18n 
service should scan for missing translations.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.scan_paths` | `[app_path(), base_path('routes'), resource_path()]`

Translation Methods
--------------------------------------------------------------------------------

An <u>array</u> that lists each translation method used within 
the I18n service to scan for missing translations.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.translation_methods` | `['__', 'trans_choice']`

Language Add Form Request
--------------------------------------------------------------------------------

A [form request][form requests] class that determines the validation 
used to validate data before adding a language.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.validation.language.add` | `Sirthxalot\Laravel\I18n\Http\Requests\AddLanguageRequest::class`

Translation Add Form Request
--------------------------------------------------------------------------------

A [form request][form requests] class that determines the validation 
used to validate data before adding a translation.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.validation.translation.add` | `Sirthxalot\Laravel\I18n\Http\Requests\AddTranslationRequest::class`

Translation Update Form Request
--------------------------------------------------------------------------------

A [form request][form requests] class that determines the validation 
used to validate data before updating a translation.

| .env | config key | default value |
| :--- | :--------- | :------------ |
| - | `i18n.validation.translation.update` | `Sirthxalot\Laravel\I18n\Http\Requests\UpdateTranslationRequest::class`

<!--                            that's all folks!                            -->

[database connection]: https://laravel.com/docs/10.x/database#configuration
[form requests]: https://laravel.com/docs/10.x/validation#form-request-validation