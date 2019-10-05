<?php declare(strict_types=1);

namespace App\Entity;

use App\DTO\CreateProductDTO;
use App\DTO\UpdateProductDTO;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Product
{
    /**
     * @var string
     * @ORM\Column(type="string")
     * @ORM\Id()
     */
    private $issn;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

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
     * @var Customer
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="products")
     * @ORM\JoinColumn(name="customer", referencedColumnName="uuid", nullable=false)
     */
    private $customer;

    public function __construct(CreateProductDTO $dto, Customer $customer)
    {
        $this->issn = $dto->issn;
        $this->name = $dto->name;
        $this->status = 'new'; //TODO: remove hardcode
        $this->customer = $customer;
    }

    public function getIssn(): string
    {
        return $this->issn;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function update(UpdateProductDTO $dto): void
    {
        $this->name = $dto->name;
        $this->status = $dto->status;
    }
}
