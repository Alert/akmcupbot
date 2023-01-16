<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'season')]
#[ORM\Entity(repositoryClass: SeasonRepository::class)]
class SeasonEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;


    #[ORM\Column(length: 255)]
    private ?string $title;

    #[ORM\OneToMany(mappedBy: 'season', targetEntity: EventEntity::class)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @return Collection<int, EventEntity>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
