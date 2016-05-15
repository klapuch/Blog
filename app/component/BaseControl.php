<?php
namespace Facedown\Component;

use Nette;
use Nextras;

abstract class BaseControl extends Nette\Application\UI\Control {
    use Nextras\Application\UI\SecuredLinksControlTrait;
}