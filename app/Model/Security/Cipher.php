<?php
declare(strict_types = 1);
namespace Facedown\Model\Security;

interface Cipher {
    /**
     * @param string $plain
     * @return string
     */
    public function encrypt(string $plain): string;

    /**
     * @param string $plain
     * @param string $hash
     * @return bool
     */
    public function decrypt(string $plain, string $hash): bool;

    /**
     * Is the cipher deprecated and need to be encrypted again?
     * @param string $hash
     * @return bool
     */
    public function deprecated(string $hash): bool;
}