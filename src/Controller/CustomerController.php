<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateCustomerDTO;
use App\DTO\UpdateCustomerDTO;
use App\Entity\Customer;
use App\Form\CreateCustomerType;
use App\Form\UpdateCustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/", name="customer_index", methods={"GET"})
     */
    public function index(): Response
    {
        $customers = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }

    /**
     * @Route("/new", name="customer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $customerDTO = new CreateCustomerDTO();
        $form = $this->createForm(CreateCustomerType::class, $customerDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = new Customer($customerDTO);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customerDTO,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="customer_show", methods={"GET"})
     */
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="customer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Customer $customer): Response
    {
        $customerDTO = new UpdateCustomerDTO();
        $customerDTO->firstName = $customer->getFirstName();
        $customerDTO->lastName = $customer->getLastName();
        $customerDTO->dateOfBirth = $customer->getDateOfBirth();
        $customerDTO->status = $customer->getStatus();

        $form = $this->createForm(UpdateCustomerType::class, $customerDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->update($customerDTO);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="customer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Customer $customer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getUuid(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_index');
    }
}
