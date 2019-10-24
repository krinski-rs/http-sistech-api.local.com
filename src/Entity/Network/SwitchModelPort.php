<?php

namespace App\Entity\Network;

class SwitchModelPort
{
    private $id;

    private $portType;

    private $quantities;

    private $switchModel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPortType(): ?string
    {
        return $this->portType;
    }

    public function setPortType(string $portType): self
    {
        $this->portType = $portType;

        return $this;
    }

    public function getQuantities(): ?int
    {
        return $this->quantities;
    }

    public function setQuantities(?int $quantities): self
    {
        $this->quantities = $quantities;

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
}
