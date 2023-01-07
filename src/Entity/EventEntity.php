<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventEntityRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'event')]
#[ORM\Entity(repositoryClass: EventEntityRepository::class)]
class EventEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $seasonId = null;

    #[ORM\Column]
    private ?int $num = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::STRING)]
    private string $readableDates = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeasonId(): ?int
    {
        return $this->seasonId;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function getDateStart(): ?DateTimeInterface
    {
        return $this->dateStart;
    }

    public function getDateEnd(): ?DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function getReadableDates(): ?string
    {
        return $this->readableDates;
    }
}
