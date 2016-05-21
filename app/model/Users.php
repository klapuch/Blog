<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Nette;
use Kdyby\Doctrine;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Facedown\Exception\ExistenceException;

final class Users extends Nette\Object {
    private $entities;
    private $users;

    public function __construct(Doctrine\EntityManager $entities) {
        $this->entities = $entities;
        $this->users = $entities->getRepository(User::class);
    }

    public function register(User $user): User {
        try {
            $this->entities->persist($user);
            $this->entities->flush($user);
            return $user;
        } catch(UniqueConstraintViolationException $ex) {
            throw new ExistenceException(
                sprintf(
                    'Uživatelské jméno %s již existuje',
                    $user->username()
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