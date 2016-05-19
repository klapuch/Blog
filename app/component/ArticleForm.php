<?php
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\{
    Model, Exception
};
use Nette\Application\UI,
    Nette\Security,
    Nette\Caching\Storages;

final class ArticleForm extends BaseControl {
    private $entities;
    private $tags;
    private $identity;
    public $onSuccess = [];

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Tags $tags,
        Security\IIdentity $identity
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->tags = $tags;
        $this->identity = $identity;
    }

    public function render(Model\Article $article = null) {
        $this->template->setFile(__DIR__ . '/ArticleForm.latte');
        if($article !== null)
            $this->template->article = $article;
        $this->template->render();
    }

    protected function createComponentForm() {
        $form = new BaseForm;
        $form->addText('title', 'Titulek')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 50);
        $form->addText('tags', 'Tagy');
        $form->addTextArea('content', 'Obsah')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->setAttribute('rows', 20);
        $form->addSubmit('act', 'Přidat');
        $form->onSuccess[] = function(UI\Form $form) {
            $this->onSuccess($form);
        };
        return $form;
    }

    protected function createComponentTags() {
        return new Tags(
            $this->entities,
            $this->tags,
            $this->identity
        );
    }
}
