# events

### Basics

You might want to run some code every time a user connects to your application, or when one disconnects. sockets exposes two events for this purpose. The `\Experus\Sockets\Events\SocketConnectedEvent` when a user connects to your server, and the `\Experus\Sockets\Events\SocketDisconnectedEvent` event when a user disconnects from your server. both expose the client that just connected to the server.

### No message event?

You might wonder, why is there no `SocketMessageEvent` or something similiar. The answer is simple, it would cause too much overhead for functionality that might not be used. Sockets tries to minimize the performance impact imposed by the framework as much as possible, and thus it was opted not to fire an event every time a message was received, instead you could register a global middleware since they get called on each request anyway.

### Listening for events

How do I listen for socket events? It's very simple, you can just register them along your "regular" laravel events in your Listener. If you don't know how to register events in Laravel, there's extensive [documentation](https://laravel.com/events) on the subject.
