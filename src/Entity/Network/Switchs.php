<?php

namespace App\Entity\Network;

class Switchs
{
    private $id;

    private $name;

    private $active;

    private $createdAt;

    private $removedAt;

    private $addressIpv4;

    private $addressIpv6;

    private $username;

    private $password;

    private $pop;

    private $switchModel;

    private $vlan;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRemovedAt(): ?\DateTimeInterface
    {
        return $this->removedAt;
    }

    public function setRemovedAt(?\DateTimeInterface $removedAt): self
    {
        $this->removedAt = $removedAt;

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
