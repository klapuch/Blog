<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Application\UI,
    Nette\Security;

final class Tag extends BaseControl {
    private $entities;
    private $tag;
    private $color;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Tag $tag,
        Model\Color $color
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->tag = $tag;
        $this->color = $color;
    }

    protected function createTemplate() {
        $template = parent::createTemplate();
        $template->tag = $this->tag;
        $template->color = $this->color;
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
            (new Model\NewestArticles($this->entities))->article($articleId)
        );
        $this->entities->flush();
        $this->presenter->redirect('this');
    }

    /**
     * @secured
     */
    public function handleRemove() {
        (new Model\ArticleTags($this->entities))->remove($this->tag);
        $this->presenter->redirect('this');
    }
}

