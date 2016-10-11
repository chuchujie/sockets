# sockets
Make your laravel application realtime

## Installation (quickstart)

- Install the package from `experus/sockets`
- Register `\Experus\Sockets\SocketProvider` in your service providers
- Run `php artisan vendor:publish --tag=sockets` to copy required files
- Run `php artisan socket:serve` to start the socket server.

## Table of contents

- [Routing](docs/routing.md)
- [Customisation](docs/provider.md)
- [Protocols](docs/protocols.md)
- [Middleware](docs/middleware.md)
- [Events](docs/events.md)
- [Exceptions](docs/exceptions.md)
- [Whitelisting and blacklisting](docs/listing.md)
- [CLI](docs/artisan.md)
- [Gotchas](docs/gotchas.md)
- [Roadmap](#roadmap)

### Roadmap

- [ ] write unit tests
- [ ] provide in depth documentation for each subject
- [ ] implement channels
- [ ] allow creating topics or something
- [ ] prefix property on routes
- [ ] command that sets up everything (vendor:publish, generate provider, middleware, controller, handler and protocols)
- [ ] maybe write a client side library for sockets?
- [ ] protocols need to specify how to extract payload
- [ ] generalize json() method on request to parse (ex: for xml)
- [ ] finish experus protocol
- [ ] named middlewares
- [ ] generate protocols
- [ ] fire event on startup of server
- [ ] fire event on shutdown of server