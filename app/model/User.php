<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User {
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="ID", type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", unique=true, length=50)
     */
    private $username;

    /**
     * Hashed Password
     * @var string
     * @ORM\Column(name="password", type="string", length=160)
     */
    private $password;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="ID")
     */
    private $role;

    public function __construct(
        string $username,
        string $password,
        Role $role
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    public function id(): int {
        return $this->id;
    }

    public function username(): string {
        return $this->username;
    }

    public function password(): string {
        return $this->password;
    }

    public function role(): Role {
        return $this->role;
    }

    public function rename($username): self {
        $this->username = $username;
        return $this;
    }

    public function changePassword(string $password) {
        $this->password = $password;
    }
}