<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Security;

use Fenxweb\Fenx\Application;
use Fenxweb\Fenx\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * class Firewall.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Firewall extends Middleware {

    public static function initialize(Application $app) {
        $configFile = $app['app.config_dir']."/security/access.php";
        $access = (is_file($configFile)) ? require $configFile : [];
        $app['security.access'] = $access;
    }

    public function before(Request $request){
        $path = $request->getPathInfo();
        $auth = $this->app['auth'];
        $access = $this->app['security.access'];
        $login = $this->app['security.login_route'];

        foreach($access as $pattern => $role) {
            if(\preg_match("#".$pattern."#",$path)) {
                if($auth->check()) {
                    if($auth->check($role)) {
                        break;
                    }else{
                        return new Response("You cannot access to this page", Response::HTTP_FORBIDDEN);
                    }
                }else {
                    $url = $this->app['router']->path($login);
                    return new RedirectResponse($url."?redirectTo=".$request->getRequestUri());
                }
            }
        }
    }

    public function after(Response $response){
        
    }
}