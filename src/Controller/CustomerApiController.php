<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateCustomerDTO;
use App\DTO\UpdateCustomerDTO;
use App\Entity\Customer;
use App\Service\ValidationService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerApiController extends AbstractController
{
    /**
     * @Route("/api/customers", name="customer.create", methods={"POST"})
     */
    public function createCustomer(Request $request, ValidationService $validator)
    {
        $data = json_decode($request->getContent());

        //TODO: handle null & datetime; param converter
        $customerDTO = new CreateCustomerDTO();
        $customerDTO->firstName = $data->firstName;
        $customerDTO->lastName = $data->lastName;
        $customerDTO->dateOfBirth = new DateTimeImmutable($data->dateOfBirth);

        if ($errors = $validator->validate($customerDTO)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer = new Customer($customerDTO);

        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush();

        return new JsonResponse($this->getView($customer), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/customers/{uuid}", name="customer.read", methods={"GET"})
     */
    public function readCustomer(string $uuid)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->getView($customer), Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers/{uuid}", name="customer.update", methods={"PUT"})
     */
    public function updateCustomer(Request $request, string $uuid, ValidationService $validator)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent());

        $customerDTO = new UpdateCustomerDTO();
        $customerDTO->firstName = $data->firstName;
        $customerDTO->lastName = $data->lastName;
        $customerDTO->dateOfBirth = new DateTimeImmutable($data->dateOfBirth);
        $customerDTO->status = $data->status;

        if ($errors = $validator->validate($customerDTO)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer->update($customerDTO);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse($this->getView($customer), Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers/{uuid}", name="customer.delete", methods={"DELETE"})
     */
    public function deleteCustomer(string $uuid)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        //TODO: set deleted status?

        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/customers", name="customer.list.read", methods={"GET"})
     */
    public function readCustomerList()
    {
        $customers = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();

        return new JsonResponse(
            array_map([$this, 'getView'], $customers),
            Response::HTTP_OK
        );
    }

    private function getView(Customer $customer): array
    {
        return [
            'uuid' => $customer->getUuid(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'dateOfBirth' => $customer->getDateOfBirth()->format('Y-m-d'),
            'status' => $customer->getStatus()
        ];
    }
}
