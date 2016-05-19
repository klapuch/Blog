<?php
declare(strict_types = 1);
namespace Facedown\Model\Security;

interface Cipher {
    public function encrypt(string $plain): string;
    public function decrypt(string $plain, string $hash): bool;
    public function deprecated(string $hash): bool;
}