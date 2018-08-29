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
 * class Route.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Route {
    /**
     * @var array
     */
    protected $data;

    protected $name;

    public function __construct($pattern, $controller,$action,$methods = null) {
        $this->data = [
            "pattern" => $pattern,
            "controller" => $controller,
            "action" => $action,
            "regex" => $pattern,
            "params" => [],
        ];
        $this->methods($methods);
        $this->name = "_route_".(rand(00000,99999));
    }

    public function params(array $params) {
        $regex = $this->data["regex"];
        foreach ($params as $pname => $rgx) {
            $rgx = sprintf("(?P<%s>%s)",$pname,$rgx);
            $regex = str_replace(sprintf(":%s",$pname),$rgx,$regex);
        }
        $this->data["regex"] = $regex;
        $this->data["params"] = array_keys($params);
        return $this;
    }

    public function name($name) {
        $this->name = $name;
        return $this;
    }

    public function methods($methods) {
        $this->data['methods'] = is_array($methods)? $methods: [$methods];
        return $this;
    }

    public function getRouteData () {
        return [$this->name => $this->data];
    }
}