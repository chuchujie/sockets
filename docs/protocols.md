# protocols

**Protocols are under active development, not all features may be documented or even written**

### Basics

If you have been reading up on the [routing](routing.md) documentation, you might wonder how sockets knows how what request matches which route; after all we're working on a single stream instead of separate requests to an URL, or how it knows what part is content. The answer is *it doesn't*, sockets leverages this to a mechanism called **protocols**. A protocol specifies how a request should be interpreted by sockets, which allows you to customize sockets to fit your need, rather than having to change your application to match sockets (stupid?) rules. Want to use XML to communicate between websockets? go ahead, write a protocol for it. Want to use plain text or even go crazy with binary schemas? Write a protocol and you're good.

### Writing your own

Writing your own protocol is very simple. First you implement the `\Experus\Sockets\Contracts\Protocols\Protocol` protocol:
```php
<?php

namespace Sockets\Protocols;

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Class FooProtocol a dummy protocol for sockets.
 * @package Sockets\Protocols
 */
class FooProtocol implements Protocol
{

    /**
     * Extract the intended route from the request.
     *
     * @param SocketRequest $request
     * @return string
     */
    public function route(SocketRequest $request)
    {
        return 'foo'; // every route is foo
    }
}
```

Next you give it a name (we'll name this example protocol *foo*) and register it in your [provider](docs/provider.md#customize-sockets):
```php
    /**
     * The supplied protocol stack.
     *
     * @var array
     */
    protected $protocols = [
        'foo' => \App\Sockets\Protocols\FooProtocol::class,
    ];
```

### Client side

Now say you registered 2 protocols in your application, how do you tell sockets which protocol to use? Say we registered a protocol named `foo` and a protocol named `bar` in our application, we could connect to the server like this:
```js
const protocol = 'foo'; // either 'foo' or 'bar'
const socket = new WebSocket('ws://localhost:9999', protocol);
```
For more information on websockets on the clientside, you can start [here](https://developer.mozilla.org/en-US/docs/Web/API/WebSocket). There's an ongoing discussion wether or not to implement a client side Javascript library in sockets. Feel free to create an issue, or endorse an existing issue asking for this feature; if there's enough demand it'll definitely make it's way in!

### Default

sockets comes with a default protocol to play around with, called `experus` internally. This section will shorty describe how this protocol works, for all you know it's just fine for you and you don't need a custom protocol.

**this section is incomplete due the Experus protocol still being under development**

### Generating protocols

**Currently there's no command available to generate protocols**