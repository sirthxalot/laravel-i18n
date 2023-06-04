---
title: Set Translation Form Request
---

Introduction
--------------------------------------------------------------------------------

The `SetTranslationRequest` [form request] can be used to validate
data before a translation will be set. These validation rules are
not implemented within any method of the I18n service since they
are obsolete. However, you can use the form request in any method
you want:

```php
use Sirthxalot\Laravel\I18n\Http\Requests\SetTranslationRequest;

public function set(SetTranslationRequest $request) 
{
    //...
}
```

Validation Rules
--------------------------------------------------------------------------------

### locale

A <u>string</u> that determines a valid and existing locale,
e.g. "en", "en_US" or "eng_US".

- required
- min. 2 characters
- max. 6 characters
- must follow ISO-15897 convention

### key

A <u>string</u> that determines a valid [translation key], e.g.
"Hello World", "animals.dog" or "i18n::animals.dog".

- required
- max. 65'535 characters

### message

A <u>string</u> that determines a valid message, e.g.
"Hello :Name", "A dog barks.", "".

- max. 65'535 characters

<!--                            that's all folks!                            -->

[form request]: https://laravel.com/docs/10.x/validation#form-request-validation
[translation key]: ../../../../../../fundamentals/i18n/#translation-types
