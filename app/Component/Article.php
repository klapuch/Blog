<?php
declare(strict_types = 1);
namespace Facedown\Component;

use Kdyby\Doctrine;
use Facedown\Model;
use Nette\Security;

final class Article extends BaseControl {
    private $entities;
    private $article;
    private $tags;

    public function __construct(
        Doctrine\EntityManager $entities,
        Model\Article $article,
        Tags $tags
    ) {
        parent::__construct();
        $this->entities = $entities;
        $this->article = $article;
        $this->tags = $tags;
    }

    protected function createTemplate() {
        $template = parent::createTemplate();
        $template->article = $this->article;
        return $template;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/Article.latte');
        $this->template->render();
    }

    public function renderPreview() {
        $this->template->setFile(__DIR__ . '/ArticlePreview.latte');
        $this->template->render();
    }

    protected function createComponentTags() {
        return $this->tags;
    }
}

