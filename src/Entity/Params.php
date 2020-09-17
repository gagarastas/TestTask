<?php

namespace App\Entity;

use App\Repository\ParamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParamsRepository::class)
 */
class Params
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
    private $paramText;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParamsText(): ?string
    {
        return $this->paramText;
    }

    public function setParamsText(string $paramText): self
    {
        $this->paramText = $paramText;

        return $this;
    }
}
