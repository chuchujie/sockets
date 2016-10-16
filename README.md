# sockets
 
[![Build Status](https://travis-ci.org/Experus/sockets.svg?branch=dev)](https://travis-ci.org/Experus/sockets) [![Latest Stable Version](https://poser.pugx.org/experus/sockets/v/stable)](https://packagist.org/packages/experus/sockets) [![License](https://poser.pugx.org/experus/sockets/license)](https://packagist.org/packages/experus/sockets) [![Monthly Downloads](https://poser.pugx.org/experus/sockets/d/monthly)](https://packagist.org/packages/experus/sockets)

*Make your laravel application realtime.*

> Sockets is a laravel package that allows your application to handle Websockets without the need of an external NodeJS server, fiddling with Redis servers to wire up your Laravel application with that NodeJS server. Sockets aims to integrate Laravel with a high performance real time WebSockets framework written in PHP, making working with WebSockets as easy as Laravel makes working with HTTP requests.

## Installation (quickstart)

- Install the package from `experus/sockets`
- Register `\Experus\Sockets\SocketServiceProvider` in your service providers
- Run `php artisan socket:setup` to set up everything (skip steps below, or use steps below and skip this)
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

- [x] write unit tests
- [x] provide in depth documentation for each subject
- [ ] implement channels
- [ ] allow creating topics or something
- [ ] prefix property on routes
- [x] command that sets up everything (vendor:publish, generate provider, middleware, controller, handler and protocols)
- [ ] maybe write a client side library for sockets?
- [ ] protocols need to specify how to extract payload
- [x] generalize json() method on request to parse (ex: for xml)
- [ ] finish experus protocol
- [x] named middlewares
- [x] generate protocols
- [x] fire event on startup of server
- [x] fire event on shutdown of server
- [ ] add session support
- [ ] extend request to expose "user" if applicable (from session)
- [ ] extend request to expose session
- [ ] extend request to expose origin (IP)
- [ ] Protocol should validate contents (Experus throws unknown property when not passing in the required properties)
- [ ] if no protocol is registered (or found) -> exception message should be clearer (gives ReflectionException(-1) now)
- [ ] move replace socket provider from setup command to create provider command