<?php
declare(strict_types = 1);
namespace Facedown\Presenter;

use Facedown\{
    Exception, Component
};
use Facedown\Model\Post;

final class ZpravaPresenter extends BasePresenter {
    public function renderPrecist(int $id) {
        $this->template->message = $this->message();
    }

    public function createComponentMessage() {
        return new Component\Message($this->entities, $this->message());
    }

    private function message() {
        try {
            return (new Post\ImportantInbox(
                $this->entities
            ))->message((int)$this->getParameter('id'));
        } catch(Exception\ExistenceException $ex) {
            $this->error($ex->getMessage());
        }
    }
}