<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventDetailRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'event_detail')]
#[ORM\Entity(repositoryClass: EventDetailRepository::class)]
class EventDetailEntity
{
    const TYPE_MEDIA = 'media';
    const TYPE_TEXT = 'text';
    const TYPE_LINK = 'link';

    const BTN_ACTION_FIELD = 'field';
    const BTN_ACTION_SCHEDULE = 'schedule';
    const BTN_ACTION_BROADCAST = 'broadcast';
    const BTN_ACTION_RESULT = 'result';
    const BTN_ACTION_REGISTER = 'register';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(inversedBy: 'details')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventEntity $event;

    #[ORM\Column(length: 50)]
    private ?string $type;

    #[ORM\Column(length: 50)]
    private ?string $btnText;

    #[ORM\Column(length: 50)]
    private ?string $btnAction;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = '';

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $sort = 0;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isEnabled = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?EventEntity
    {
        return $this->event;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getBtnText(): ?string
    {
        return $this->btnText;
    }

    public function getBtnAction(): ?string
    {
        return $this->btnAction;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
