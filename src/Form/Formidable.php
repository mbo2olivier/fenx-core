<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Form;

use Fenxweb\Fenx\Module;
use Fenxweb\Fenx\Application;

/**
 * class Formidable.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Formidable {

    public static function initialize(Application $app) {
        $app['form'] = function ($a) {
            $cache = $a['app.mode'] === "prod";
            return new FormBuilder($a['app.template_dir'],$a['app.cache_dir'], $cache);
        };
    }
}