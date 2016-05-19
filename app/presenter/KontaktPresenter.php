<?php
declare(strict_types = 1);
namespace Facedown\Presenter;

use Facedown\{
    Model, Exception, Component
};
use Nette\Security,
    Nette\Application\UI;

final class KontaktPresenter extends BasePresenter {
    public function createComponentContactForm() {
        $form = new Component\BaseForm;
        $form->addText('username', 'Přezdívka nebo email')
            ->addRule(UI\Form::FILLED, 'Pole %label musí být vyplněno')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 100);
        $form->addText('subject', 'Předmět')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 100);
        $form->addTextArea('content', 'Obsah')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->setAttribute('rows', 15);
        $form->addSubmit('act', 'Poslat');
        $form->onSuccess[] = function(UI\Form $form) {
            $this->contactFormSucceeded($form);
        };
        return $form;
    }

    public function contactFormSucceeded(UI\Form $form) {
        $message = $form->values;
        (new Model\Post\ImportantInbox($this->entities))
            ->put($message->subject, $message->content, $message->username);
        $this->flashMessage('Zpráva byla odeslána', 'success');
        $this->redirect('this');
    }
}