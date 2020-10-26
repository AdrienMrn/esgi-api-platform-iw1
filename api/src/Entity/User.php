<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"user_read_get"}}
 *          },
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"user_read"}},
 *              "swagger_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "groups",
 *                          "in" = "query",
 *                          "description" = "Groups filtered",
 *                          "required" = "false",
 *                          "type" : "array",
 *                          "items" : {
 *                              "type" : "string"
 *                          }
 *                      }
 *                  }
 *               }
 *          },
 *          "delete",
 *          "put",
 *          "patch"
 *     }
 * )
 * @ApiFilter(GroupFilter::class, arguments={"parameterName": "groups", "overrideDefaultGroups": false, "whitelist": {"user_get_pwd"}})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_read", "user_read_get"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups("user_get_pwd")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("user_read_get")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOk;

    /**
     * @ORM\OneToMany(targetEntity=Drunk::class, mappedBy="userId", cascade={"persist"})
     * @Groups({"user_read"})
     */
    private $drunks;

    public function __construct()
    {
        $this->drunks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getIsOk(): ?bool
    {
        return $this->isOk;
    }

    public function setIsOk(bool $isOk): self
    {
        $this->isOk = $isOk;

        return $this;
    }

    /**
     * @return Collection|Drunk[]
     */
    public function getDrunks(): Collection
    {
        return $this->drunks;
    }

    public function addDrunk(Drunk $drunk): self
    {
        if (!$this->drunks->contains($drunk)) {
            $this->drunks[] = $drunk;
            $drunk->setUserId($this);
        }

        return $this;
    }

    public function removeDrunk(Drunk $drunk): self
    {
        if ($this->drunks->contains($drunk)) {
            $this->drunks->removeElement($drunk);
            // set the owning side to null (unless already changed)
            if ($drunk->getUserId() === $this) {
                $drunk->setUserId(null);
            }
        }

        return $this;
    }
}
