<?php
namespace Facedown\Model;

use Nette;
use Kdyby\Doctrine;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Facedown\Exception\ExistenceException;

final class Users extends Nette\Object {
    private $entities;
    private $cipher;
    private $users;

    public function __construct(
        Doctrine\EntityManager $entities,
        Security\Cipher $cipher
    ) {
        $this->entities = $entities;
        $this->cipher = $cipher;
        $this->users = $entities->getRepository(User::class);
    }

    public function register(string $username, string $password): User {
        try {
            $user = new User(
                $username,
                $this->cipher->encrypt($password),
                $this->entities->getRepository(Role::class)->findOneByName('member')
            );
            $this->entities->persist($user);
            $this->entities->flush($user);
            return $user;
        } catch(UniqueConstraintViolationException $ex) {
            throw new ExistenceException(
                sprintf(
                    'Uživatelské jméno %s již existuje',
                    $username
                )
            );
        }
    }

    public function user(int $id): User {
        $user = $this->users->find($id);
        if($user !== null)
            return $user;
        throw new ExistenceException('Uživatel neexistuje');
    }
}