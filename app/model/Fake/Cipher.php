<?php
namespace Facedown\Model\Fake;

use Facedown\Model\Security;

final class Cipher implements Security\Cipher {
    private $decrypt;
    private $deprecated;

    public function __construct($decrypt = true, $deprecated = false) {
        $this->decrypt = $decrypt;
        $this->deprecated = $deprecated;
    }

    public function encrypt(string $plain): string {
        return 'encrypted';
    }

    public function decrypt(string $plain, string $hash): bool {
        return $this->decrypt;
    }

    public function deprecated(string $hash): bool {
        return $this->deprecated;
    }
}