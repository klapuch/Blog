<?php
namespace Facedown\Model;

use Nette,
    Nette\Security;
use Kdyby\Doctrine;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Facedown\Exception\ExistenceException;

final class Users extends Nette\Object {
    private $entities;
    private $passwords;
    private $users;

    public function __construct(
        Doctrine\EntityManager $entities,
        Security\Passwords $passwords
    ) {
        $this->entities = $entities;
        $this->passwords = $passwords;
        $this->users = $entities->getRepository(User::class);
    }

    public function register(string $username, string $password): User {
        try {
            $user = new User(
                $username,
                $this->passwords->hash($password, ['cost' => 12]),
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
        if($user === null)
            throw new ExistenceException('Uživatel neexistuje');
        return $user;
    }
}