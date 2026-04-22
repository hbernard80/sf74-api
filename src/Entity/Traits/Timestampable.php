<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait Timestampable
{
    #[ORM\Column]
    #[Groups(['post:read', 'category:read', 'user:read'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups(['post:read', 'category:read', 'user:read'])]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\PrePersist]
    public function setTimestampsOnCreate(): void
    {
        $now = new \DateTimeImmutable();

        if ($this->created_at === null) {
            $this->created_at = $now;
        }

        if ($this->updated_at === null) {
            $this->updated_at = $now;
        }
    }

    #[ORM\PreUpdate]
    public function setTimestampOnUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    #[Groups(['post:read', 'category:read', 'user:read'])]
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    #[Groups(['post:read', 'category:read', 'user:read'])]
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}