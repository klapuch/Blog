<?php
namespace Facedown\Presenter;

use Facedown\Model,
    Facedown\Model\Fake;

final class TagPresenter extends BasePresenter {
    /** @persistent */
    public $backlink;

    /**
     * @var Model\Security\Cipher @inject
     */
    public $cipher;

    public function actionPin(int $id, int $articleId) {
        $tag = (new Model\AllArticleTags($this->entities))
            ->tag($id);
        $tag->pin(
            (new Model\NewestArticles(
                $this->entities,
                new Model\Users($this->entities, $this->cipher),
                $this->identity
            ))->article($articleId)
        );
        $this->entities->flush();
        $this->restoreRequest($this->backlink);
    }
    
    public function actionUnpin(int $id) {
        $tag = (new Model\AllArticleTags($this->entities))
            ->tag($id);
        $tag->unpin();
        $this->entities->flush();
        $this->restoreRequest($this->backlink);
    }

    public function actionRemove(int $id) {
        $tags = new Model\AllArticleTags($this->entities);
        $tags->remove($tags->tag($id));
        $this->restoreRequest($this->backlink);
    }
}