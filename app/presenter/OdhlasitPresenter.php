<?php
declare(strict_types = 1);
namespace Facedown\Presenter;

final class OdhlasitPresenter extends BasePresenter {
    public function actionDefault() {
        if(!$this->user->loggedIn)
            $this->error('Odhlášení pro hosta neexistuje');
        $this->user->logout(true);
        $this->session->regenerateId();
        $this->flashMessage('Jsi odhlášen', 'success');
        $this->redirect('Default:');
    }
}