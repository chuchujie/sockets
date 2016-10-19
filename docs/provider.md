# provider

### Basics

As amazing as it sounds, sockets really only needs a serviceprovider installed to work. You can do most of the customalisation in that service provider. For now, there's three things you can customize in the service provider, each with it's own documentation in depth; we'll just cover how to set them up here:
- [middleware stack](middleware.md)
- [protocols](protocols.md)
- [exception handlers](exceptions.md)

### Default

sockets attempts to find the perfect balance between unopinionated and sensible defaults. If you like the defaults, and don't have anything you want to customize you can just register the default provider in your providers.

Add the following to your *config/app.php*:
```php
// ... default laravel config
/*
 * Application Service Providers...
 */
App\Providers\AppServiceProvider::class,
App\Providers\AuthServiceProvider::class,
// App\Providers\BroadcastServiceProvider::class,
App\Providers\EventServiceProvider::class,
App\Providers\RouteServiceProvider::class,
\Experus\Sockets\SocketProvider::class, // <-- add this line

// ... default laravel config
```
This sets up sockets without middlewares, one protocol (The [experus protocol](protocols.md#default)) and an exception handler that crashes your server on exceptions (there's a reason, I swear! see [default handler](exceptions.md#default)).

### Customize sockets

Usually you'll want to configure some of these options however (especially that exception handler). You can do this by creating a subclass of `\Experus\Sockets\SocketProvider` and override the approperiate properties.

For example, usually you'll create something like this in *app/Providers/* (this is in fact what the generator creates for you):
```php
<?php

namespace App\Providers;

use Experus\Sockets\Contracts\Exceptions\Handler;
use Experus\Sockets\Core\Exceptions\DebugHandler;
use Experus\Sockets\Core\Middlewares\SocketMiddlewareStack;
use Experus\Sockets\Contracts\Middlewares\Stack;
use \Experus\Sockets\SocketServiceProvider as ServiceProvider;

class SocketServiceProvider extends ServiceProvider
{
    /**
     * All the protocols supported by this application.
     *
     * @var array
     */
    protected $protocols = [
        // register your protocols here
    ];

    /**
     * The exception handler for handling exceptions occurring when processing Websockets.
     *
     * @var Handler
     */
    protected $handler = DebugHandler::class; // set your own exception handling class here

    /**
     * The global middleware stack for the sockets runtime.
     *
     * @var Stack
     */
    protected $stack = SocketMiddlewareStack::class;
}
```

You can leave properties you don't need out, sockets will take care of them with the default settings.

### Generating a provider

Since writing provider boilerplate code is tedious and not exactly fun, sockets provides you with a command that generates the boilerplate code for you.

Simply run `php artisan socket:provider SocketProvider` and sockets will generate a provider named *SocketProvider* for you. You can obviously specify any name you want in place of *SocketProvider*. For a reference of possible parameters and available command see the [documentation](artisan.md).
