<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{id<\d+>}", name="show_post")
     */
    public function index(Request $request, Post $post)
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
        ]);
    }
}
