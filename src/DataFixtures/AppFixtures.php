<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $roles = ["ROLE_USER", "ROLE_ADMIN"];

        $user = new User();
        $user->setUsername("admin");
        $user->setEmail("admin@test.com");

        $password = $this->passwordEncoder->encodePassword($user, 'devpass');
        $user->setPassword($password);
        $user->setRegisterDate(new \DateTime('now'));
        $user->setRoles($roles);

        $manager->persist($user);



        $manager->flush();
    }




}
