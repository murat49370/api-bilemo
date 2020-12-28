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


/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/customers")
 */
class CustomerController extends AbstractController
{

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
     */
    public function item(Customer $customer, SerializerInterface $serializer, TokenStorageInterface $tokenStorage): JsonResponse
    {
        if ($customer->getUser() === $tokenStorage->getToken()->getUser())
        {
            return new JsonResponse(
                $serializer->serialize($customer, "json", ["groups" => "get"]),
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

        return new JsonResponse(
            $serializer->serialize($customer, "json", ["groups" => "get"]),
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