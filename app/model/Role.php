<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="roles")
 */
class Role {
    const PROMOTE = +1;
    const DEGRADE = -1;
    const DEFAULT_RANK = -1;
    const DEFAULT_ROLE = 'guest';
    private $roles = [
        'member' => 1,
        'administrator' => 2,
        'creator' => 3,
    ];

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="ID", type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * Rank of the current role - It's not part of the database table
     * @var int
     */
    private $rank;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function id(): int {
        return $this->id;
    }

    public function rank(): int {
        return $this->roles[(string)$this] ?? self::DEFAULT_RANK;
    }

    public function promote(): self {
        $highestRank = max($this->roles);
        if($highestRank === $this->rank())
            throw new \OverflowException('Vyšší role neexistuje');
        $this->change(self::PROMOTE);
        return $this;
    }

    public function degrade(): self {
        $lowestRank = min($this->roles);
        if($lowestRank === $this->rank())
            throw new \UnderflowException('Nižší role neexistuje');
        $this->change(self::DEGRADE);
        return $this;
    }

    private function change(int $increment) {
        $this->name = array_flip($this->roles)[$this->rank() + $increment];
        $this->rank = $this->rank() + $increment;
    }

    public function __toString() {
        if(isset($this->roles[$this->name]))
            return $this->name;
        return self::DEFAULT_ROLE;
    }
}