<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Router;

/**
 * class RouteManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class RouteManager
{
    /**
     * @var array
     */
    protected $routes;

    protected $root;

    public static $methods = ["GET","POST","PUT","DELETE","OPTIONS"];

    public function __construct($routes, $root) {
        $this->routes = $routes;
        $this->root = $root;
    }

    public function path($route,$params = []) {
        if(isset($this->routes[$route])) {
            $path = $this->routes[$route]['pattern'];
            foreach($params as $key => $val) {
                $path = \str_replace(sprintf(":%s",$key),$val,$path);
            }
            return $this->root.$path;
        }else {
            throw new \InvalidArgumentException(sprintf('Impossible de trouver la route "%s"',$route));
        }
    }

    public function checkPath($path) {
        $response = false;
        $data = [];
        foreach($this->routes as $name => $route) {
            if(\preg_match("#^".$route['regex']."$#",$path,$data)) {
                $response = [];
                $response['name'] = $name;
                $response['controller'] = $route['controller'];
                $response['action'] = $route['action'];
                $response['params'] = [];
                foreach($route['params'] as $p) {
                    $response['params'][$p] = $data[$p];
                }
                break;
            }
        }
        return $response;
    }
}