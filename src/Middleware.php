<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * class Middleware.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
abstract class Middleware implements ContainerAware
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app) {
      $this->app = $app;
    }
    /**
     * @param Request $request
     * @return null|Response
     */
   abstract function before(Request $request);
   /**
     * @param Response $response
     * @return null|Response
     */
   abstract function after(Response $response);
}