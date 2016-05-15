<?php
namespace Facedown\Presenter;

use Nextras;
use Nette,
    Nette\Http\IResponse;
use Kdyby;

abstract class BasePresenter extends Nette\Application\UI\Presenter {
    use Nextras\Application\UI\SecuredLinksPresenterTrait;

    /** @var Kdyby\Doctrine\EntityManager @inject */
    public $entities;

    /** @var Nette\Security\IIdentity @inject */
    public $identity;

    public function checkRequirements($element) {
        if($this->signal === null) {
            $resource = $this->name;
            $action = $this->action;
        } elseif($this->signal && empty($this->signal[0])) {
            $resource = $this->name;
            $action = $this->signal[1] . '!';
        } elseif($this->signal && $this->signal[0]) {
            $resource = preg_replace('~-[0-9]+$~', '', $this->signal[0]);
            $action = $this->signal[1] . '!';
        }
        if(!$this->user->isAllowed($resource, $action)) {
            if($this->user->loggedIn) {
                $this->error(
                    'Na tuto stránku nemáte dostatečné oprávnění',
                    IResponse::S403_FORBIDDEN
                );
            }
            $this->flashMessage('Je třeba se nejdřív přihlásit', 'danger');
            $this->redirect(
                'Prihlasit:',
                ['backlink' => $this->storeRequest()]
            );
        }
    }
}
