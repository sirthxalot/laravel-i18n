---
title: Set Locale From GET Parameter Middleware
---

Introduction
--------------------------------------------------------------------------------

You can change the application's locale depending on a GET parameter 
used on a request. All you need to do is to [register our middleware], 
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
        \Sirthxalot\Laravel\I18n\Http\Middleware\SetLocaleFromGetParameter::class
    ],
];
```

Basic Usage
--------------------------------------------------------------------------------

Now every client will be able to change the locale if it uses the 
correct GET parameter:

[http://localhost?locale=de_CH](http://localhost?locale=de_CH)

The middleware detects the locale from the `locale` GET parameter. 
If the given locale exists in languages, then the application's 
will be changed to it:

```php
app()->getLocale(); // de_CH
```

<!--                            that's all folks!                            -->

[register our middleware]: https://laravel.com/docs/10.x/middleware#registering-middleware