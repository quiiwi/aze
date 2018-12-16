<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HomeRepository")
 */
class Home
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $headFile;

    /**
     * @ORM\Column(type="text")
     */
    private $info1;

    /**
     * @ORM\Column(type="text")
     */
    private $info2;

    /**
     * @ORM\Column(type="text")
     */
    private $info3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getHeadFile(): ?string
    {
        return $this->headFile;
    }

    public function setHeadFile(string $headFile): self
    {
        $this->headFile = $headFile;

        return $this;
    }

    public function getInfo1(): ?string
    {
        return $this->info1;
    }

    public function setInfo1(string $info1): self
    {
        $this->info1 = $info1;

        return $this;
    }

    public function getInfo2(): ?string
    {
        return $this->info2;
    }

    public function setInfo2(string $info2): self
    {
        $this->info2 = $info2;

        return $this;
    }

    public function getInfo3(): ?string
    {
        return $this->info3;
    }

    public function setInfo3(string $info3): self
    {
        $this->info3 = $info3;

        return $this;
    }

    public function getPicture1(): ?string
    {
        return $this->picture1;
    }

    public function setPicture1(string $picture1): self
    {
        $this->picture1 = $picture1;

        return $this;
    }

    public function getPicture2(): ?string
    {
        return $this->picture2;
    }

    public function setPicture2(string $picture2): self
    {
        $this->picture2 = $picture2;

        return $this;
    }

    public function getPicture3(): ?string
    {
        return $this->picture3;
    }

    public function setPicture3(string $picture3): self
    {
        $this->picture3 = $picture3;

        return $this;
    }
}
