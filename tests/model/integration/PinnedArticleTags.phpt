<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Integration;

use Tester,
    Tester\Assert;
use Facedown\{
    Model, TestCase
};
use Facedown\Model\Fake;

require __DIR__ . '/../../bootstrap.php';

final class PinnedArticleTags extends TestCase\Database {
    public function testIterating() {
        $tags = (new Model\PinnedArticleTags(
            $this->entities,
            new Fake\Tags
        ))->iterate();
        Assert::same(3, count($tags));
        Assert::same('clean code', (string)$tags[0]);
        Assert::same('OOP', (string)$tags[1]);
        Assert::same('security', (string)$tags[2]);
    }
}

(new PinnedArticleTags())->run();
