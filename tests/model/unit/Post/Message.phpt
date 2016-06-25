<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Unit\Post;

use Tester,
    Tester\Assert;
use Facedown\Model\Post;

require __DIR__ . '/../../../bootstrap.php';

final class Message extends Tester\TestCase {
    /**
     * @var Post\Message
     */
    private $message;

    public function setUp() {
        parent::setUp();
        $this->message = new Post\Message(
            'fooSubject',
            'fooContent',
            'fooSender'
        );
    }

    public function testMarking() {
        Assert::same(Post\Message::UNREAD, $this->message->state());
        $this->message->mark(Post\Message::READ);
        Assert::same(Post\Message::READ, $this->message->state());
    }

    public function testMarkingToInvalidStateWithoutChange() {
        $this->message->mark('bullshit');
        Assert::same(Post\Message::UNREAD, $this->message->state());
    }

    public function testMarkingToTheSameState() {
        $this->message->mark(Post\Message::SPAM);
        Assert::same(Post\Message::SPAM, $this->message->state());
        $this->message->mark(Post\Message::SPAM);
        Assert::same(Post\Message::SPAM, $this->message->state());
    }

}


(new Message())->run();
