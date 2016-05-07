<?php
namespace Facedown\Model;

use Nette,
    Nette\Security;
use Kdyby\Doctrine;
use Facedown\Exception;

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
        $user = new User(
            $username,
            $this->passwords->hash($password, ['cost' => 12]),
            $this->entities->getRepository(Role::class)->findOneByName('member')
        );
        $this->entities->persist($user);
        $this->entities->flush($user);
        return $user;
    }

    public function user(int $id): User {
        $user = $this->users->find($id);
        if($user === null)
            throw new Exception\ExistenceException('Uživatel neexistuje');
        return $user;
    }
}