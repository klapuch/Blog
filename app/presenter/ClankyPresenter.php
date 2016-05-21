<?php
declare(strict_types = 1);
namespace Facedown\Presenter;

use Facedown\{
    Exception, Model, Component
};
use Facedown\Model\Fake;
use Nette\Security,
    Nette\Application,
    Nette\Application\UI,
    Nette\Caching\Storages,
    Nette\Utils\Strings;

final class ClankyPresenter extends BasePresenter {
    /** @var \Facedown\Model\Security\Cipher @inject */
    public $cipher;

    public function renderDefault(string $tag = null) {
        try {
            $this->template->selectedTag = $tag;
            $this->template->articles = $this->articles();
        } catch(Exception\ExistenceException $ex) {
            $this->error($ex->getMessage());
        }
    }

    public function createComponentArticleForm() {
        $form = new Component\ArticleForm($this->entities, new Fake\Tags);
        $form->onSuccess[] = function(UI\Form $form) {
            $this->articleFormSucceeded($form);
        };
        return $form;
    }

    public function articleFormSucceeded(UI\Form $form) {
        try {
            $article = $form->values;
            $publishedArticle = $this->entities->transactional(
                function() use($article) {
                    $publishedArticle = $this->articles()->publish(
                        new Model\Article(
                            $article->title,
                            $article->content,
                            (new Model\Users($this->entities))
                                ->user($this->identity->getId())
                        )
                    );
                    (new Model\ArticleSlugs($this->entities, $this->articles()))
                        ->add(
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
            $this->flashMessage('Článek byl publikován', 'success');
            $this->redirect('Clanek:default', ['id' => $publishedArticle->id()]);
        } catch(Exception\ExistenceException $ex) {
            $form->addError($ex->getMessage());
        } catch(Exception\DuplicateException $ex) {
            $form->addError($ex->getMessage());
        }
    }

    public function createComponentAllTags() {
        return new Component\Tags(
            $this->entities,
            new Model\CachedTags(
                new Storages\MemoryStorage,
                new Model\PinnedArticleTags(
                    $this->entities,
                    new Model\ArticleTags($this->entities)
                )
            )
        );
    }

    public function createComponentArticles() {
        if($this->getParameter('tag') !== null) {
            return new Component\Articles(
                $this->entities,
                new Model\TaggedArticles(
                    $this->getParameter('tag'),
                    $this->entities,
                    $this->articles()
                )
            );
        }
        return new Component\Articles($this->entities, $this->articles());
    }

    private function articles() {
        return new Model\CachedArticles(
            new Storages\MemoryStorage,
            new Model\NewestArticles($this->entities)
        );
    }
}