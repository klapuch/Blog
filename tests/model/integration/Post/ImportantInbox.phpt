<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Integration\Access;

use Tester,
    Tester\Assert;
use Facedown\TestCase;
use Facedown\Model\Post;

require __DIR__ . '/../../../bootstrap.php';

final class ImportantInbox extends TestCase\Database {
    /**
     * @var $inbox Post\Inbox
     */
    private $inbox;

    public function setUp() {
        parent::setUp();
        $this->inbox = new Post\ImportantInbox($this->entities);
    }

    public function testPutting() {
        $message = $this->inbox->put(
            new Post\Message('ssubject', 'ccontent', 'ssender')
        );
        Assert::same(5, $message->id());
        Assert::same(5, $this->inbox->count());
    }

    public function testCounting() {
        Assert::same(4, $this->inbox->count());
    }

    public function testIterating() {
        $inbox = $this->inbox->iterate();
        Assert::same(4, count($inbox));
        Assert::same(3, $inbox[0]->id());
        Assert::same(4, $inbox[1]->id());
        Assert::same(2, $inbox[2]->id());
        Assert::same(1, $inbox[3]->id());
    }

    public function testMessage() {
        $message = $this->inbox->message(2);
        Assert::same(2, $message->id());
        Assert::same(Post\Message::READ, $message->state());
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Zpráva neexistuje
     */
    public function testUnknownMessage() {
        $this->inbox->message(666);
    }
}

(new ImportantInbox())->run();
