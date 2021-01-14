<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;



/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/customers")
 */
class CustomerController extends AbstractFOSRestController
{

    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Route(name="api_customers_collection_get", methods={"GET"})
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @param TokenStorageInterface $tokenStorage
     * @return JsonResponse
     */
    public function collection(
        CustomerRepository $customerRepository,
        SerializerInterface $serializer,
        TokenStorageInterface $tokenStorage
    ): JsonResponse
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();
        return new JsonResponse(
            $serializer->serialize($customerRepository->findBy(["user" => $user->getId()]), "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );

    }

    /**
     * @Route("/{id}", name="api_customers_item_get", methods={"GET"})
     * @param Customer $customer
     * @param SerializerInterface $serializer
     * @param TokenStorageInterface $tokenStorage
     * @return JsonResponse
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Customer::class, groups={"get"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="rewards")
     * @Security(name="Bearer")
     */
    public function item(Customer $customer, SerializerInterface $serializer, TokenStorageInterface $tokenStorage): JsonResponse
    {
        if ($customer->getUser() === $tokenStorage->getToken()->getUser())
        {
            $absoluteUrl = $this->router->generate('api_customers_item_get', ['id' => $customer->getId()], urlGeneratorInterface::ABSOLUTE_URL);

            $response = [
                'id' => $customer->getId(),
                'email' => $customer->getEmail(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'registredAt' => $customer->getRegisteredAt(),
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
        }else{
            return new JsonResponse(
                ['code' => JsonResponse::HTTP_FORBIDDEN, 'status' => "You do not have permission for this user"],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

    }


    /**
     * @Route(name="api_customers_collection_post", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param TokenStorageInterface $tokenStorage
     * @return JsonResponse
     */
    public function post(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage
    ): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json');

        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $customer->setUser($user);

        $entityManager->persist($customer);
        $entityManager->flush();

        $absoluteUrl = $this->router->generate('api_customers_item_get', ['id' => $customer->getId()], urlGeneratorInterface::ABSOLUTE_URL);
        $response = [
            'id' => $customer->getId(),
            'email' => $customer->getEmail(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'registredAt' => $customer->getRegisteredAt(),
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
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_customers_item_get", ["id" => $customer->getId()])],
            true
        );

    }

    /**
     * @Route("/{id}", name="api_customers_item_delete", methods={"DELETE"})
     * @param Customer $customer
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @return JsonResponse
     */
    public function delete(Customer $customer, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        if ($customer->getUser() === $tokenStorage->getToken()->getUser())
        {
            $entityManager->remove($customer);
            $entityManager->flush();

            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }else{
            return new JsonResponse(
                ['code' => JsonResponse::HTTP_FORBIDDEN, 'status' => "You do not have permission for this user"],
                JsonResponse::HTTP_FORBIDDEN
            );
        }


    }



}