<?php

namespace App\Entity;

use App\Repository\ObjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=ObjectsRepository::class)
 */
class Objects
{
    /**
     * @OneToMany(targetEntity="Photos", mappedBy="object_id", cascade="all")
     */
    private $photos;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coordinates;

    /**
     * @ORM\Column(type="text")
     */
    private $description;



    /**
     * @ORM\ManyToMany(targetEntity=Params::class)
     */
    private $objectsParams;

    /**
     * @ORM\ManyToMany(targetEntity=Facilities::class)
     */
    private $objectsFacilities;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->objectsParams = new ArrayCollection();
        $this->objectsFacilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(string $coordinates): self
    {
        $this->coordinates = $coordinates;

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

    /**
     * @return Collection|Params[]
     */
    public function getObjectsParams(): Collection
    {
        return $this->objectsParams;
    }

    public function addObjectsParam(Params $objectsParam): self
    {
        if (!$this->objectsParams->contains($objectsParam)) {
            $this->objectsParams[] = $objectsParam;
        }

        return $this;
    }

    public function removeObjectsParam(Params $objectsParam): self
    {
        if ($this->objectsParams->contains($objectsParam)) {
            $this->objectsParams->removeElement($objectsParam);
        }

        return $this;
    }

    /**
     * @return Collection|Facilities[]
     */
    public function getObjectsFacilities(): Collection
    {
        return $this->objectsFacilities;
    }

    public function addObjectsFacility(Facilities $objectsFacility): self
    {
        if (!$this->objectsFacilities->contains($objectsFacility)) {
            $this->objectsFacilities[] = $objectsFacility;
        }

        return $this;
    }

    public function removeObjectsFacility(Facilities $objectsFacility): self
    {
        if ($this->objectsFacilities->contains($objectsFacility)) {
            $this->objectsFacilities->removeElement($objectsFacility);
        }

        return $this;
    }

    public function getPhotos()
    {
        return $this->photos->toArray();
    }
}
