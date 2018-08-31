<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Templating;

use Fenxweb\Fenx\Application;
/**
 * class Helper.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Helper {
    /**
     * @var array
     */
    protected static $mappings;
    /**
     * @var Application
     */
    protected static $app;

    public static function setApplication(Application $app) {
        self::$app = $app;
    }

    public static function __callStatic($name, $args) {
        if (isset(self::$mappings[$name])) {
            $service = self::$mappings[$name]['service'];
            $service = self::$app[$service];
            $method = self::$mappings[$name]['method'];
            return \call_user_func_array([$service,$method],$args);
        }else{
            throw new \RuntimeException(sprintf('helper "%s" is not defined'));
        }
    }

    public static function registerHelper($name, $service, $method) {
        self::$mappings[$name] = [
            "service" => $service,
            "method" => $method,
        ];
    }
}
