<?php
namespace Facedown\Model;

use Nette\Caching;

final class CachedDiscussion implements Discussion {
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

    public function post(string $content, string $author): Comment {
        return $this->origin->post($content, $author);
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