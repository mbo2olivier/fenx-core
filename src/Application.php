<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx;

use Pimple\Container;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Fenxweb\Fenx\Annotation\Inject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Fenxweb\Fenx\Templating\Helper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Fenxweb\Fenx\Hook\Hooker;

/**
 * class Application.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Application extends Container
{
    const CTRL_CLASS_KEY = '_controller_class';
    const CTRL_ACTION_KEY = '_controller_action';

    /**
     * @var array
     */
    protected $before;
    /**
     * @var array
     */
    protected $after;
    /**
     * @var array
     */
    protected $modules;
    /**
     * @var array
     */
    protected $commands;
    /**
     * @var bool
     */
    protected $debug;

    public function __construct($projectDir, $debug = false) {
        $this->debug = $debug;
        $this['app.mode'] = $this->debug ? 'dev': 'prod';
        $this->setupSession();
        $this->setupHooker();
        AnnotationRegistry::registerFile(__DIR__.'/../mapping/Annotations.php');
        $this->before = [];
        $this->after = [];
        $this->modules = [];
        $this->commands = [];
        $this["app.project_dir"] = $projectDir;
        $configDir = $projectDir."/config";
        $this["app.config_dir"] = $configDir;
        if( !is_dir($configDir) ){
            mkdir($configDir, 0700);
        }
        $cacheDir = $projectDir."/var/cache";
        $this["app.cache_dir"] = $cacheDir;
        if( !is_dir($cacheDir) ){
            mkdir($cacheDir, 0700,true);
        }
        $this['app.route.root']="";
    }

   /**
    * @param string $classname
    * @return self
    */
   public function mount($classname) {
        $module = $this->instanciate($classname);
        $classname::initialize($this);
        $this->modules[] = get_class($module);
        return $this;
   }

   /**
    * @param string $classname
    * @return self
    */
    public function before($classname) {
        $middleware = $this->addMiddleware($classname);
        $this->before[] = $middleware;
        return $this;
   }

   /**
    * @param string $classname
    * @return self
    */
    public function after($classname) {
        $middleware = $this->addMiddleware($classname);
        $this->after[] = $middleware;
        return $this;
   }

   protected function addMiddleware($classname) {
       $middleware = new $classname($this);
       $classname::initialize($this);
       return $middleware;
   }

   private function instanciate($class) {
       return new $class();
   }

   public function run () {
       $request = Request::createFromGlobals();
       $this['app.route.root']= $request->getBasePath();
       $this['app.route.host']= sprintf("%s://%s%s", $request->isSecure() ? 'https':'http', $request->getHost(), $request->getPort() === 80 ? "" : sprintf(":%d",$request->getPort()));
       $response = $this->runBefore($request);
       if(!$response instanceOf Response) {
            $response = $this->invokeController($request);
            if(!$response instanceOf Response) {
                throw new \RuntimeException(sprintf('Le controleur doit obligatoirement renvoyer un objet de type "%s"',Response::class));
            }
       }
       $response = $this->runAfter($response);
       $response->send();
   }

   protected function runBefore(Request $request) {
        foreach($this->before as $mid) {
            $response = $mid->before($request);
            if($response instanceOf Response) {
                return $response;
            }
        }
        return null;
   }

   protected function runAfter(Response $response) {
        foreach($this->after as $mid) {
            $response = $mid->after($response);
        }
        return $response;
    }

    protected function invokeController(Request $request) {
        // on recherche le controleur adéquat ainsi que la méthode à exécuter
        $class = $request->attributes->get(self::CTRL_CLASS_KEY);
        $action = $request->attributes->get(self::CTRL_ACTION_KEY);
        $method = new \ReflectionMethod($class, $action);
        // lecture des annotations sur cette méthode
        $reader = new AnnotationReader();
        $anno = $reader->getMethodAnnotations($method);
        $anno = array_filter($anno, function($a) {
            return $a instanceof Inject;
        });
        // récupération des variables d'injection ainsi que le service à injecter
        $ij = [];
        foreach($anno as $a) {
            $ij[$a->var] = $a->service;
        }
        // injection des arguments, il s'agit des attributs de la requêtes, la requête elle même ou les services 
        $args = [];
        foreach($method->getParameters() as $p) {
            if($p->getClass() && $p->getClass()->name === Request::class) {
                $args[]= $request;
                continue;
            }else if($request->attributes->has($p->name)) {
                $args[]= $request->attributes->get($p->name);
                continue;
            }else {
                $args[]= isset($ij[$p->name])? $this[$ij[$p->name]]:null;
                continue;
            }
        }
        // on invoke la methode du controleur
        $controller = new $class();
        return \call_user_func_array([$controller,$action], $args);
    }

    public function registerHelper($name, $service, $method) {
        Helper::registerHelper($name, $service, $method);
    }

    public function registerCommand($cmd, $isService = false) {
        $this->commands[$cmd] = $isService;
    }

    public function getCommands() {
        return $this->commands;
    }

    private function setupSession() {
        $this['session'] = function() {
            $sessionStorage = new NativeSessionStorage(array(), new NativeFileSessionHandler());
            $session = new Session($sessionStorage);
            $session->start();
            return $session;
        };
    }

    private function setupHooker() {
        $this['hook'] = function($a) {
            return new Hooker($a);
        };
    }

}