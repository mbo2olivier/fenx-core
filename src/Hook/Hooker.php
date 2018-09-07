<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Hook;

use Fenxweb\Fenx\Application;

/**
 * class Hooker.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Hooker {
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var array
     */
    protected $hooks;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->hooks = [];
    }

    public function attach($tag, $callback, $priority = 0) {
        $h = $this->getOrCreateHook($tag);
        $h->attach($callback, $priority);
    }

    public function dispatch($tag, $args = []) {
        $h = $this->getOrCreateHook($tag);
        $result = null;
        foreach($h as $service => $method) {
            $result = \call_user_func_array([$this->app[$service],$method], $args);
        }

        return $result;
    }

    protected function getOrCreateHook($tag) {
        if(!isset($this->hooks[$tag])) {
            $h = new Hook($tag);
            $this->hooks[$tag] = $h;
        }

        return $this->hooks[$tag];
    }
}