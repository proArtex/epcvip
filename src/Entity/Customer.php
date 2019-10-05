<?php declare(strict_types=1);

namespace App\Entity;

use App\DTO\CreateCustomerDTO;
use App\DTO\UpdateCustomerDTO;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Customer
{
    /**
     * @var string
     * @ORM\Column(type="string")
     * @ORM\Id()
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $dateOfBirth;

    /**
     * TODO: enum
     * @var string
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Product", mappedBy="customer", cascade={"persist", "remove"})
     */
    private $products;

    public function __construct(CreateCustomerDTO $dto)
    {
        $this->uuid = (string) Uuid::uuid4(); //TODO: binary(16)
        $this->firstName = $dto->firstName;
        $this->lastName = $dto->lastName;
        $this->dateOfBirth = $dto->dateOfBirth;
        $this->status = 'new'; //TODO: remove hardcode
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getDateOfBirth(): DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function update(UpdateCustomerDTO $dto): void
    {
        $this->firstName = $dto->firstName;
        $this->lastName = $dto->lastName;
        $this->dateOfBirth = $dto->dateOfBirth;
        $this->status = $dto->status;
    }
}
