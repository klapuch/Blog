<?php
namespace Facedown\Presenter;

use Nette;

abstract class BasePresenter extends Nette\Application\UI\Presenter {
    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $entities;
}
