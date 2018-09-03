<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Mailer;

use Fenxweb\Fenx\Module;
use Fenxweb\Fenx\Application;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * class Mailer.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Mailer extends Module {

    public static function initialize(Application $app) {
        $configFile = $app['app.config_dir']."/mailer.php";
        if(!is_file($configFile)) {
            throw new \RuntimeException(\sprintf("cannot find the config file at: %s",$configFile));
        }
        $config = require $configFile;
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        foreach ($config as $key => $val) {
            $mailer->$key = $val;
        }

        $app['mailer'] = function ($a) use ($mailer) {
            return $mailer;
        };
    }
}