<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown;

interface Articles {
    /**
     * Go through all articles
     * @return Article[]
     */
    public function iterate(): array;

    /**
     * Publish the given article
     * @param Article $article
     * @throws Facedown\Exception\ExistenceException
     * @return Article
     */
    public function publish(Article $article): Article;

    /**
     * Give article by the ID
     * @param int $id
     * @throws Facedown\Exception\ExistenceException
     * @return Article
     */
    public function article(int $id): Article;

    /**
     * Count the articles
     * @return int
     */
    public function count(): int;
}