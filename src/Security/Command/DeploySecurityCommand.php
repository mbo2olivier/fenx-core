<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Security\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class DeploySecurityCommand.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class DeploySecurityCommand extends Command {

    protected $rootDir;
    protected $resDir;

    public function __construct($root, $res) {
        $this->rootDir = $root;
        $this->resDir = $res;
        parent::__construct();
    }

    protected function configure() {
        $this
            ->setName('security:deploy')
            ->setDescription('deploy security files.')
            ->setHelp('This command allows you to deploy security file...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            "",
            "Security deployment",
            "===================",
        ]);
        $this->deploy($output,"Security/model/User.php","model/Entity/User.php","Deploying User entity");
        $this->deploy($output,"Security/config/security.php","config/routes/security.php","Deploying Routes file");
        $this->deploy($output,"Security/controller/SecurityController.php","controller/SecurityController.php","Deploying Controller");
        
        if(!is_dir($this->rootDir."/view/Security")) {
            mkdir($this->rootDir."/view/Security",0777,true);
        }
        $this->deploy($output,[
            "Security/view/login_form.php",
            "Security/view/login.php"
        ],[
            "view/Security/login_form.php",
            "view/Security/login.php",
        ],"Deploying views");
    }

    protected function backup(OutputInterface $output, $file) {
        $file = realpath($file);
        if(is_file($file)) {
            $content = file_get_contents($file);
            $t = $file."~";
            file_put_contents($t, $content);
            $output->writeln(sprintf("file backup at %s",$t));
        }
    }

    protected function deploy(OutputInterface $output, $from, $to, $message) {
        $output->writeln([
            "",
            "=> ".$message,
            ""
        ]);
        if(is_array($from)) {
            for($i = 0; $i < count($from); $i++) {
                $this->singleDeploy($output, $from[$i], $to[$i]);
            }
        }else{
            $this->singleDeploy($output, $from, $to);
        }
    }

    protected function singleDeploy(OutputInterface $output, $from, $to) {
        $path = $this->resDir."/".$from;
        $content = file_get_contents($path);
        if($content === false) {
            $output->writeln("<bg=red>an error occured during deployment</>");
        }else{
            $target = $this->rootDir."/".$to;
            $this->backup($output, $target);
            file_put_contents($target, $content);
            $output->writeln(sprintf("<bg=green>User entity deployed at %s</>",realpath($target)));
        }
    }
}