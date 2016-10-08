# sockets
Make your laravel application realtime

## Installation

- Install the package from `experus/sockets`
- Register `\Experus\Sockets\SocketProvider` in your service providers
- Run `php artisan vendor:publish --tag=socket` to copy required files
- Run `php artisan socket:serve` to start the socket server.