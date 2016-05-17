<?php
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Application\UI,
    Nette\Security;

final class Articles extends BaseControl {
    private $entities;
    private $articles;
    private $identity;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Articles $articles,
        Security\IIdentity $identity
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->articles = $articles;
        $this->identity = $identity;
    }

    public function createTemplate() {
        $template = parent::createTemplate();
        $template->articles = $this->articles->iterate();
        return $template;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Articles.latte');
        $this->template->render();
    }

    protected function createComponentArticles() {
        $components = [];
        foreach($this->articles->iterate() as $article)
            $components[$article->id()] = new Article(
                $this->entities,
                $article,
                $this->identity
            );
        return new UI\Multiplier(
            function(int $id) use ($components) {
                return $components[$id];
            }
        );
    }
}

