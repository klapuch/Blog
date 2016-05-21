<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Application\UI,
    Nette\Security;

final class Articles extends BaseControl {
    private $entities;
    private $articles;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Articles $articles
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->articles = $articles;
    }

    public function createTemplate() {
        $template = parent::createTemplate();
        $template->articles = $this->articles;
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
                $article
            );
        return new UI\Multiplier(
            function(int $id) use ($components) {
                return $components[$id];
            }
        );
    }
}

