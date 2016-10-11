# sockets
Make your laravel application realtime

## Installation (quickstart)

- Install the package from `experus/sockets`
- Register `\Experus\Sockets\SocketProvider` in your service providers
- Run `php artisan vendor:publish --tag=socket` to copy required files
- Run `php artisan socket:serve` to start the socket server.

### Routing

The socket routing stack has been build to imitate the Laravel routing stack where approperiate.

Currently you can do three things, register a route, group routes and create a channel.

```php
$router->socket('foo', ['uses' => 'SocketController@foo']); // register a route

$router->group(['middleware' => 'test'], function($router) {
    $router->socket('bar', ['uses' => 'SocketController@bar']); // Applies 'test' middleware to bar automatically
});

$router->channel('foobar', function($router) {
    $router->socket('baz', ['uses' => 'SocketController@baz']); // 'baz' is now part of channel 'foobar'
});
```

### Customize sockets

You can very easily customize how the sockets package works by subclassing the `\Experus\Sockets\SocketProvider` class.
Doing so allows you to specify custom protocols and register a middleware stack.

Simply create a service provider like this (we'll explain in detail in the respective chapters)
```php
<?php

namespace App\Providers;

use \Experus\Sockets\SocketProvider as ServiceProvider;

class SocketServiceProvider extends ServiceProvider
{
    /**
     * The middlewares to apply to incoming requests.
     *
     * @var array
     */
    protected $middlewares = [
        \Foo\Bar\CustomMiddleware::class,
    ];

    /**
     * All the protocols supported by this application.
     *
     * @var array
     */
    protected $protocols = [
        'custom' => \Foo\Baz\CustomProtocol::class,
    ];
}
```

### Protocols

sockets provides it's own default protocol for parsing routes and payloads,
but you can provide your own if the default does not suit your needs.

You simply implement the `\Experus\Sockets\Contracts\Protocols\Protocol` contract.
In the constructor you type hint the request, sockets will automatically provide it to you there.

Then you register your protocol in your SocketServiceProvider (as shown above) and give it a name.

On the client side you can create a web socket like this:
```js
var socket = new WebSocket('ws://localhost:9999', 'custom'); // opens a socket with the 'custom' protocol
```

sockets will automatically pass everything through the correct protocol handler.

### Middleware

Sometimes you want to do checks or execute some logic before a request is passed to your handler code.

Just like with normal requests, sockets allows you to write middlewares and bind them to routes or global stack.
Simply implement the `\Experus\Sockets\Contracts\Middlewares\Middleware` contract.

Then you can bind them in the `$middleware` property of your custom SocketServiceProvider (as shown above).

### Events

Currently sockets will broadcast 2 events for you:
- `\Experus\Sockets\Events\SocketConnectedEvent` when a connection is opened
- `\Experus\Sockets\Events\SocketDisconnectedEvent` when a connection closes. (Note that you get the client passed, and writing to it will not throw but it is not guaranteed the client will receive the message)

You can register listeners for these events as if they were [regular laravel events](https://laravel.com/docs/events). *it just works*

### Exceptions (catchers and handlers)

TODO (note the existence of NoopHandler and DebugHandler)

### Whitelisting and blacklisting

TODO

### Artisan helpers

TODO

### Roadmap

- write unit tests
- provide in depth documentation for each subject
- implement channels
- allow creating topics or something