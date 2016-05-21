<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Comment {
    public function id(): int;
    public function date(): \DateTimeInterface;
    public function content(): string;
    public function author(): string;
    public function visible(): bool;
    public function erase();
}