# broadcasting

### Basics

Up until now you've seen how you can register a route, respond to events happening on them, subject incoming requests to middlewares and respond to a request. You might be wondering how you can send a message to *all* connections on your server, or a subgroup of them. For this, sockets employs broadcasting.

### Broadcaster

If you want to send a message to a connection other than the one you've received a message from, you'll need a broadcaster. Sockets provides the `\Experus\Sockets\Contracts\Server\Broadcaster` contract for this, which contains 2 methods:
- **clients** returns all clients connected, optionally pass a filter to get a subset of users.
- **broadcast** send a message to all clients connected, optionally pass a filter to get a subset of users.

Let's add an example for each, starting with clients (see [here](https://laravel.com/docs/container#resolving) for documentation on `resolve`):
```php
$broadcaster = resolve(\Experus\Sockets\Contracts\Server\Broadcaster::class); // resolve the broadcaster
$all = $broadcaster->clients(); // get all clients
$cool = $broadcaster->clients(function (\Experus\Sockets\Core\Client\SocketClient $client) { // returns all 'cool' users
    return iscool($client); // some filter function that checks if our user is cool!
});
```
The example above shows how you can retrieve all users (stored in `$all`) or filter which user you want (stored in `$cool`).

The broadcast method works very similiar (internally it employs the filter method to check which clients to send to):
```php
$broadcaster = resolve(\Experus\Sockets\Contracts\Server\Broadcaster::class); // resolve the broadcaster
$broadcaster->broadcast(['message' => 'Hello world!']); // send `['message' => 'Hello world!']` to all users
$broadcaster->broadcast(['message' => 'Hello world!'], function (\Experus\Sockets\Core\Client\SocketClient $client) { // sends only to the 'cool' users
    return iscool($client); // some filter function that checks if our user is cool!
});
```

### Topics and channels
**Currently, there is no channel or topic system, but it's on the roadmap and under development. Stay tuned!**
