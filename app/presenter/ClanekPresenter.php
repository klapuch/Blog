<?php
namespace Facedown\Presenter;

use Facedown\{
    Model, Exception, Component
};
use Nette\Security,
    Nette\Application\UI;

final class ClanekPresenter extends BasePresenter {
    public function renderDefault(int $id) {
        $article = $this->article();
        $this->template->article = $article;
        $this->template->discussion = $this->discussion($article);
    }

    public function actionEditace(int $id) {
        $article = $this->article();
        $this['editArticleForm']->defaults = [
            'title' => $article->title(),
            'content' => $article->content(),
        ];
        $this->template->article = $article;
        $this->template->backlink = $this->storeRequest();
    }

    public function createComponentEditArticleForm() {
        $form = new Component\BaseForm;
        $form->addText('title', 'Titulek')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 50);
        $form->addText('tags', 'Tagy');
        $form->addTextArea('content', 'Obsah')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn');
        $form->addSubmit('act', 'Upravit');
        $form->onSuccess[] = function(UI\Form $form) {
            $this->editArticleFormSucceeded($form);
        };
        return $form;
    }

    public function editArticleFormSucceeded(UI\Form $form) {
        $article = $form->values;
        $this->entities->transactional(function () use ($article) {
            if(trim($article->tags)) {
                (new Model\SelectedTags(
                    $this->entities,
                    array_reduce(
                        array_map('trim', explode(',', $article->tags)),
                        function($previous, string $tag) {
                            $previous[] = new Model\ArticleTag($tag);
                            return $previous;
                        }
                    )
                ))->pin($this->article());
            }
            $this->article()->edit($article->title, $article->content);
        });
        $this->flashMessage('Článek byl upraven', 'success');
        $this->redirect('Clanek:default', ['id' => $this->getParameter('id')]);
    }

    public function createComponentDiscussionForm() {
        $form = new Component\BaseForm;
        $form->addText('author', 'Autor')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 50);
        $form->addTextArea('content', 'Obsah')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn');
        $form->addSubmit('act', 'Přidat');
        $form->onSuccess[] = function(UI\Form $form) {
            $this->discussionFormSucceeded($form);
        };
        return $form;
    }

    public function discussionFormSucceeded(UI\Form $form) {
        $discussion = $this->discussion($this->article());
        $comment = $form->values;
        $discussion->post($comment->content, $comment->author);
        $this->flashMessage('Komentář byl zveřejněn', 'success');
        $this->redirect('this');
    }

    public function createComponentTags() {
        return new Component\Tags(
            $this->entities,
            new Model\SelectedTags(
                $this->entities,
                $this->article()->tags()->toArray()
            ),
            $this->identity
        );
    }

    private function article(): Model\Article {
        try {
            return (new Model\NewestArticles(
                $this->entities,
                new Model\Users(
                    $this->entities,
                    new Model\Security\Bcrypt(new Security\Passwords)
                ),
                $this->identity
            ))->article($this->getParameter('id'));
        } catch(Exception\ExistenceException $ex) {
            $this->error($ex->getMessage());
        }
    }

    private function discussion(Model\Article $article): Model\Discussion {
        return new Model\ArticleDiscussion(
            $this->entities,
            $article
        );
    }
}