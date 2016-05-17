<?php
namespace Facedown\Component;

use Facedown\Model;
use Facedown\Exception;
use Nette\Application\UI;

final class CommentForm extends BaseControl {
    private $discussion;
    public $onSuccess = [];

    public function __construct(Model\Discussion $discussion) {
        parent::__construct();
        $this->discussion = $discussion;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/CommentForm.latte');
        $this->template->render();
    }

    protected function createComponentForm() {
        $form = new BaseForm;
        $form->addText('author', 'Autor')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 50);
        $form->addTextArea('content', 'Obsah')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->setAttribute('rows', 10);
        $form->addSubmit('act', 'Přidat');
        $form->onSuccess[] = function(UI\Form $form) {
            $this->formSucceeded($form);
        };
        return $form;
    }

    protected function formSucceeded(UI\Form $form) {
        $comment = $form->values;
        $this->discussion->post($comment->content, $comment->author);
        $this->onSuccess();
    }
}
