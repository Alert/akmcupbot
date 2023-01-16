<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'event')]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class EventEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: SeasonEntity::class, inversedBy: 'events')]
    private ?SeasonEntity $season;

    #[ORM\Column]
    private ?int $num;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateStart;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateEnd;

    #[ORM\Column(type: Types::STRING)]
    private string $readableDates = '';

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventDetailEntity::class)]
    private Collection $details;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?SeasonEntity
    {
        return $this->season;
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

    /**
     * @return Collection<EventDetailEntity>
     */
    public function getDetails(): Collection
    {
        return $this->details->filter(fn($detail) => $detail->isEnabled());
    }

    /**
     * Find detail by button action
     *
     * @param string $btnAction
     *
     * @return EventDetailEntity|null
     */
    public function findDetailByBtnAction(string $btnAction): ?EventDetailEntity
    {
        return $this->getDetails()->findFirst(fn($key, $detail) => $detail->getBtnAction() === $btnAction);
    }
}
