<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Session;

use Guzzle\Http\Message\RequestInterface as Request;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Session\SessionManager;

/**
 * Class SocketSession wrapper around the laravel sessions for the sockets runtime.
 * @package Experus\Sockets\Core\Session
 */
class SocketSessionFactory
{
    /**
     * The name laravel uses for it's session cookie.
     *
     * @var string
     */
    private $cookie_name;

    /**
     * The laravel session we're wrapping.
     *
     * @var SessionManager
     */
    private $session;

    /**
     * The laravel encryption driver.
     *
     * @var Encrypter
     */
    private $encrypter;

    /**
     * SocketSession constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->cookie_name = $app->make(Config::class)->get('session.cookie');
        $this->session = new SessionManager($app);
        $this->encrypter = $app->make(Encrypter::class);
    }

    /**
     * Build the session manager for given request.
     *
     * @param Request $request
     * @return SessionManager
     */
    public function make(Request $request)
    {
        $cookie = substr($request->getCookie($this->cookie_name), 0, 276);

        try {
            $id = $this->encrypter->decrypt($cookie);
            $this->session->setId($id);
        } catch (DecryptException $ex) {
        }

        $this->session->start();

        return $this->session;
    }
}