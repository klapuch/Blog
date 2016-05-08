<?php
namespace Facedown\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

final class RouterFactory {

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter() {
        $router = new RouteList;
        $router[] = new Route('<presenter>/<action>[/<id>]', 'Default:default');
        return $router;
    }

}
