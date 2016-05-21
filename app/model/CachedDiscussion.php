<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Nette,
    Nette\Caching;

final class CachedDiscussion extends Nette\Object implements Discussion {
    private $cache;
    private $origin;

    public function __construct(Caching\IStorage $cache, Discussion $origin) {
        $this->cache = $cache;
        $this->origin = $origin;
    }

    public function iterate(): array {
        return $this->read(__FUNCTION__);
    }

    public function comment(int $id): Comment {
        return $this->read(__FUNCTION__, $id);
    }

    public function post(Comment $comment): Comment {
        return $this->origin->post($comment);
    }

    public function count(): int {
        return $this->read(__FUNCTION__);
    }

    private function read(string $method, ...$args) {
        $key = __CLASS__ . '::' . $method;
        if($this->cache->read($key) === null)
            $this->cache->write($key, $this->origin->$method(...$args), []);
        return $this->cache->read($key);
    }
}