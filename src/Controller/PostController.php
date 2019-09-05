<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Form\CommentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostLikeRepository;

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


    /**
     * @Route("/post/{id<\d+>}/like", name="post_like")
     */
    public function like(Request $request, Post $post, PostLikeRepository $postLikeRepository)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($post->isLikedBy($user)) {

            $userLike = $post->getUserLike($user);
            $post->removePostLike($userLike);
            $em->persist($post);
            $em->flush();

            $likeCount = $post->getLikesCount();

            return $this->json(["success" => true, "message" => "Post has been disliked.", "likeCount"=>$likeCount]);
        }

        $like = new PostLike();
        $like->setUser($user);
        $post->addPostLike($like);

        $em->persist($like);
        $em->persist($post);
        $em->flush();

        $likeCount = $post->getLikesCount();

        return $this->json(["success" => true, "message" => "Post has been liked.", "likeCount"=>$likeCount]);
    }
}