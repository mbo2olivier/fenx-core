<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx;
/**
 * interface ContainerAware.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
interface ContainerAware
{
    /**
     * Fonction appelée juste après l'instanciation du l'object par le container
     *
     * @param Application $app 
     **/
    public static function initialize(Application $app);
   
}
