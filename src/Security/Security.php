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
use Fenxweb\Fenx\Module;
use Fenxweb\Fenx\Security\Command\DeploySecurityCommand;
use Fenxweb\Fenx\Security\Command\CreateUserCommand;
/**
 * class Security.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Security extends Module {

    public static function initialize(Application $app) {  
        self::registerServices($app);
        self::registerCommands($app);
    }

    private static function registerServices(Application $app) {
        # Add roles manager in the container.
        $configFile = $app['app.config_dir']."/security/roles.php";
        $roles = (is_file($configFile)) ? require $configFile : [];
        $app['role_manager'] = function($a) use ($roles){
            return new RoleManager($a['hook'],$roles);
        };

        # reading congig file.
        $configFile = $app['app.config_dir']."/security/security.php";
        $config = (is_file($configFile)) ? require $configFile : [];
        $app['security.login_route'] = (isset($config['login_route'])) ? $config['login_route'] : 'login';

        # User Provider.
        $app['user_provider'] = function($a) use ($config){
            $class = isset($config['entity']) ? $config['entity'] : 'App\Entity\User';
            return new EntityUserProvider($a['db.em'], $class);
        };

        # Add AuthManager.
        $app['auth'] = function($a) use ($config){
            return new AuthManager($a['session'], $a['user_provider'], $a['role_manager'], $config);
        };

        # Add UserSession.
        $app['user_session'] = function($a) use ($config){
            return new UserSession($a['session']);
        };

        # add view helpers
        $app->registerHelper('check','auth','check');
        $app->registerHelper('user','user_session','get');
    }

    private static function registerCommands(Application $app) {
        # register commands
        $app['security.cmd.deploy'] = function ($a) {
            $resDir = __DIR__."/../../resources";
            return new DeploySecurityCommand($a['app.project_dir'],$resDir);
        };

        $app['security.cmd.create_user'] = function ($a) {
            return new CreateUserCommand($a['user_provider']);
        };

        $app->registerCommand('security.cmd.create_user',true);
        $app->registerCommand('security.cmd.deploy',true);
    }
}