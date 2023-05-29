<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    |
    | These options help to change the database setup used for
    | languages and translations. Feel free to modify the table
    | names or database connection, used for I18n models,
    | migrations etc.
    |
    */

    'database' => [
        'connection' => env('I18N_DB_CONNECTION', 'mysql'),
        'tables' => [
            'languages' => env('I18N_DB_LANGUAGE_TABLE', 'languages'),
            'translations' => env('I18N_DB_TRANSLATION_TABLE', 'translations'),
        ],
        'models' => [
            'language' => \Sirthxalot\Laravel\I18n\Models\Language::class,
            'translation' => \Sirthxalot\Laravel\I18n\Models\Translation::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Driver
    |--------------------------------------------------------------------------
    |
    | This option sets the driver used for the translation (I18n) service.
    |
    | Supported drivers: "file" or "database".
    |
    */

    'driver' => env('I18N_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Locale Session Key
    |--------------------------------------------------------------------------
    |
    | This option sets the session key used in order to detect
    | the locale for a user. Feel free to modify this session
    | key, but try to avoid just "locale" since this key may
    | be used elsewhere.
    |
    */

    'locale_sk' => 'i18n_locale',

    /*
    |--------------------------------------------------------------------------
    | Translation Scan Paths
    |--------------------------------------------------------------------------
    |
    | This option contains a list of translation paths.
    | These paths will be scanned for any translation messages.
    |
    */

    'scan_paths' => [app_path(), base_path('routes'), resource_path()],

    /*
    |--------------------------------------------------------------------------
    | Translation Methods
    |--------------------------------------------------------------------------
    |
    | This option contains a list of translation methods that are
    | available. Here you can register custom translation methods
    | e.g. `say()` or something similar.
    |
    */

    'translation_methods' => ['__', 'trans_choice', '@lang'],

    /*
    |--------------------------------------------------------------------------
    | Validation Form Requests
    |--------------------------------------------------------------------------
    |
    | This option contains a list of form requests used in order
    | to validate data. Here you can swap out the validation rules
    | or messages used for a given action. Create a new form request
    | and change the namespace to load your custom rules.
    |
    */

    'validation' => [
        'language' => [
            'add' => \Sirthxalot\Laravel\I18n\Http\Requests\AddLanguageRequest::class,
        ],
        'translation' => [
            'add' => \Sirthxalot\Laravel\I18n\Http\Requests\AddTranslationRequest::class,
            'update' => \Sirthxalot\Laravel\I18n\Http\Requests\UpdateTranslationRequest::class,
        ],
    ],

];
