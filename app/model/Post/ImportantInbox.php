<?php
declare(strict_types = 1);
namespace Facedown\Model\Post;

use Nette;
use Kdyby\Doctrine;
use Facedown\Exception;

final class ImportantInbox extends Nette\Object implements Inbox {
    private $entities;
    private $inbox;

    public function __construct(Doctrine\EntityManager $entities) {
        $this->entities = $entities;
        $this->inbox = $entities->getRepository(Message::class);
    }

    public function put(Message $message): Message {
        $this->entities->persist($message);
        $this->entities->flush();
        return $message;
    }

    public function message(int $id): Message {
        $message = $this->inbox->find($id);
        if($message !== null)
            return $message;
        throw new Exception\ExistenceException('Zpráva neexistuje');
    }

    public function iterate(): array {
        return $this->entities->createQueryBuilder()
            ->select('m')
            ->addSelect("(CASE
                WHEN m.state LIKE 'unread' THEN 1
                WHEN m.state LIKE 'read' THEN 2
                WHEN m.state LIKE 'spam' THEN 3
                ELSE 4 END) AS HIDDEN ord")
            ->from(Message::class, 'm')
            ->orderBy('ord', 'ASC')
            ->addOrderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function count(): int {
        return $this->inbox->countBy();
    }
}