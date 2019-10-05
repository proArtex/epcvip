<?php declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductDTO
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $issn;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    public $name;
}
