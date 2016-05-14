<?php
namespace Facedown\Presenter;

use Facedown\{
    Exception, Model, Component
};
use Nette\Security,
    Nette\Application\UI,
    Nette\Utils\Strings;

final class ClankyPresenter extends BasePresenter {
    public function renderDefault() {
        $this->template->articles = $this->articles();
    }

    public function createComponentAddArticleForm() {
        $form = new Component\BaseForm;
        $form->addText('title', 'Titulek')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 50);
        $form->addTextArea('content', 'Obsah')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn');
        $form->addSubmit('act', 'Přidat');
        $form->onSuccess[] = function(UI\Form $form) {
            $this->addArticleFormSucceeded($form);
        };
        return $form;
    }

    public function addArticleFormSucceeded(UI\Form $form) {
        try {
            $article = $form->values;
            $publishedArticle = $this->entities->transactional(
                function() use($article) {
                    $publishedArticle = $this->articles()->publish(
                        $article->title,
                        $article->content
                    );
                    (new Model\ArticleSlugs(
                        $this->entities,
                        $this->articles()
                    ))->add(
                        $publishedArticle->id(),
                        Strings::webalize($publishedArticle->title())
                    );
                    return $publishedArticle;
                }
            );
            $this->flashMessage('Článek byl přidán', 'success');
            $this->redirect('Clanek:default', ['id' => $publishedArticle->id()]);
        } catch(Exception\ExistenceException $ex) {
            $this->flashMessage($ex->getMessage(), 'danger');
        }
    }

    private function articles() {
        return new Model\NewestArticles(
            $this->entities,
            new Model\Users(
                $this->entities,
                new Model\Security\Bcrypt(new Security\Passwords)
            ),
            $this->identity
        );
    }
}