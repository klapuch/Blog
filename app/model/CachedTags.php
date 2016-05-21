<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Nette,
    Nette\Caching;

final class CachedTags extends Nette\Object implements Tags {
    private $cache;
    private $origin;

    public function __construct(Caching\IStorage $cache, Tags $origin) {
        $this->cache = $cache;
        $this->origin = $origin;
    }

    public function iterate(): array {
        return $this->read(__FUNCTION__);
    }

    public function pin($target) {
        return $this->origin->pin($target);
    }

    public function tag(int $id): Tag {
        return $this->read(__FUNCTION__, $id);
    }

    public function remove(Tag $tag) {
        $this->origin->remove($tag);
    }

    private function read(string $method, ...$args) {
        $key = __CLASS__ . '::' . $method;
        if($this->cache->read($key) === null)
            $this->cache->write($key, $this->origin->$method(...$args), []);
        return $this->cache->read($key);
    }
}