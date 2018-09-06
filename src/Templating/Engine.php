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
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * class Engine.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Engine {

    protected $dir;
    protected $root;
    /**
     * @var Session
     */
    protected $session;

    public function __construct($dir, Session $session, $root) {
        $this->dir = $dir;
        $this->root = $root;
        $this->session = $session;
    }

    public function render($template,$data = []) {
        $text = $this->renderView($template, $data);
        return new Response($text);
    }

    public function renderView($template, $data = []) {
        extract($data, EXTR_SKIP);
        \ob_start();
        require $this->dir."/".$template;
        return \ob_get_clean();
    }

    public function asset($path) {
        $req = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['HTTP_HOST'],
            $this->root
        );

        return $req."/".$path;
    }

    public function flash($key) {
        return $this->session->getFlashBag()->get($key, array());
    }
}