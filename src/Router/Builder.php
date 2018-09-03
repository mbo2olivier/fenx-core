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
 * class Builder.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Builder {
    /**
     * @var array
     */
    protected $routes;

    public function __construct() {
        $this->routes = [];
    }

    protected function add($pattern, array $controller,$methods) {
        $r = new Route($pattern,$controller[0],$controller[1],$methods);
        $this->routes[] = $r;
        return $r;
    }

    public function route($pattern, array $controller) {
        return $this->add($pattern,$controller,RouteManager::$methods);
    }

    public function get($pattern, array $controller) {
        return $this->add($pattern,$controller,"GET");
    }

    public function post($pattern, array $controller) {
        return $this->add($pattern,$controller,"POST");
    }

    public function build() {
        $b = [];
        foreach($this->routes as $r) {
            $b[$r->getName()] = $r->getRouteData();
        }
        return $b;
    }
}