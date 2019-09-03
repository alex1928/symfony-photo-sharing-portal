<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\CommentLike;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route("/comment/{id<\d+>}/like", name="comment_like")
     */
    public function like(Request $request, Comment $comment)
    {
        $user = $this->getUser();

        if ($comment->isLikedBy($user)) {
            return $this->json(["success" => false, "message" => "Comment was already liked."]);
        }

        $em = $this->getDoctrine()->getManager();

        $like = new CommentLike();
        $like->setComment($comment);
        $like->setUser($user);

        $em->persist($like);
        $em->flush();

        $likeCount = $comment->getLikesCount();

        return $this->json(["success" => true, "message" => "Comment has been liked.", "likeCount"=>$likeCount]);
    }
}
