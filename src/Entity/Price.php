<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriceRepository")
 */
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $gir1;

    /**
     * @ORM\Column(type="float")
     */
    private $gir2;

    /**
     * @ORM\Column(type="float")
     */
    private $gir3;

    /**
     * @ORM\Column(type="float")
     */
    private $gir4;

    /**
     * @ORM\Column(type="float")
     */
    private $gir5;

    /**
     * @ORM\Column(type="float")
     */
    private $gir6;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGir1(): ?float
    {
        return $this->gir1;
    }

    public function setGir1(float $gir1): self
    {
        $this->gir1 = $gir1;

        return $this;
    }

    public function getGir2(): ?float
    {
        return $this->gir2;
    }

    public function setGir2(float $gir2): self
    {
        $this->gir2 = $gir2;

        return $this;
    }

    public function getGir3(): ?float
    {
        return $this->gir3;
    }

    public function setGir3(float $gir3): self
    {
        $this->gir3 = $gir3;

        return $this;
    }

    public function getGir4(): ?float
    {
        return $this->gir4;
    }

    public function setGir4(float $gir4): self
    {
        $this->gir4 = $gir4;

        return $this;
    }

    public function getGir5(): ?float
    {
        return $this->gir5;
    }

    public function setGir5(float $gir5): self
    {
        $this->gir5 = $gir5;

        return $this;
    }

    public function getGir6(): ?float
    {
        return $this->gir6;
    }

    public function setGir6(float $gir6): self
    {
        $this->gir6 = $gir6;

        return $this;
    }
}
