<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Comment {
    /**
     * ID of the comment
     * @return int
     */
    public function id(): int;

    /**
     * Date of the posting
     * @return \DateTimeInterface
     */
    public function date(): \DateTimeInterface;

    /**
     * Content of the comment
     * @return string
     */
    public function content(): string;

    /**
     * Author of the posted comment
     * @return string
     */
    public function author(): string;

    /**
     * Is the comment visible?
     * @return bool
     */
    public function visible(): bool;

    /**
     * Erase the message
     * @throws \LogicException
     * @return void
     */
    public function erase();
}