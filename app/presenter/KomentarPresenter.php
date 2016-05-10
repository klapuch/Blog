<?php
namespace Facedown\Presenter;

use Facedown\{
    Model, Exception, Component
};
use Nette\Security,
    Nette\Application\UI;

final class KomentarPresenter extends BasePresenter {
    public function actionSmazat(int $id, int $articleId) {
        try {
            $this->discussion($this->article())
                ->comment($id)
                ->erase();
            $this->entities->flush();
            $this->flashMessage('Komentář byl smazán', 'success');
            $this->redirect('Clanek:default', ['id' => $articleId]);
        } catch(Exception\ExistenceException $ex) {
            $this->error($ex->getMessage());
        } catch(\LogicException $ex) {
            $this->flashMessage($ex->getMessage(), 'danger');
            $this->redirect('Clanek:default', ['id' => $articleId]);
        }
    }

    private function article(): Model\Article {
        try {
            return (new Model\NewestArticles(
                $this->entities,
                new Model\Users($this->entities, new Security\Passwords),
                $this->identity
            ))->article($this->getParameter('articleId'));
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