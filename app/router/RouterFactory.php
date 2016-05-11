<?php
namespace Facedown\Router;

use Nette,
    Nette\Application\Routers;
use Kdyby\Doctrine;
use Facedown\Model,
    Facedown\Model\Fake;

final class RouterFactory {
    private static $entities;

    public function __construct(Doctrine\EntityManager $entities) {
        static::$entities = $entities;
    }

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter() {
        $router = new Routers\RouteList;
        $router[] = new Routers\Route('clanek/<id [-a-z0-9_]+>', array(
            'presenter' => 'Clanek',
            'action' => 'default',
            'id'  =>  array(
                Routers\Route::FILTER_IN => function ($slug) {
                    return (new Model\ArticleSlugs(
                        static::$entities,
                        new Fake\Articles
                    ))->slug((string)$slug)->origin();
                },
                Routers\Route::FILTER_OUT => function ($id) {
                    return (string)(new Model\ArticleSlugs(
                        static::$entities,
                        new Fake\Articles
                    ))->slug((int)$id);
                }
            ),
        ));
        $router[] = new Routers\Route('<presenter>/<action>[/<id>]', 'Default:default');
        return $router;
    }
}
