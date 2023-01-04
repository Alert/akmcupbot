<?php

namespace App\Entity;

use App\Repository\WebhookLogRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'webhook_log')]
#[ORM\Entity(repositoryClass: WebhookLogRepository::class)]
class WebhookLogEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $ts = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(name: 'first_name', length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(name: 'last_name', length: 50)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $raw = null;

    public function getTs(): ?DateTimeInterface
    {
        return $this->ts;
    }

    public function setTs(DateTimeInterface $ts): self
    {
        $this->ts = $ts;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getRaw(): ?string
    {
        return $this->raw;
    }

    public function setRaw(string $raw): self
    {
        $this->raw = $raw;

        return $this;
    }
}
