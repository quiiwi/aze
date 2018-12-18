<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Traits\AddressTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstablishementRepository")
 */
class Establishement
{
    use AddressTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Candidacy", mappedBy="establishment")
     */
    private $candidacies;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groupe", inversedBy="establishement")
     */
    private $groupe;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="establishement")
     */
    private $services;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gir")
     */
    private $gir;

    public function __construct()
    {
        $this->candidacies = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->name = "";
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $notation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId() {
        return $this->id;
    }

    public function getNotation(): ?int
    {
        return $this->notation;
    }

    public function setNotation(int $notation): self
    {
        $this->notation = $notation;

        return $this;
    }

    public function getGir()
    {
        return $this->gir;
    }

    public function setGir($gir)
    {
        $this->gir = $gir;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getGroupe()
    {
        return $this->groupe;
    }

    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
    }

    public function getTelephon()
    {
        return $this->telephon;
    }

    public function setTelephon($telephon)
    {
        $this->telephon = $telephon;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function getCandidacies()
    {
        return $this->candidacies;
    }

    public function getMininalPrice()
    {
        // Prix de la chambre, il faut créer un champ ou un objet
        // Cela depent du besoin
        // Exemple avec 50 €
        $priceRoom = 50;

        $number = $this->gir->getThree() + $priceRoom;

        return $number * 3;
    }

    public function getCandidacyAccepted() {
        $count = 0;

        foreach($this->candidacies as $c) {
            if($c->getStatus() === 'Accepté') {
                $count++;
            }
        }

        return $count;
    }

    public function getCandidacyRefused() {
        $count = 0;

        foreach($this->candidacies as $c) {
            if($c->getStatus() === 'Refusé') {
                $count++;
            }
        }

        return $count;
    }

    public function getCandidacyNews() {
        $count = 0;

        foreach($this->candidacies as $c) {
            if($c->getStatus() === 'Nouvelle') {
                $count++;
            }
        }

        return $count;
    }

    public function addService($service)
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
        }

        return $this;

    }

    public function __toString()
    {
        return $this->name;
    }
}
