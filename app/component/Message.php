<?php
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model\Post;

final class Message extends BaseControl {
    private $entities;
    private $message;

    public function __construct(
        Doctrine\EntityManager $entities,
        Post\Message $message
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->message = $message;
    }

    protected function createTemplate() {
        $template = parent::createTemplate();
        $template->message = $this->message;
        return $template;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Message.latte');
        $this->template->render();
    }

    /**
     * @secured
     */
    public function handleMark(string $state) {
        $this->message->mark($state);
        $this->entities->flush();
        $this->presenter->flashMessage('Zprávy byla označena', 'success');
        $this->presenter->redirect('Posta:default');
    }
}

