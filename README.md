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
- [Broadcasting](docs/broadcasting.md)
- [Events](docs/events.md)
- [Exceptions](docs/exceptions.md)
- [Whitelisting and blacklisting](docs/listing.md)
- [config](docs/config.md)
- [CLI](docs/artisan.md)
- [Gotchas](docs/gotchas.md)
- [Roadmap](#roadmap)

### Roadmap

- [x] implement broadcasting, or accessing other sockets
- [ ] implement channels
- [ ] allow creating topics or something
- [ ] prefix property on routes
- [ ] maybe write a client side library for sockets?
- [ ] extend request to expose "user" if applicable (from session)
