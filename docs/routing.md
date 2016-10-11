# Socket routing

### Basics

The routing stack of sockets has been built to look familiar to [laravel's routing stack](https://laravel.com/docs/routing). Just like in laravel, you have a routes file where you can register your routes and their actions. You can find the routes file (named socket.php) along with the other route files after you run `php artisan vendor:publish --tag=sockets` (see [quickstart](../README.md#quickstart)).

Let's dive in with two very simple examples:
```php
$router->socket('foo', ['uses' => 'SocketController@foo']); // example 1

$router->socket('bar', function() { // example 2
    dd('Hello world');
});
```
Looks familiar, right? example 1 registers a socket route '*foo*' and when you call *foo* the routing system will call the **foo** method on the class **SocketController**. Nothing new from the laravel stack here right?

Example 2 shows a route '*bar*' which uses a closure as it's action. When you call *bar* the routing system will call the closure, which in this case just dumps 'Hello world' and kills the server (see [gotchas](gotchas.md#dd)).

Just like with the default laravel routing, you can type hint anything you want in your parameters and the container will inject it for you ([official docs](https://laravel.com/docs/controllers#dependency-injection-and-controllers)).

### Groups

So you may be wondering how you can apply [middlewares](middleware.md) to your routes, or maybe you want to assign aliases to your routes. You can achieve this with *grouping*. A route group is basically a group of routes that share a set of properties defined by the group. Let's illustrate with an example:
```php
$router->group(['namespace' => '\Foo\Bar\Baz'], function($router) {
    $router->socket('foo', ['uses' => 'SocketController@foo']);
});
```
Instead of the default controller namespace for sockets (*App\Sockets\Controllers*) the **SocketController** registered for *foo* will be resolved as **\Foo\Bar\Baz\SocketController**. This is handy if you have changed your namespace to something else than App, of you just want to place them in a different folder.

You can set other properties than just a namespace with route groups though, all properties in a group will be merged with the properties you set on your route. Below is a list of all the supported properties on routes and groups:
- **namespace**: sets the namespace in which to resolve the controller.
- **middlewares**: set the middlewares to be used for this route specifically (see [middlewares](middleware.md) for more info).
- **uses** specifies the controller action to use for this route (*it's not recommended to share this in groups, but it's possible*)
- **prefix**: prefix your route(s) with the given prefix (**on the roadmap**).

### Route properties

So you have seen how to share properties like namespace and middlewares over multiple routes. What if you want to set some properties for only one route? You can specify those properties in the array alongside the *uses* property (*uses* is in fact a property like all the rest):
```php
$router->socket('foo', [
    'uses' => 'SocketController@foo',
    'middlewares' => [RedirectIfNotAuthenticated::class, CheckAuthToken::class],
]);
```

### Channels

You can group a set of routes together in a *channel* using the `channel` method. **Currently channels are not implemented in the sockets runtime and registering sockets in a channel won't do anything. The method is defined for future use.**

You can define a channel and register routes to it very similiar to [groups](#Groups):
```php
$router->channel('bar', function($router) {
    $router->socket('foo', ['uses' => 'SocketController@foo']);
});
```
This defines a channel *bar* with a route *foo* in it.

### Generating controllers

Since writing controller boilerplate code is tedious and not exactly fun, sockets provides you with a command that generates the boilerplate code for you.

Simply run `php artisan socket:controller SocketController` and sockets will generate a controller named *SocketController* for you. You can obviously specify any name you want in place of *SocketController*. For a reference of possible parameters and available command see the [documentation](artisan.md).