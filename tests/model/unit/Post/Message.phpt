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
    public function testState() {
        $message = new Post\Message('fooSubject', 'fooContent', 'fooSender');
        Assert::same(Post\Message::UNREAD, $message->state());

        $message->mark(Post\Message::READ);
        Assert::same(Post\Message::READ, $message->state());

        $message->mark(Post\Message::SPAM);
        Assert::same(Post\Message::SPAM, $message->state());
        $message->mark(Post\Message::SPAM);
        Assert::same(Post\Message::SPAM, $message->state());

        $message->mark('bullshit');
        Assert::same(Post\Message::SPAM, $message->state()); // still the same
    }
}


(new Message())->run();
