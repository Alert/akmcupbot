<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\DynamicParamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'dynamic_param')]
#[ORM\Entity(repositoryClass: DynamicParamRepository::class)]
class DynamicParamEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name;

    #[ORM\Column(length: 500)]
    private ?string $value = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
