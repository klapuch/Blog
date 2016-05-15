<?php
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Application\UI,
    Nette\Security;

final class Tag extends BaseControl {
    private $entities;
    private $tag;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Tag $tag
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->tag = $tag;
    }

    protected function createTemplate() {
        $template = parent::createTemplate();
        $template->tag = $this->tag;
        return $template;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Tag.latte');
        $this->template->render();
    }

    public function renderEditable(int $articleId) {
        $this->template->setFile(__DIR__ . '/EditableTag.latte');
        $this->template->articleId = $articleId;
        $this->template->render();
    }

    public function renderPinned() {
        $this->template->setFile(__DIR__ . '/PinnedTag.latte');
        $this->template->render();
    }

    /**
     * @secured
     */
    public function handleUnpin() {
        $this->tag->unpin();
        $this->entities->flush();
        $this->presenter->redirect('this');
    }

    /**
     * @secured
     */
    public function handlePin(int $articleId) {
        $this->tag->pin(
            (new Model\NewestArticles(
                $this->entities,
                new Model\Users(
                    $this->entities,
                    new Model\Security\Bcrypt(new Security\Passwords)
                ),
                $this->identity
            ))->article($articleId)
        );
        $this->entities->flush();
        $this->presenter->redirect('this');
    }

    /**
     * @secured
     */
    public function handleRemove() {
        $tags = new Model\ArticleTags($this->entities);
        $tags->remove($this->tag);
        $this->presenter->redirect('this');
    }
}

