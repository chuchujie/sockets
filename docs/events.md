# events

### Basics

Sockets provides you with a couple of events so that you can handle incoming requests, clients leaving and the server starting and closing. Sockets uses the built in event system of laravel to provide this functionality so you can just consume them as you always have.
You might want to run some code every time a user connects to your application, or when one disconnects. sockets exposes two events for this purpose. The `\Experus\Sockets\Events\SocketConnectedEvent` when a user connects to your server, and the `\Experus\Sockets\Events\SocketDisconnectedEvent` event when a user disconnects from your server. both expose the client that just connected to the server.

### User connected

Whenever a user connects, sockets will create a `\Experus\Sockets\Core\Client\SocketClient` for it and pass it to the `\Experus\Sockets\Events\SocketConnectedEvent` event. You can use this event to check various things and even close the connection again if you find it necessary.

### User Disconnected

Whenever the connection with a client closes, sockets will fire a `\Experus\Sockets\Events\SocketDisconnectedEvent` event and pass the disconnecting `\Experus\Sockets\Core\Client\SocketClient` to it. You can use this event to free up resources held by the connection. Do note, that the user may or may not still be connected to the server when this event is fired; if the server initiates the disconnect, the client will still be connected at the time this event is fired and can still receive messages. If the client initiates the disconnection (due network latency, closing browser, ...) the client will not be available and will not receive the messages sent to the socket. Sending a message to an already closed client will *not* throw during this event.

### Server started

When the server starts, right before it enters it's event loop, sockets will dispatch the `\Experus\Sockets\Events\SocketServerStartedEvent` event passing in the `\Experus\Sockets\Contracts\Server\Server` that's about to start up. You can use this event to dynamically blacklist IP adresses (maybe load them from a database?), whitelist domains, register middleware and protocols or just display a welcome message in the CLI.

### Server closed

When the server shuts down, after closing all connections and stopping the event loop so that the server no longer accepts incoming connections. This event is fired **after** the server has shut down, so you cannot broadcast any messages or change any server related. That's why this event does not take the server instance, as the server should be considered immutable.

### No message event?

You might wonder, why is there no `SocketMessageEvent` or something similiar. The answer is simple, it would cause too much overhead for functionality that might not be used. Sockets tries to minimize the performance impact imposed by the framework as much as possible, and thus it was opted not to fire an event every time a message was received, instead you could register a global middleware since they get called on each request anyway.

### Listening for events

How do I listen for socket events? It's very simple, you can just register them along your "regular" laravel events in your Listener. If you don't know how to register events in Laravel, there's extensive [documentation](https://laravel.com/events) on the subject.
