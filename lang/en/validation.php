<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'iso' => [
        '15897' => 'The :attribute does not match the ISO-15897 convention e.g. "en_US".',
    ],

    'language' => [
        'exists' => 'The :attribute field must be an existing language.',
        'missing' => 'The :attribute field must be a missing language.',
    ],

    'translation' => [
        'key' => [
            'exists' => 'The :attribute field must be an existing translation key.',
            'missing' => 'The :attribute field must be a missing translation key.',
        ],
    ],

];
