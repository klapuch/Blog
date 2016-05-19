<?php
declare(strict_types = 1);
namespace Facedown\Model\Fake;

use Nette\Security;

final class Passwords extends Security\Passwords {
    private static $verified;

    public function __construct(bool $verified = true) {
        static::$verified = $verified;
    }

    public static function hash($password, array $options = NULL) {
        return 'hashed';
    }

    public static function verify($password, $hash) {
        return static::$verified;
    }

    public static function needsRehash($hash, array $options = NULL) {
        return false;
    }
}