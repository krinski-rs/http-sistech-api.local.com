<?php

namespace App\Entity\Network;

class SwitchPort
{
    private $id;

    private $vlanId;

    private $numbering;

    private $adminStatus;

    private $operStatus;

    private $autoNeg;

    private $speed;

    private $duplex;

    private $flowCtrl;

    private $destiny;

    private $type;

    private $mode;

    private $switch;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVlanId(): ?int
    {
        return $this->vlanId;
    }

    public function setVlanId(?int $vlanId): self
    {
        $this->vlanId = $vlanId;

        return $this;
    }

    public function getNumbering(): ?int
    {
        return $this->numbering;
    }

    public function setNumbering(int $numbering): self
    {
        $this->numbering = $numbering;

        return $this;
    }

    public function getAdminStatus(): ?string
    {
        return $this->adminStatus;
    }

    public function setAdminStatus(?string $adminStatus): self
    {
        $this->adminStatus = $adminStatus;

        return $this;
    }

    public function getOperStatus(): ?string
    {
        return $this->operStatus;
    }

    public function setOperStatus(?string $operStatus): self
    {
        $this->operStatus = $operStatus;

        return $this;
    }

    public function getAutoNeg(): ?bool
    {
        return $this->autoNeg;
    }

    public function setAutoNeg(bool $autoNeg): self
    {
        $this->autoNeg = $autoNeg;

        return $this;
    }

    public function getSpeed(): ?string
    {
        return $this->speed;
    }

    public function setSpeed(?string $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getDuplex(): ?string
    {
        return $this->duplex;
    }

    public function setDuplex(?string $duplex): self
    {
        $this->duplex = $duplex;

        return $this;
    }

    public function getFlowCtrl(): ?bool
    {
        return $this->flowCtrl;
    }

    public function setFlowCtrl(bool $flowCtrl): self
    {
        $this->flowCtrl = $flowCtrl;

        return $this;
    }

    public function getDestiny(): ?string
    {
        return $this->destiny;
    }

    public function setDestiny(?string $destiny): self
    {
        $this->destiny = $destiny;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getSwitch(): ?Switchs
    {
        return $this->switch;
    }

    public function setSwitch(?Switchs $switch): self
    {
        $this->switch = $switch;

        return $this;
    }
}
