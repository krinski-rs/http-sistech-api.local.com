<?php

namespace App\Entity\Network;

class Pop
{
    private $id;

    private $name;

    private $isActive;

    private $recordingDate;

    private $removalDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRecordingDate(): ?\DateTimeInterface
    {
        return $this->recordingDate;
    }

    public function setRecordingDate(\DateTimeInterface $recordingDate): self
    {
        $this->recordingDate = $recordingDate;

        return $this;
    }

    public function getRemovalDate(): ?\DateTimeInterface
    {
        return $this->removalDate;
    }

    public function setRemovalDate(?\DateTimeInterface $removalDate): self
    {
        $this->removalDate = $removalDate;

        return $this;
    }
}
