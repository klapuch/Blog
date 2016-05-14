<?php
namespace Facedown\Model\Access;

use Facedown\Model;
use Kdyby\Doctrine;
use Nette\Security\{
    IAuthenticator, AuthenticationException, Identity
};

final class Authenticator implements IAuthenticator {
    private $entities;
    private $cipher;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Security\Cipher $cipher
    ) {
        $this->entities = $entities;
        $this->cipher = $cipher;
    }

    public function authenticate(array $credentials) {
        list($username, $plainPassword) = $credentials;
        $users = $this->entities->getRepository(Model\User::class);
        /** @var $user Model\User */
        $user = $users->findOneByUsername($username);
        if($user === null)
            throw new AuthenticationException('Uživatel neexistuje');
        elseif(!$this->cipher->decrypt($plainPassword, $user->password()))
            throw new AuthenticationException('Nesprávné heslo');
        if($this->cipher->deprecated($user->password())) {
            $user->changePassword($this->cipher->encrypt($plainPassword));
            $this->entities->flush();
        }
        return new Identity($user->id(), [(string)$user->role()]);
    }

}