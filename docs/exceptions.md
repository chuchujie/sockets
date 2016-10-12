# exceptions

### Basics

Every application will eventually throw an exception, the user did something wrong or you made a mistake writing your awesome application, either way you will need a way to handle these exceptions before they crash your application. Luckily, sockets provides you with a fluent way to deal with exceptions; and if you have worked with [experus/exceptions](https://github.com/Experus/exceptions) (go check it out if you haven't!) before you'll find a lot of familiar things. Just like exceptions, sockets takes the approach of catchers and handlers. We'll explain the difference below.

### Handlers

A *handler* in sockets is a class that takes in all the thrown exceptions, checks if there's a class registered that can process that exception and will take care of dispatching it. You can view a handler as a dispatcher for exceptions (in experus/exceptions the class happens to be called Dispatcher).

Defining a handler in sockets is very easy:
```php
<?php

namespace App\Sockets\Exceptions;

use Exception;
use Experus\Sockets\Contracts\Exceptions\Handler;
use Experus\Sockets\Core\Client\SocketClient;

/**
 * Class ExceptionHandler
 * @package App\Sockets\Exceptions
 */
class ExceptionHandler extends Handler
{
    /**
     * The registered exception catchers.
     *
     * @var array
     */
    protected $catchers = [
        FooHandler::class => [
            FooException::class,
            BazException::class,
        ],
    ];
}
```
The `ExceptionHandler` class as shown above has one handler registered (`FooHandler`) which is capable of handling 2 exceptions: `FooException` and `BazException`. This means that if somewhere in your application `FooException` or `BazException` is thrown, the `ExceptionHandler` wil dispatch it to `FooHandler`. You can register as much handlers and exceptions as you want.

What happens if an exception is thrown that you didn't register a handler for? The handler class will call a method called `unhandled`. Let's extend our `ExceptionHandler` with a callback for unhandled exceptions:
```php
<?php

namespace App\Sockets\Exceptions;

use Exception;
use Experus\Sockets\Contracts\Exceptions\Handler;
use Experus\Sockets\Core\Client\SocketClient;

/**
 * Class ExceptionHandler
 * @package App\Sockets\Exceptions
 */
class ExceptionHandler extends Handler
{
    /**
     * The registered exception catchers.
     *
     * @var array
     */
    protected $catchers = [
        FooHandler::class => [
            FooException::class,
            BazException::class,
        ],
    ];

    /**
     * Called when an exception does not have a registered handler.
     *
     * @param SocketClient $client
     * @param Exception $exception
     * @return array|null|object
     * @throws Exception if the exception thrown is fatal.
     * @note When throwing an exception in the handler, the entire server will halt.
     */
    public function unhandled(SocketClient $client, Exception $exception)
    {
        throw $exception; // crash application.
    }
}
```
So what happens if we throw an exception in our `unhandled` handler? The server will halt and print out a message, throwing in the `unhandled` method is fatal, so make sure you don't throw unless **absolutely necessary**!

### Catchers

A catcher in sockets is a class that takes in an exception and processes it. You could see it as the `catch` part of a try catch clause. If the handler class dispatches exceptions, it dispatches them to a catcher. There is no contract available to implement for catchers for the simple reason that it would prevent you from type hinting your catchers `handle` method.

A catcher only needs to have a `handle` method which takes a `\Experus\Sockets\Core\Client\SocketClient` and an exception which you can type hint specialized:
```php
<?php

namespace App\Sockets\Exceptions\Catchers;

use Experus\Sockets\Core\Client\SocketClient;

/**
 * Class NotFoundExceptionCatcher handles NotFoundExceptions
 * @package App\Sockets\Exceptions\Catchers
 */
class NotFoundExceptionCatcher
{
    /**
     * NotFoundExceptionCatcher constructor.
     */
    public function __construct()
    {
        // TODO type hint dependencies
    }

    /**
     * Handle the exception thrown on the connection.
     *
     * @param SocketClient $client
     * @param \Exception $exception
     * @todo Specialize exception type hint.
     */
    public function handle(SocketClient $client, \Exception $exception)
    {
        dd($exception);
    }
}
```
Since catchers are resolved from the container, you can type hint any dependencies you need in the constructor.

### Default

Sockets ships with the `\Experus\Sockets\Core\Exceptions\DebugHandler` by default, which throws the exception in it's `unhandled` method, causing the server to crash. You might ask yourself, why would anyone want that? Simple,  it helps you detect which exceptions you haven't caught yet, as an uncaught exception will crash your server and if you start your server using `php artisan socket:serve -vvv` you get a nice verbose stacktrace from laravel telling you what went wrong where. While this might be great in debugging, you obviously **never ever ever** want this in production.

### Alternative default

Sockets ships with a second handler out of the box; the `\Experus\Sockets\Core\Exceptions\NoopHandler`. The NoopHandler silently ignores your exception and just passes them back to the sockets' kernel internal handler which send the exception to the client and forcefully closes the connection (after the SocketClosedEvent has been dispatched, allowing you to gracefully handle it's closing). This is a slightly more sane option to use in production, but it's there as a fallback, usually you'll want to ship with a custom handler.

### Generating handlers

Since writing handler boilerplate code is tedious and not exactly fun, sockets provides you with a command that generates the boilerplate code for you.

Simply run `php artisan socket:handler ExceptionHandler` and sockets will generate a handler named *ExceptionHandler* for you. You can obviously specify any name you want in place of *ExceptionHandler*. For a reference of possible parameters and available command see the [documentation](artisan.md).

### Generating catchers

Since writing catcher boilerplate code is tedious and not exactly fun, sockets provides you with a command that generates the boilerplate code for you.

Simply run `php artisan socket:catcher SocketExceptionCatcher` and sockets will generate a catcher named *SocketExceptionCatcher* for you. You can obviously specify any name you want in place of *SocketExceptionCatcher*. For a reference of possible parameters and available command see the [documentation](artisan.md).
