# artisan

### Basics

Integrating a package or framework often involves a lot of boilerplate code to get even the simplest example working. Sockets tries to avoid as much of the boilerplate and tedious work as possible to let you focus on developing awesome things, or just fiddle around quickly.

### Serve command

The serve command allows you to start the socket server and specify some options on how to start it. You can also just run the server by running the `socket` file generated when running `php artisan vendor:publish --tag=sockets`, but running it through artisan gives you more freedom and a nicer stacktrace when stuff breaks.

The syntax of the command looks like this:
```sh
php artisan socket:serve {--port=} {--local}
```
Let's break those arguments down:
- **port** allows you to specify an alternative port (default 9999). example: `php artisan socket:serve --port=1234`
- **local** allows you to run the server locally (by default available for everyone). example: `php artisan socket:serve --local`

### Setup command

Sockets provides a command that will help you setup your laravel environment for working with WebSockets in one go. The setup command will ask you what you want to setup, and will guide you through setting up your environment and structure.

The syntax of the command looks like this:
```sh
php artisan socket:setup
```
That's it, no arguments needed.

### Generate controller command

The controller command generates the boilerplate code for setting up controllers that handle incoming socket requests. It sets up directory structures for you and provides you with an empty controller class so you can hack away.

The generator will deduce where it needs to put the generated controller based on the root directory and the namespace.

The syntax of the command looks like this:
```sh
php artisan socket:controller {name} {--namespace=} {--dir}
```
Let's break those arguments down:
- **name** the name of the controller to generate (*required*). Example: `php artisan socket:controller SocketController`
- **namespace** Allows you to specify a custom namespace (default: `App\Sockets\Controllers`). Example: `php artisan socket:controller SocketController --namespace='Foo\Bar\Baz'`
- **dir** Allows you to specify a different root directory (default: *app/*). Example: `php artisan socket:controller SocketController --dir=src`

### Generate middleware command

The middleware command generates the boilerplate code for setting up middlewares. It sets up directory structures for you and provides you with an empty middleware class so you can hack away.

The generator will deduce where it needs to put the generated middleware based on the root directory and the namespace.

The syntax of the command looks like this:
```sh
php artisan socket:middleware {name} {--namespace=} {--dir}
```
Let's break those arguments down:
- **name** the name of the middleware to generate (*required*). Example: `php artisan socket:middleware SocketMiddleware`
- **namespace** Allows you to specify a custom namespace (default: `App\Sockets\Middlewares`). Example: `php artisan socket:middleware SocketMiddleware --namespace='Foo\Bar\Baz'`
- **dir** Allows you to specify a different root directory (default: *app/*). Example: `php artisan socket:middleware SocketMiddleware --dir=src`

### Generate stack command

The stack command generates the boilerplate code for setting up stacks. It sets up directory structures for you and provides you with an empty stack class so you can hack away.

The generator will deduce where it needs to put the generated stack based on the root directory and the namespace.

The syntax of the command looks like this:
```sh
php artisan socket:stack {name} {--namespace=} {--dir}
```
Let's break those arguments down:
- **name** the name of the stack to generate (*required*). Example: `php artisan socket:stack SocketStack`
- **namespace** Allows you to specify a custom namespace (default: `App\Sockets\Middlewares`). Example: `php artisan socket:stack SocketStack --namespace='Foo\Bar\Baz'`
- **dir** Allows you to specify a different root directory (default: *app/*). Example: `php artisan socket:stack SocketStack --dir=src`

### Generate serviceprovider command

The provider command generates the boilerplate code for setting up a serviceprovider. It sets up directory structures for you and provides you with an empty provider class so you can configure your app right away.

The generator will deduce where it needs to put the generated provider based on the root directory and the namespace.

The syntax of the command looks like this:
```sh
php artisan socket:provider {name} {--namespace=} {--dir}
```
Let's break those arguments down:
- **name** the name of the provider to generate (*required*). Example: `php artisan socket:provider SocketProvider`
- **namespace** Allows you to specify a custom namespace (default: `App\Providers`). Example: `php artisan socket:provider SocketProvider --namespace='Foo\Bar\Baz'`
- **dir** Allows you to specify a different root directory (default: *app/*). Example: `php artisan socket:provider SocketProvider --dir=src`

### Generate handler command

The handler command generates the boilerplate code for setting up handlers. It sets up directory structures for you and provides you with an empty handler class so you can hack away.

The generator will deduce where it needs to put the generated handler based on the root directory and the namespace.

The syntax of the command looks like this:
```sh
php artisan socket:handler {name} {--namespace=} {--dir}
```
Let's break those arguments down:
- **name** the name of the handler to generate (*required*). Example: `php artisan socket:handler ExceptionHandler`
- **namespace** Allows you to specify a custom namespace (default: `App\Sockets\Exceptions`). Example: `php artisan socket:handler ExceptionHandler --namespace='Foo\Bar\Baz'`
- **dir** Allows you to specify a different root directory (default: *app/*). Example: `php artisan socket:handler ExceptionHandler --dir=src`

### Generate catcher command

The catcher command generates the boilerplate code for setting up catchers. It sets up directory structures for you and provides you with an empty catcher class so you can hack away.

The generator will deduce where it needs to put the generated catcher based on the root directory and the namespace.

The syntax of the command looks like this:
```sh
php artisan socket:catcher {name} {--namespace=} {--dir}
```
Let's break those arguments down:
- **name** the name of the catcher to generate (*required*). Example: `php artisan socket:catcher FooCatcher`
- **namespace** Allows you to specify a custom namespace (default: `App\Sockets\Exceptions`). Example: `php artisan socket:catcher FooCatcher --namespace='Foo\Bar\Baz'`
- **dir** Allows you to specify a different root directory (default: *app/*). Example: `php artisan socket:catcher FooCatcher --dir=src`
