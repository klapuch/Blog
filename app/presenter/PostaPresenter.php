<?php
namespace Facedown\Presenter;

use Facedown\Model;

final class PostaPresenter extends BasePresenter {
    public function renderDefault() {
        $this->template->czechStates = [
            'unread' => 'Nepřečteno',
            'read' => 'Přečteno',
            'spam' => 'Spam'
        ];
        $this->template->colors = [
            'unread' => 'success',
            'read' => 'muted',
            'spam' => 'danger'
        ];
        $this->template->inbox = new Model\Post\ImportantInbox(
            $this->entities
        );
    }
}