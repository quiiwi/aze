<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GirRepository")
 */
class Gir
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $one;

    /**
     * @ORM\Column(type="integer")
     */
    private $two;

    /**
     * @ORM\Column(type="integer")
     */
    private $three;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOne(): ?int
    {
        return $this->one;
    }

    public function setOne(int $one): self
    {
        $this->one = $one;

        return $this;
    }

    public function getTwo(): ?int
    {
        return $this->two;
    }

    public function setTwo(int $two): self
    {
        $this->two = $two;

        return $this;
    }

    public function getThree(): ?int
    {
        return $this->three;
    }

    public function setThree(int $three): self
    {
        $this->three = $three;

        return $this;
    }

    public function __toString()
    {
        return $this->one . " - " . $this->two . " - " . $this->three;
    }
}
