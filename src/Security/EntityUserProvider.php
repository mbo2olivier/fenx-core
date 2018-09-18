<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Security;

use Doctrine\ORM\EntityManager;
/**
 * class EntityUserProvider.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class EntityUserProvider implements UserProviderInterface {
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var string
     */
    protected $class;

    public function __construct(EntityManager $em, $class) {
        $this->em = $em;
        $this->class = $class;
    }

    public function findUser($username) {
        return $this->em->getRepository($this->class)->findOneBy(['username' => $username]);
    }

    public function createUser($user) {
        $user = $this->hashPassword($user);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function hashPassword($user) {
        $pwd = password_hash($user->getPlainPassword(),PASSWORD_DEFAULT);
        $user->setPassword($pwd);
        return $user;
    }

    public function newObject() {
        return new $this->class();
    }
}