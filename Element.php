<?php

namespace attitude\Elements;

use \attitude\Elements\Singleton_Prototype;

class Server_Element extends Singleton_Prototype
{
    private $api   = null;
    private $auth  = null;

    protected function __construct()
    {
        if (!defined('BOOT_HAS_PASSED')) {
            @header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            @header('X-Status-Debug: Please initiate Boot sequence.');

            trigger_error('Please initiate sequence via `Boot_Element::instance();`', E_USER_ERROR);

            die;
        }

        $_request = new Request_Element(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI_ARRAY'],
            $_SERVER['argv'],
            $_SERVER['HTTP_ACCEPT'],
            $GLOBALS['_'.$_SERVER['REQUEST_METHOD']]
        );

        DependencyContainer::set('REQUEST', $_request);

        try {
            $this->api = new API_Element($_request);
        } catch (HTTPException $e) {
            throw $e;
        }

        return $this;
    }

    public function setAuth(Auth_Element $dependency)
    {
        $this->auth = $dependency;

        return $this;
    }

    public function respond()
    {
        return $this->api->respond();
    }
}
