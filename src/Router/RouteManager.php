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

    protected $host;
    /**
     * @var string
     */
    protected $currentRouteName;

    /**
     * @var string
     */
    protected $currentRouteParams;

    public static $methods = ["GET","POST","PUT","DELETE","OPTIONS"];

    public function __construct($routes, $root, $host) {
        $this->routes = $routes;
        $this->root = $root;
        $this->host = $host;
        $this->currentRouteName = "";
        $this->currentRouteParams = [];
    }

    public function path($route,$params = [], $absolute = false) {
        if(isset($this->routes[$route])) {
            $path = $this->routes[$route]['pattern'];
            foreach($params as $key => $val) {
                $path = \str_replace(sprintf(":%s",$key),$val,$path);
            }
            return ($absolute ? $this->host : "").$this->root.$path;
        }else {
            throw new \InvalidArgumentException(sprintf('Impossible de trouver la route "%s"',$route));
        }
    }

    public function checkPath($path,$method = "GET") {
        $response = false;
        $data = [];
        foreach($this->routes as $name => $route) {
            if(in_array($method, $route['methods']) && \preg_match("#^".$route['regex']."$#",$path,$data)) {
                $response = [];
                $response['name'] = $name;
                $response['controller'] = $route['controller'];
                $response['action'] = $route['action'];
                $response['params'] = [];
                foreach($route['params'] as $p) {
                    $response['params'][$p] = $data[$p];
                }
                $this->currentRouteName = $name;
                $this->currentRouteParams = $route['params'];
                break;
            }else{
                $this->currentRouteName = "";
                $this->currentRouteParams = [];
            }
        }
        return $response;
    }

    public function getRouteInfo($key){
        switch($key) {
            case "params":
                return $this->currentRouteParams;
            case "name":
                return $this->currentRouteName;
            default:
                return null;
        }
    }
}