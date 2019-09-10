<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id<\d+>}", name="profile")
     */
    public function index(User $user, PostRepository $postRepository)
    {

        $posts = $postRepository->getLastPostsByUser($user, 15);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
