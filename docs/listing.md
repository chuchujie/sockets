# listing

### Basics

Sometimes users aren't playing by the rules, or outright try to break your application. Sockets makes it very simple to create blacklists of IP addresses so that you can keep those malicious users out. We also build in a domain whitelisting feature that allows you to specify from which domains you accept socket connections.

### Blacklisting an IP address

Say you have a user that's been spamming your server all day long, and you want to block his IP address. You could make a middleware which checks the IP address of the user, but that still causes the overhead of opening a connection for him. By blacklisting an IP, it won't even reach the socket server and no connection will be made with the address.

Blacklisting an IP address is fortunatly very easy (see the [documentation](https://laravel.com/docs/container#the-make-method) for the `resolve` method):
```php
resolve(\Experus\Sockets\Contracts\Kernel::class)->block('192.168.0.10');
```

### Whitelisting a domain

If you want a domain to be able to connect to your socket server, you will need to whitelist that domain or you'll receive a nasty 403 when attempting to connect to the socket server.

Whitelisting a domain is very similiar to blacklisting one (again, see the [documentation](https://laravel.com/docs/container#the-make-method) for the `resolve` method):
```php
resolve(\Experus\Sockets\Contracts\Kernel::class)->allow('example.com');
```
