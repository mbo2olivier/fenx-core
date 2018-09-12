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
use Fenxweb\Fenx\Security\UserProviderInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;

/**
 * class CreateUserCommand.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class CreateUserCommand extends Command {
    /** @var UserProviderInterface */
    protected $provider;

    public function __construct(UserProviderInterface $provider) {
        $this->provider = $provider;
        parent::__construct();
    }

    protected function configure() {
        $this
            ->setName('security:create-user')
            ->setDescription('Add new user.')
            ->setHelp('This command allows you to create a new user...')

            ->addArgument('username', InputArgument::REQUIRED, 'the user username used as login')
            ->addArgument('password', InputArgument::REQUIRED, 'the account plain password')
            ->addArgument('role', InputArgument::OPTIONAL, 'the user role')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $user = $this->provider->newObject();
        $user->setUsername($input->getArgument('username'));
        $user->setPlainPassword($input->getArgument('password'));
        $role = $input->getArgument('role');
        $user->setRole($role ? $role : "ROLE_USER");
        $user->setActive(true);

        $user = $this->provider->createUser($user);

        $output->writeln(sprintf("<bg=green>User %s has been created</>",$user->getUsername()));
    }

}