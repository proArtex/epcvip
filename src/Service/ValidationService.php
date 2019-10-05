<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return string[]
     */
    public function validate(object $object): array
    {
        $errors = [];

        foreach ($this->validator->validate($object) as $violation) {
            $errors[] = "{$violation->getPropertyPath()}: {$violation->getMessage()}";
        }

        return $errors;
    }
}
