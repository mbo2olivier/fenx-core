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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

    public function outputFile($file,$name = "file.pdf") {
        $content = file_get_contents($file);
        $response = new Response($content);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    public function redirect($url,$status = 302, $headers = array()) {
        return new RedirectResponse($url, $status, $headers);
    }
}