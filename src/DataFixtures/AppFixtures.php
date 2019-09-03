<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    private $passwordEncoder;
    private $faker;
    private $manager;
    private $container;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
        $this->faker = Factory::create();

    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $user = $this->loadUser();

        $this->loadPosts($user);
    }

    private function loadUser(): User
    {
        $roles = ["ROLE_USER", "ROLE_ADMIN"];

        $user = new User();
        $user->setUsername("admin");
        $user->setEmail("admin@test.com");

        $password = $this->passwordEncoder->encodePassword($user, 'devpass');
        $user->setPassword($password);
        $user->setRoles($roles);

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

    private function loadPosts(User $user)
    {
        $post = new Post();

        $content = $this->faker->paragraphs(3, true);
        $post->setContent($content);
        $post->setAddDate(new \DateTime('now'));
        $post->setImage('default.png');
        $post->setUser($user);

        $imageDirectory = $this->container->getParameter('images_directory');
        $userImageDirectory = $imageDirectory . "/" . $user->getId();

        mkdir($userImageDirectory);
        copy($imageDirectory . '/default.png', $userImageDirectory . '/default.png');

        $this->manager->persist($post);
        $this->manager->flush();
    }
}
