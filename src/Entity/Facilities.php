<?php

namespace App\Entity;

use App\Repository\FacilitiesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacilitiesRepository::class)
 */
class Facilities
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $facilityText;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacilityText(): ?string
    {
        return $this->facilityText;
    }

    public function setFacilityText(string $facilityText): self
    {
        $this->facilityText = $facilityText;

        return $this;
    }
}
