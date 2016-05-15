<?php
namespace Facedown\Presenter;

use Facedown\{
    Exception, Model, Component
};
use Nette\Security,
    Nette\Application,
    Nette\Application\UI,
    Nette\Utils\Strings;

final class ClankyPresenter extends BasePresenter {
    public function renderDefault(string $tag = null) {
        try {
            $this->template->selectedTag = $tag;
            $this->template->articles = $this->articles();
            if($tag !== null) {
                $this->template->articles = new Model\TaggedArticles(
                    $tag,
                    $this->entities,
                    $this->template->articles
                );
            }
        } catch(Exception\ExistenceException $ex) {
            $this->error($ex->getMessage());
        }
    }

    public function createComponentAddArticleForm() {
        $form = new Component\BaseForm;
        $form->addText('title', 'Titulek')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněn')
            ->addRule(UI\Form::MAX_LENGTH, '%label smí mít maximálně %d znaků', 50);
        $form->addText('tags', 'Tagy')
            ->addRule(UI\Form::FILLED, '%label musí být vyplněny');
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
                    (new Model\SelectedTags(
                        $this->entities,
                        array_reduce(
                            array_map('trim', explode(',', $article->tags)),
                            function($previous, string $tag) {
                                $previous[] = new Model\ArticleTag($tag);
                                return $previous;
                            }
                        )
                    ))->pin($publishedArticle);
                    return $publishedArticle;
                }
            );
            $this->flashMessage('Článek byl přidán', 'success');
            $this->redirect('Clanek:default', ['id' => $publishedArticle->id()]);
        } catch(Exception\ExistenceException $ex) {
            $this->flashMessage($ex->getMessage(), 'danger');
        }
    }
    
    public function createComponentTags() {
        return new Component\Tags(
            $this->entities,
            new Model\PinnedArticleTags(
                $this->entities,
                new Model\ArticleTags($this->entities)
            )
        );
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