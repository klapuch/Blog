<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Application\UI;

final class Comment extends BaseControl {
    private $entities;
    private $comment;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Comment $comment
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->comment = $comment;
    }

    protected function createTemplate() {
        $template = parent::createTemplate();
        $template->comment = $this->comment;
        return $template;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Comment.latte');
        $this->template->render();
    }

    /**
     * @secured
     */
    public function handleErase() {
        try {
            $this->comment->erase();
            $this->entities->flush();
            $this->presenter->flashMessage('Komentář byl smazán', 'success');
        } catch(\LogicException $ex) {
            $this->presenter->flashMessage($ex->getMessage(), 'danger');
        } finally {
            $this->presenter->redirect('this');
        }
    }
}

