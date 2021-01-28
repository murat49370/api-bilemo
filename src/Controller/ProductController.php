<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/products")
 */
class ProductController extends AbstractController
{

    private UrlGeneratorInterface $router;

    private $products;

    public function __construct(UrlGeneratorInterface $router, ProductRepository $productRepository)
    {
        $this->router = $router;
        $this->products = $productRepository->findAll();
    }

    /**
     * @OA\Get(
     *     tags={"product"},
     *     description="Get all products.",
     *     path="/products",
     *     security={{
     *     "bearer":{}
     *     }},
     *     @OA\Response(
     *          response="200",
     *          description="Products liste",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product")),
     *     ),
     *     @OA\Response(response="401", ref="#/components/responses/TokenNotFound"),
     * )
     *
     * @Route(name="api_products_collection_get", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @param CacheInterface $cache
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function collection(ProductRepository $productRepository, SerializerInterface $serializer, CacheInterface $cache): JsonResponse
    {

        $products = $cache->get('products', function () {
            return $this->products;
        });

        return new JsonResponse(
            $serializer->serialize($products, "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Get(
     *     tags={"product"},
     *     description="Get product by ID",
     *     path="/products/{id}",
     *     security={{
     *     "bearer":{}
     *     }},
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(
     *          response="200",
     *          description="Product detaille",
     *          @OA\JsonContent(ref="#/components/schemas/Product"),
     *     ),
     *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
     *     @OA\Response(response="400", ref="#/components/responses/InvalidID"),
     *     @OA\Response(response="401", ref="#/components/responses/TokenNotFound"),
     * )
     *
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