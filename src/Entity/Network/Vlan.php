<?php

namespace App\Entity\Network;

class Vlan
{
    private $id;

    private $tagId;

    private $description;

    private $status;

    private $isActive;

    private $recordingDate;

    private $removalDate;

    private $service;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagId(): ?int
    {
        return $this->tagId;
    }

    public function setTagId(int $tagId): self
    {
        $this->tagId = $tagId;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }
}
