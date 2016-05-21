<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Application\UI,
    Nette\Security;

final class Tags extends BaseControl {
    private $entities;
    private $tags;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Tags $tags
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->tags = $tags;
    }

    public function createTemplate() {
        $template = parent::createTemplate();
        $template->tags = $this->tags->iterate();
        return $template;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Tags.latte');
        $this->template->render();
    }

    public function renderPinned() {
        $this->template->setFile(__DIR__ . '/PinnedTags.latte');
        $this->template->render();
    }

    public function renderEditable(int $articleId) {
        $this->template->setFile(__DIR__ . '/EditableTags.latte');
        $this->template->articleId = $articleId;
        $this->template->render();
    }

    protected function createComponentTags() {
        $components = [];
        foreach($this->tags->iterate() as $tag)
            $components[$tag->id()] = new Tag(
                $this->entities,
                $tag
            );
        return new UI\Multiplier(
            function(int $id) use ($components) {
                return $components[$id];
            }
        );
    }
}

