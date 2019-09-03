<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Service\ImageUploader\PostImageUploader;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
        $user = $this->getUser();
        $post = new Post();
        $newPostForm = $this->createForm(PostFormType::class, $post);
        $em = $this->getDoctrine()->getManager();

        $imagesDirectory = $this->getParameter('images_directory');

        $newPostForm->handleRequest($request);

        if ($newPostForm->isSubmitted() && $newPostForm->isValid()) {

            $userImagesDirectory = $imagesDirectory . DIRECTORY_SEPARATOR .$user->getId();
            $imageFile = $newPostForm['image']->getData();
            $imageUploader->upload($imageFile, $userImagesDirectory);

            if ($imageUploader->isSuccessful()) {

                $imageFileName = $imageUploader->getFileName();
                $post->setImage($imageFileName);
                $post->setUser($user);

                $em->persist($post);
                $em->flush();

                $this->addFlash("success", "Post has been added!");
            } else {

                $this->addFlash("error", $imageUploader->getErrorMessage());
            }
        }

        $posts = $postRepository->getLastPosts(10);



        return $this->render('feed/index.html.twig', [
            'controller_name' => 'FeedController',
            'post_form' => $newPostForm->createView(),
            'post_image_directory' => $imagesDirectory,
            'posts' => $posts,
        ]);
    }



}
