<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Application\UI;

final class Discussion extends BaseControl {
    private $entities;
    private $discussion;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Discussion $discussion
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->discussion = $discussion;
    }

    public function createTemplate() {
        $template = parent::createTemplate();
        $template->discussion = $this->discussion->iterate();
        return $template;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Discussion.latte');
        $this->template->render();
    }

    protected function createComponentDiscussion() {
        $components = [];
        foreach($this->discussion->iterate() as $comment) {
            $components[$comment->id()] = new Comment(
                $this->entities,
                $comment
            );
        }
        return new UI\Multiplier(
            function(int $id) use ($components) {
                return $components[$id];
            }
        );
    }
}

