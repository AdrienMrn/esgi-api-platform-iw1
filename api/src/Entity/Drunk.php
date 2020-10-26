<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DrunkRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DrunkRepository::class)
 */
class Drunk
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="drunks")
     * @Assert\NotBlank(message="error.empty.user")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity=Drink::class, inversedBy="drunks")
     * @Assert\NotBlank(message="error.empty.drink")
     * @Groups({"user_read"})
     */
    private $drinkId;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user_read"})
     */
    private $quantity;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDrinkId(): ?Drink
    {
        return $this->drinkId;
    }

    public function setDrinkId(?Drink $drinkId): self
    {
        $this->drinkId = $drinkId;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return Drunk
     */
    public function setCreated(\DateTime $created): Drunk
    {
        $this->created = $created;
        return $this;
    }
}
