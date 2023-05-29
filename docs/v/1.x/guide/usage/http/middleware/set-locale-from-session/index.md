---
title: Set Locale From Session Middleware
---

Introduction
--------------------------------------------------------------------------------

You can change the application's locale depending on a user's 
[session] value. All you need to do is to [register our middleware], 
into your routes or HTTP kernel. Here is an example of how to 
register the middleware for web routes:

Register Middleware
--------------------------------------------------------------------------------

Register the middleware within the `App\Providers\RouteServiceProvider` 
service provider class:

``` php {14}
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Sirthxalot\Laravel\I18n\Http\Middleware\SetLocaleFromSession::class
    ],
];
```

Basic Usage
--------------------------------------------------------------------------------

You can now store a locale within a session. The session key is 
defined within the `i18n.locale_sk` configuration. Its default 
value is `i18n_locale`:

```php
session(['i18n_locale' => 'de_CH']);
```

The middleware now detects the locale from the session. If the 
locale is a given language then it uses the value for the 
application's locale:

```php
app()->getLocale(); // de_CH
```

<!--                            that's all folks!                            -->

[session]: https://laravel.com/docs/10.x/session
[register our middleware]: https://laravel.com/docs/10.x/middleware#registering-middleware