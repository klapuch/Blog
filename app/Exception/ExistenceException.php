<?php
declare(strict_types = 1);
namespace Facedown\Exception;

use Nette\Http\IResponse;

class ExistenceException extends \Exception {
    public function __construct(
        $message,
        $code = IResponse::S404_NOT_FOUND,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}