<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $UserPasswordEncoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $UserPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $UserPasswordEncoder)
    {
        $this->UserPasswordEncoder = $UserPasswordEncoder;
    }


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setPassword($this->UserPasswordEncoder->encodePassword($user, "password"));
            $user->setEmail(sprintf("email+%d@email.com", $i));
            $user->setCompanyName(sprintf("Company+%d", $i));
            $manager->persist($user);

            for ($j = 1; $j <= 5; $j++) {
                $customer = new Customer();
                $customer->setEmail(sprintf("customer-email+%d@email.com", $j));
                $customer->setFirstName(sprintf("firstName+%d", $j));
                $customer->setLastName(sprintf("lastName+%d", $j));
                $customer->setUser($user);
                $manager->persist($customer);
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setTitle(sprintf("title product %d", $i));
            $product->setContent(sprintf("content product %d", $i));
            $product->setBrand(sprintf("brand product %d", $i));
            $product->setCamera(sprintf("camera product %d", $i));
            $product->setModel(sprintf("model product %d", $i));
            $product->setReference($i);
            $product->setScreenSize($i);
            $product->setStock(8);
            $manager->persist($product);

            for ($j = 1; $j <= 3; $j++) {
                $image = new Image();
                $image->setProduct($product);
                $image->setFileName(sprintf("image%d.jpeg", $j));
                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
