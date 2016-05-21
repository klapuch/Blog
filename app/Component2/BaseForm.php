<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Nette\Application\UI;

final class BaseForm extends UI\Form {
    public function __construct() {
        parent::__construct();
        $this->addProtection();
    }

    public function fireEvents() {
        $this->onError[] = function() {
            $this->presenter->flashMessage(current($this->errors), 'danger');
        };
        parent::fireEvents();
    }
}