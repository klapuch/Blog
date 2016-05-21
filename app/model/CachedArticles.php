<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Nette\Caching;

final class CachedArticles implements Articles {
    private $cache;
    private $origin;

    public function __construct(Caching\IStorage $cache, Articles $origin) {
        $this->cache = $cache;
        $this->origin = $origin;
    }

    public function iterate(): array {
        return $this->read(__FUNCTION__);
    }

    public function publish(Article $article): Article {
        return $this->origin->publish($article);
    }

    public function article(int $id): Article {
        return $this->read(__FUNCTION__, $id);
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