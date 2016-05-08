<?php
namespace Facedown\Model\Access;

use Nette\Security;

final class IdentityFactory {
    private $user;

    public function __construct(Security\User $user) {
        $this->user = $user;
    }

    /**
     * @return Security\IIdentity
     */
    public function create(): Security\IIdentity {
        if($this->user->identity === null)
            return new NoOneIdentity;
        return $this->user->identity;
    }
}