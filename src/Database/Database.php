<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Database;

use Fenxweb\Fenx\Module;
use Fenxweb\Fenx\Application;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * class Database.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Database extends Module {

    public static function initialize(Application $app) {
        $entityDir = $app['app.project_dir']."/model/Entity";
        if( !is_dir($entityDir) ){
            mkdir($entityDir, 0700,true);
        }
        $isDevMode = $app["app.mode"] === "dev";
        $configFile = $app['app.config_dir']."/database.php";
        if(!is_file($configFile)) {
            throw new \RuntimeException(\sprintf("cannot find the config file at: %s",$configFile));
        }
        $params = require $configFile;
        $config = Setup::createAnnotationMetadataConfiguration([$entityDir], $isDevMode, null, null, false);
        $config->setProxyDir(isset($params['proxy_dir']) ? $params['proxy_dir'] : $app['app.cache_dir']."/doctrine");
        $config->setProxyNamespace("App\DoctrineProxies");
        $em = EntityManager::create($params, $config);

        $app['db.em'] = function($a) use ($em) {
            return $em;
        };

        $app['db.native'] = function($a) use ($em) {
            return $em->getConnection();
        };
    }
}