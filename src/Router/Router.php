<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Router;

use Fenxweb\Fenx\Application;
use Fenxweb\Fenx\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * class Router.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Router extends Middleware{

    public static function initialize(Application $app){
        self::setupProjtectRoot($app);

        $app['router'] = function ($a) {
            $routes = new Builder();
            $dir = $a["app.config_dir"]."/routes";
            foreach(\scandir($dir) as $f) {
                $f =$dir."/".$f;
                if(\is_file($f)) {
                    require_once $f;
                }
            }
            return new RouteManager($routes->build(), $a['app.route.root']);
        };
        // Pour générer des routes depuis la vue (e.g: Helper::path('my_route',$args))
        $app->registerHelper('path','router','path');
    }

    public static function setupProjtectRoot(Application $app) {
        if(!isset($_SERVER['REQUEST_URI'])) return;
        $base = $_SERVER['REQUEST_URI'];
        if(preg_match('#\?#',$base)) {
            $base = preg_split('#\?#',$base);
            $base = $base[0];
        }
        if(preg_match('#index.php#', $base)){
            $split=preg_split("/\/index.php/",$base);
            $base = $split[0];
        }else if($_SERVER['REQUEST_URI'] === $_SERVER['PATH_INFO']){
            $base = "";
        }else {
            if (!isset($_SERVER['PATH_INFO'])){
                $base = substr($base,-1) === "/" ? substr($base,0,-strlen("/")): $base;
            }else {
                $base = substr($base,0,-strlen($_SERVER['PATH_INFO']));
            }
        }
        $app['app.route.root'] = $base;
    }

    public function before(Request $request){
        $path = $request->getPathInfo();
        $router = $this->app['router'];
        $rep = $router->checkPath($path);
        if(is_array($rep)) {
            $request->attributes->set(Application::CTRL_CLASS_KEY,$rep['controller']);
            $request->attributes->set(Application::CTRL_ACTION_KEY,$rep['action']);
            foreach($rep['params'] as $key => $val) {
                $request->attributes->set($key, $val);
            }
        }else{
            return new Response("Impossible de traiter cette URL",Response::HTTP_NOT_FOUND);
        }
    }

    public function after(Response $response){
        
    }
}