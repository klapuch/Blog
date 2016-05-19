<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model\Post;
use Nette\Application\UI;

final class Inbox extends BaseControl {
    private $entities;
    private $inbox;

    public function __construct(
        Doctrine\EntityManager $entities,
        Post\Inbox $inbox
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->inbox = $inbox;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Inbox.latte');
        $this->template->inbox = $this->inbox;
        $this->template->colors = [
            'unread' => 'success',
            'read' => 'muted',
            'spam' => 'danger'
        ];
        $this->template->render();
    }
}

