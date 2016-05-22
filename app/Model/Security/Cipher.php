<?php
declare(strict_types = 1);
namespace Facedown\Model\Security;

interface Cipher {
    /**
     * Encrypt the given plain text by the cipher
     * @param string $plain
     * @return string
     */
    public function encrypt(string $plain): string;

    /**
     * Check whether the plain text is the same as hash
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