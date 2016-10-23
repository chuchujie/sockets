<?php

    /*
    |--------------------------------------------------------------------------
    | Sockets configuration file
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration of your socket server.
    | Here you can configure domain whitelists, IP blacklists and how
    | your server responds to the rest of the world.
    |
    | If you have a suggestion for additional configuration options, leave an
    | issue at https://github.com/Experus/sockets/issues
    |
    */

return [

    /*
    |--------------------------------------------------------------------------
    | Sockets host
    |--------------------------------------------------------------------------
    |
    | The sockets host defines on which hostname to listen for incoming
    | connections. '0.0.0.0' is the address to listen to incoming requests
    | from everywhere and is a sensible default. You'll usually rarely
    | change this setting.
    |
    */

    'host' => '0.0.0.0',

    /*
    |--------------------------------------------------------------------------
    | Sockets port
    |--------------------------------------------------------------------------
    |
    | The sockets port defines on which port to listen for incoming connections.
    | Make sure that the specified port is available and doesn't require
    | additional permissions to listen to. Unix based operating systems
    | often require elevated permissions to listen to ports below 1024.
    |
    */

    'port' => '9999',

    /*
    |--------------------------------------------------------------------------
    | Sockets domain whitelist
    |--------------------------------------------------------------------------
    |
    | Sockets has a built in CORS protection and by default only allows
    | localhost to connect. Here you can add more domains that are allowed to
    | connect to your application.
    |
    */

    'whitelist' => [
        'localhost',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sockets blacklist
    |--------------------------------------------------------------------------
    |
    | Here you can block specific IP adresses that you don't want connecting to
    | your socket server.
    |
    */

    'blacklist' => [
    ],

];
