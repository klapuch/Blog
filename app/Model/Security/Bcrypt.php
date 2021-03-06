<?php
declare(strict_types = 1);
namespace Facedown\Model\Security;

use Nette,
    Nette\Security;

final class Bcrypt extends Nette\Object implements Cipher {
    const COST = 12;
    private $password;

    public function __construct(Security\Passwords $password) {
        $this->password = $password;
    }

    public function encrypt(string $plain): string {
        return $this->password->hash($plain, ['cost' => self::COST]);
    }

    public function decrypt(string $plain, string $hash): bool {
        return $this->password->verify($plain, $hash);
    }

    public function deprecated(string $hash): bool {
        return $this->password->needsRehash($hash, ['cost' => self::COST]);
    }
}