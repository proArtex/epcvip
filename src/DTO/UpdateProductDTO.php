<?php declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductDTO
{
    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Choice({"pending", "in review", "approved", "inactive"})
     */
    public $status;
}
