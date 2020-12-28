<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @return JsonResponse
     */
    public function collection(CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($customerRepository->findAll(), "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );

    }

    /**
     * @Route("/{id}", name="api_customers_item_get", methods={"GET"})
     * @param Customer $customer
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(Customer $customer, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($customer, "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );

    }

    /**
     * @Route(name="api_customers_collection_post", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    public function post(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json');

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(["id" => 1]);

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
     * @return JsonResponse
     */
    public function delete(Customer $customer, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($customer);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

    }

}