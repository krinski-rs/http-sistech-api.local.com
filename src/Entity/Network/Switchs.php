<?php

namespace App\Entity\Network;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Switchs
{
    private $id;

    private $name;

    private $isActive;

    private $recordingDate;

    private $removalDate;

    private $addressIpv4;

    private $addressIpv6;

    private $username;

    private $password;

    private $switchPort;

    private $pop;

    private $switchModel;

    private $vlan;

    public function __construct()
    {
        $this->switchPort = new ArrayCollection();
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

    public function getAddressIpv4(): ?string
    {
        return $this->addressIpv4;
    }

    public function setAddressIpv4(?string $addressIpv4): self
    {
        $this->addressIpv4 = $addressIpv4;

        return $this;
    }

    public function getAddressIpv6(): ?string
    {
        return $this->addressIpv6;
    }

    public function setAddressIpv6(?string $addressIpv6): self
    {
        $this->addressIpv6 = $addressIpv6;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|SwitchPort[]
     */
    public function getSwitchPort(): Collection
    {
        return $this->switchPort;
    }

    public function addSwitchPort(SwitchPort $switchPort): self
    {
        if (!$this->switchPort->contains($switchPort)) {
            $this->switchPort[] = $switchPort;
            $switchPort->setSwitchs($this);
        }

        return $this;
    }

    public function removeSwitchPort(SwitchPort $switchPort): self
    {
        if ($this->switchPort->contains($switchPort)) {
            $this->switchPort->removeElement($switchPort);
            // set the owning side to null (unless already changed)
            if ($switchPort->getSwitchs() === $this) {
                $switchPort->setSwitchs(null);
            }
        }

        return $this;
    }

    public function getPop(): ?Pop
    {
        return $this->pop;
    }

    public function setPop(?Pop $pop): self
    {
        $this->pop = $pop;

        return $this;
    }

    public function getSwitchModel(): ?SwitchModel
    {
        return $this->switchModel;
    }

    public function setSwitchModel(?SwitchModel $switchModel): self
    {
        $this->switchModel = $switchModel;

        return $this;
    }

    public function getVlan(): ?Vlan
    {
        return $this->vlan;
    }

    public function setVlan(?Vlan $vlan): self
    {
        $this->vlan = $vlan;

        return $this;
    }
}
