<?php
namespace Facedown\Model\Post;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="inbox")
 */
class Message {
    const READ = 'read';
    const UNREAD = 'unread';
    const SPAM = 'spam';
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="subject", type="string", length=100)
     */
    private $subject;

    /**
     * @var string
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(name="sender", type="string", length=200)
     */
    private $sender;

    /**
     * @var int
     * @ORM\Column(name="state", type="string", length=50)
     */
    private $state;

    public function __construct(
        string $subject,
        string $content,
        string $sender
    ) {
        $this->subject = $subject;
        $this->content = $content;
        $this->sender = $sender;
        $this->date = new \DateTimeImmutable;
        $this->state = self::UNREAD;
    }

    public function id(): int {
        return $this->id;
    }

    public function sender(): string {
        return $this->sender;
    }

    public function content(): string {
        return $this->content;
    }

    public function date(): \DateTimeInterface {
        return $this->date;
    }

    public function subject(): string {
        return $this->subject;
    }

    public function state(): string {
        return $this->state;
    }

    /**
     * Change state of the current message
     */
    public function mark(string $state): self {
        if(in_array($state, [self::READ, self::UNREAD, self::SPAM], true))
            $this->state = $state;
        return $this;
    }
}