<?php
namespace Facedown\Model\Fake;

use Nette\Security;

final class Identity implements Security\IIdentity {
    private $id;
    private $roles;

    public function __construct(int $id = 0, array $roles = []) {
        $this->id = $id;
        $this->roles = $roles;
    }

    function getId() {
        return $this->id;
    }

    function getRoles() {
        return $this->roles;
    }
}