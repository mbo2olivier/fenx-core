<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Templating;

use Symfony\Component\HttpFoundation\Response;
/**
 * class Engine.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Engine {

    protected $dir;

    public function __construct($dir) {
        $this->dir = $dir;
    }

    public function render($template,$data = []) {
        extract($data, EXTR_SKIP);
        \ob_start();
        require $this->dir."/".$template;
        $text = \ob_get_clean();
        return new Response($text);
    }
}