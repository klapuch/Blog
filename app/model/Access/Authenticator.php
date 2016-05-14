<?php
namespace Facedown\Model\Access;

use Facedown\Model;
use Kdyby\Doctrine;
use Nette\Security;

class Authenticator implements Security\IAuthenticator {
    private $entities;
    private $passwords;

    public function __construct(
        Doctrine\EntityManager $entities,
        Security\Passwords $passwords
    ) {
        $this->entities = $entities;
        $this->passwords = $passwords;
    }

    public function authenticate(array $credentials) {
        list($username, $plainPassword) = $credentials;
        $users = $this->entities->getRepository(Model\User::class);
        /** @var $user Model\User */
        $user = $users->findOneByUsername($username);
        if($user === null)
            throw new Security\AuthenticationException('Uživatel neexistuje');
        elseif(!$this->passwords->verify($plainPassword, $user->password()))
            throw new Security\AuthenticationException('Nesprávné heslo');
        if($this->passwords->needsRehash($user->password(), ['cost' => 12])) {
            $user->changePassword(
                $this->passwords->hash(
                    $plainPassword,
                    ['cost' => 12]
                )
            );
            $this->entities->flush();
        }
        return new Security\Identity($user->id(), [(string)$user->role()]);
    }

}