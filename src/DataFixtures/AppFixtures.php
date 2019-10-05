<?php

namespace App\DataFixtures;

use App\DTO\CreateCustomerDTO;
use App\DTO\CreateProductDTO;
use App\DTO\UpdateCustomerDTO;
use App\DTO\UpdateProductDTO;
use App\Entity\Customer;
use App\Entity\Product;
use App\Enum\Status;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $createCustomerDTO = new CreateCustomerDTO();
            $createCustomerDTO->firstName = "firstName {$i}";
            $createCustomerDTO->lastName = "lastName {$i}";
            $createCustomerDTO->dateOfBirth = new DateTimeImmutable(mt_rand(1, 55). ' years ago');

            $customer = new Customer($createCustomerDTO);

            if (mt_rand(0, 1)) {
                $updateCustomerDTO = new UpdateCustomerDTO();
                $updateCustomerDTO->firstName = $createCustomerDTO->firstName;
                $updateCustomerDTO->lastName = $createCustomerDTO->lastName;
                $updateCustomerDTO->dateOfBirth = $createCustomerDTO->dateOfBirth;
                $updateCustomerDTO->status = Status::allExternal()[mt_rand(0, 3)];

                $customer->update($updateCustomerDTO);
            }

            $manager->persist($customer);

            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $createProductDTO = new CreateProductDTO();
                $createProductDTO->name = "Name {$i}";

                $product = new Product($createProductDTO, $customer);

                if (mt_rand(0, 1)) {
                    $updateProductDTO = new UpdateProductDTO();
                    $updateProductDTO->name = $createProductDTO->name;
                    $updateProductDTO->status = Status::allExternal()[mt_rand(0, 3)];

                    $product->update($updateProductDTO);
                }

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
