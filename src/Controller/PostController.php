<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentFormType;
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
        $comment = new Comment();

        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            $comment->setUser($user);
            $comment->setPost($post);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', "Comment has been added.");

            return $this->redirectToRoute('show_post', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
            'comment_form' => $commentForm->createView(),
        ]);
    }

}
