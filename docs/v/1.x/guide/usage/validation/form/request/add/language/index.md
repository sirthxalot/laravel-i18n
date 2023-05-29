---
title: Add Language Form Request
---

Introduction
--------------------------------------------------------------------------------

The `AddLanguageRequest` [form request] will be used to validate 
data before a language will be added. It contains the common 
validation rules provided by {{ $site.title }}. You can use the 
form request in any method you want:

```php
use Sirthxalot\Laravel\I18n\Http\Requests\AddLanguageRequest;

public function add(AddLanguageRequest $request) 
{
    //...
}
```

Validation Rules
--------------------------------------------------------------------------------

### locale

A <u>string</u> that determines a valid and non-existing locale, 
e.g. "en", "en_US" or "eng_US".

- required
- min 2 characters
- max 6 characters
- follow ISO-15897 convention
- language must be missing

Replacing Form Request
--------------------------------------------------------------------------------

You can change the validation rules for new languages by [creating 
your own form request][form request]. However, we do not recomend 
to do this.

After creating your form request you can register it within the 
I18n configuration (`config/i18n.php`):

``` php {4}
return [
    'validation' => [
        'language' => [
            'add' => \Sirthxalot\Laravel\I18n\Http\Requests\AddLanguageRequest::class,
        ],
    ],
]
```

:::warning
Changing the form request can be dangerous and may lead into problems. 
Please do not change the form requests if you are not 100% sure what 
you are doing - stick to our plan. We also may not support you if 
you are using custom form requests.
:::

<!--                            that's all folks!                            -->

[form request]: https://laravel.com/docs/10.x/validation#form-request-validation