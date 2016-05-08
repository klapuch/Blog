<?php
namespace Facedown\Presenter;

use Nette;
use Kdyby;

abstract class BasePresenter extends Nette\Application\UI\Presenter {
    /** @var Kdyby\Doctrine\EntityManager @inject */
    public $entities;

    /** @var Nette\Security\IIdentity @inject */
    public $identity;
}
