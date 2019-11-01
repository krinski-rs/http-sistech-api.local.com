<?php

namespace App\Entity\Network;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SwitchModel
{
    private $id;

    private $name;

    private $isActive;

    private $recordingDate;

    private $removalDate;

    private $brand;

    private $switchModelPort;

    public function __construct()
    {
        $this->switchModelPort = new ArrayCollection();
    }

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

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|SwitchModelPort[]
     */
    public function getSwitchModelPort(): Collection
    {
        return $this->switchModelPort;
    }

    public function addSwitchModelPort(SwitchModelPort $switchModelPort): self
    {
        if (!$this->switchModelPort->contains($switchModelPort)) {
            $this->switchModelPort[] = $switchModelPort;
            $switchModelPort->setSwitchModel($this);
        }

        return $this;
    }

    public function removeSwitchModelPort(SwitchModelPort $switchModelPort): self
    {
        if ($this->switchModelPort->contains($switchModelPort)) {
            $this->switchModelPort->removeElement($switchModelPort);
            // set the owning side to null (unless already changed)
            if ($switchModelPort->getSwitchModel() === $this) {
                $switchModelPort->setSwitchModel(null);
            }
        }

        return $this;
    }
}
