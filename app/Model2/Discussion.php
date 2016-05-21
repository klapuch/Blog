<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown;

interface Discussion {
    /**
     * Go through all comments in the discussion
     * @return Comment[]
     */
    public function iterate(): array;

    /**
     * Post the new comment to the current discussion
     * @param Comment $comment
     * @return Comment
     */
    public function post(Comment $comment): Comment;

    /**
     * Give comment by the ID
     * @param int $id
     * @throws Facedown\Exception\ExistenceException
     * @return Comment
     */
    public function comment(int $id): Comment;

    /**
     * Count comments in the discussion
     * @return int
     */
    public function count(): int;
}