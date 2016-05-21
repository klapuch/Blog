<?php
declare(strict_types = 1);
namespace Facedown\Presenter;

use Facedown\Component;
use Facedown\Model\Post;

final class PostaPresenter extends BasePresenter {
    public function createComponentInbox() {
        return new Component\Inbox(
            $this->entities,
            new Post\ImportantInbox($this->entities)
        );
    }
}