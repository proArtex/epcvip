<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateProductDTO;
use App\DTO\UpdateProductDTO;
use App\Entity\Customer;
use App\Entity\Product;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductApiController extends AbstractController
{
    /**
     * @Route("/api/customers/{uuid}/products", name="customer.product.create", methods={"POST"})
     */
    public function createProduct(Request $request, string $uuid, ValidationService $validator)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent());

        $productDTO = new CreateProductDTO();
        $productDTO->name = $data->name ?? null;

        if ($errors = $validator->validate($productDTO)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product = new Product($productDTO, $customer);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new JsonResponse($this->getView($product), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/customers/{uuid}/products/{issn}", name="customer.product.read", methods={"GET"})
     */
    public function readProduct(string $uuid, string $issn)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->find($issn);

        if (!$product) {
            return new JsonResponse(['errors' => ['Product not found']], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->getView($product), Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers/{uuid}/products/{issn}", name="customer.product.update", methods={"PUT"})
     */
    public function updateProduct(Request $request, string $uuid, string $issn, ValidationService $validator)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->find($issn);

        if (!$product) {
            return new JsonResponse(['errors' => ['Product not found']], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent());

        $productDTO = new UpdateProductDTO();
        $productDTO->name = $data->name ?? null;
        $productDTO->status = $data->status ?? null;

        if ($errors = $validator->validate($productDTO)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product->update($productDTO);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse($this->getView($product), Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers/{uuid}/products/{issn}", name="customer.product.delete", methods={"DELETE"})
     */
    public function deleteProduct(string $uuid, string $issn)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->find($issn);

        if (!$product) {
            return new JsonResponse(['errors' => ['Product not found']], Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/customers/{uuid}/products", name="customer.product.list.read", methods={"GET"})
     */
    public function readProductList(string $uuid)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(Customer::class)
            ->find($uuid);

        if (!$customer) {
            return new JsonResponse(['errors' => ['Customer not found']], Response::HTTP_NOT_FOUND);
        }

        $products = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findBy(['customer' => $customer]);

        return new JsonResponse(
            array_map([$this, 'getView'], $products),
            Response::HTTP_OK
        );
    }

    private function getView(Product $product): array
    {
        return [
            'issn' => $product->getIssn(),
            'name' => $product->getName(),
            'status' => $product->getStatus()
        ];
    }
}
