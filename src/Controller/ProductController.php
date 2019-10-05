<?php

namespace App\Controller;

use App\DTO\CreateProductDTO;
use App\DTO\UpdateProductDTO;
use App\Entity\Customer;
use App\Entity\Product;
use App\Form\CreateProductType;
use App\Form\UpdateProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer/{uuid}/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(Customer $customer, ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy(['customer' => $customer]),
            'customer' => $customer
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, Customer $customer): Response
    {
        $productDTO = new CreateProductDTO();
        $form = $this->createForm(CreateProductType::class, $productDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = new Product($productDTO, $customer);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', ['uuid' => $customer->getUuid()]);
        }

        return $this->render('product/new.html.twig', [
            'product' => $productDTO,
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{issn}", name="product_show", methods={"GET"})
     */
    public function show(Product $product, Customer $customer): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{issn}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Customer $customer, Product $product): Response
    {
        $productDTO = new UpdateProductDTO();
        $productDTO->name = $product->getName();
        $productDTO->status = $product->getStatus();

        $form = $this->createForm(UpdateProductType::class, $productDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->update($productDTO);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', ['uuid' => $customer->getUuid()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{issn}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Customer $customer, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getIssn(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', ['uuid' => $customer->getUuid()]);
    }
}
