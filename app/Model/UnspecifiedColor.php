<?php
declare(strict_types = 1);
namespace Facedown\Model;

final class UnspecifiedColor implements Color {
	private $name;
	private $print;

	public function __construct(string $name, string $print) {
		$this->name = $name;
		$this->print = $print;
	}

	public function name(): string {
		return $this->name;
	}

	public function print(): string {
		return $this->print;
	}
}