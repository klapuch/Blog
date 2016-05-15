<?php
namespace Facedown\Presenter;

use Facedown\{
    Model, Component
};
use Nette\Security,
    Nette\Application\UI;

final class PrihlasitPresenter extends BasePresenter {
    public function startup() {
        parent::startup();
        if($this->user->loggedIn)
            $this->error('Přihlášení pro již přihlášeného uživatele neexistuje');
    }

    public function createComponentLoginForm() {
        $form = new Component\BaseForm;
        $form->addText('username', 'Přezdívka')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněna');
        $form->addPassword('password', 'Heslo')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněno');
        $form->addSubmit('act', 'Přihlásit se');
        $form->onSuccess[] = function(UI\Form $form) {
            $this->loginFormSucceeded($form);
        };
        return $form;
    }

    public function loginFormSucceeded(UI\Form $form) {
        try {
            $user = $form->values;
            $this->user->login($user->username, $user->password);
            $this->flashMessage('Jsi přihlášen', 'success');
            $this->session->regenerateId();
            $this->redirect('Default:');
        } catch(Security\AuthenticationException $ex) {
            $form->addError($ex->getMessage());
        }
    }
}