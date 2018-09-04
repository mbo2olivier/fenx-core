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

    public function asset($path) {
        $base = $_SERVER['REQUEST_URI'];
        if(preg_match('#index.php#', $base)){
            $split=preg_split("/index.php/",$base);
            $base = $split[0];
        }else if($_SERVER['REQUEST_URI'] === $_SERVER['PATH_INFO']){
            $base = "/";
        }else {
            if(strpos($base,$_SERVER['PATH_INFO']) !== false) {
                $base = substr($base,0,-strlen($_SERVER['PATH_INFO']))."/";
            }
        }
        $req = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['HTTP_HOST'],
            $base
        );

        return $req.$path;
    }
}