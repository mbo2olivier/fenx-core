<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx;

use Symfony\Component\HttpFoundation\Response;
/**
 * class Controller.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Controller
{

    public function output($text,$code = Response::HTTP_OK) {
        \ob_start();
        echo $text;
        $text = \ob_get_clean();
        return new Response($text, $code);
    }
}