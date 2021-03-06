<?php
declare(strict_types = 1);
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
        return $this->user->identity ?? new NoOneIdentity;
    }
}