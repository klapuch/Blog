<?php
namespace Facedown\TestCase;

use Tester;
use Testbench;

require __DIR__ . '/../bootstrap.php';

abstract class Database extends Tester\TestCase {
    use Testbench\TDoctrine;

    /** @var $entities \Kdyby\Doctrine\EntityManager */
    protected $entities;

    /** @var $connection \Kdyby\Doctrine\Connection */
    protected $connection;

    public function setUp() {
        parent::setUp();
        $this->entities = $this->getEntityManager();
        $this->connection = $this->entities->getConnection();
        $this->prepareDatabase();
    }

    protected function prepareDatabase() {  }
}
