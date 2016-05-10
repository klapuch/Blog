<?php
namespace Facedown\Presenter;

use Facedown\{
    Model, Exception
};

final class ZpravaPresenter extends BasePresenter {
    public function renderPrecist(int $id) {
        $this->template->message = $this->message();
    }

    public function actionOznacit(string $state) {
        $this->message()->mark($state);
        $this->entities->flush();
        $this->flashMessage('Zprávy byla označena', 'success');
        $this->redirect('Posta:default');
    }

    private function message() {
        try {
            return (new Model\Post\ImportantInbox(
                $this->entities
            ))->message($this->getParameter('id'));
        } catch(Exception\ExistenceException $ex) {
            $this->error($ex->getMessage());
        }
    }
}