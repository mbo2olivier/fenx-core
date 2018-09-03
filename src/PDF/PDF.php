<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\PDF;

use Fenxweb\Fenx\Module;
use Fenxweb\Fenx\Application;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * class PDF.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class PDF extends Module {

    public static function initialize(Application $app) {
        $app['pdf'] = function() {
            return new Printer();
        };
    }
}