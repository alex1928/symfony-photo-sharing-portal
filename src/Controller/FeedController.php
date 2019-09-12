<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Service\ImageUploader\PostImageUploader;
use App\Service\TextFormatter\BasicTextFormatter;
use App\Service\TextFormatter\Formatters\HashtagFormatter;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    /**
     * @Route("/feed", name="feed")
     * @param Request $request
     * @param PostRepository $postRepository
     * @param PostImageUploader $imageUploader
     * @return Response
     * @throws Exception
     */
    public function index(Request $request, PostRepository $postRepository, PostImageUploader $imageUploader)
    {
        $post = new Post();
        $newPostForm = $this->createForm(PostFormType::class, $post);
        $imagesDirectory = $this->getParameter('images_directory');
        $posts = $postRepository->getLastPosts(10);

        $hashtagFormatter = new HashtagFormatter('<a href="/hashtag/${1}">${1}</a>');
        $postsFormatter = new BasicTextFormatter([$hashtagFormatter]);
        $postsFormatter->formatMany($posts);


        return $this->render('feed/index.html.twig', [
            'controller_name' => 'FeedController',
            'post_form' => $newPostForm->createView(),
            'post_image_directory' => $imagesDirectory,
            'posts' => $posts,
        ]);
    }
}
