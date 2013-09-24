<?php

namespace attitude\Elements;

use \attitude\Elements\Singleton_Prototype;

class Server_Element extends Singleton_Prototype
{
    private $api   = null;
    private $auth  = null;

    protected function __construct()
    {
        Boot_Element::instance();
//         echo json_encode($GLOBALS, JSON_PRETTY_PRINT);

        $this->auth = \bemore\Auth_Element::instance($_SERVER['Authorization']);

        DependencyContainer::set('Auth', $this->auth);

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

    public function respond()
    {
        return $this->api->respond();
    }
}
