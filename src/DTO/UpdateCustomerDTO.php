<?php declare(strict_types=1);

namespace App\DTO;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCustomerDTO
{
    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    public $firstName;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    public $lastName;

    /**
     * @var DateTimeInterface
     * @Assert\Date()
     */
    public $dateOfBirth;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Choice({"pending", "in review", "approved", "inactive"})
     */
    public $status;
}
