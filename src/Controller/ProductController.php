<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/products")
 */
class ProductController extends AbstractController
{

    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Route(name="api_products_collection_get", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_products_item_get", methods={"GET"})
     * @param Product $product
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $absoluteUrl = $this->router->generate('api_products_item_get', ['id' => $product->getId()], urlGeneratorInterface::ABSOLUTE_URL);
        
        $response = [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'content' => $product->getContent(),
            'stock' => $product->getStock(),
            'reference' => $product->getReference(),
            'brand' => $product->getBrand(),
            'model' => $product->getModel(),
            'camera' => $product->getCamera(),
            'screenSize' => $product->getScreenSize(),
            '_link' => [
                'self' => [
                    'href' => $absoluteUrl
                ],
                'modify' => [
                    'href' => $absoluteUrl
                ],
                'delete' => [
                    'href' => $absoluteUrl
                ]
            ]
        ];

        return new JsonResponse(
            $serializer->serialize($response, "json"),
            JsonResponse::HTTP_OK,
            [],
            true
        );

    }

}