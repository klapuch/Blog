<?php
namespace Facedown\Model\Access;

use Nette,
    Nette\Security;
use Facedown\Model;

final class NoOneIdentity extends Nette\Object implements Security\IIdentity {
    public function getId() {
        return 0;
    }

    public function getRoles() {
        return [Model\Role::DEFAULT_ROLE];
    }

}