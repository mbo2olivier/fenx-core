<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Templating;

use Fenxweb\Fenx\Module;
use Fenxweb\Fenx\Application;
/**
 * class Templating.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Templating extends Module {

    public static function initialize(Application $app) {
        $app['templating'] = function ($a) {
            $templateDir = $a['app.project_dir']."/view";
            if( !is_dir($templateDir) ){
                mkdir($templateDir, 0700);
            }
            return new Engine($templateDir);
        };
    }
}