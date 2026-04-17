<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Timestampable
{
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

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