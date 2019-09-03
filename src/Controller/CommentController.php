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
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($comment->isLikedBy($user)) {

            $userLike = $comment->getUserLike($user);
            $comment->removeCommentLike($userLike);
            $em->persist($comment);
            $em->flush();

            $likeCount = $comment->getLikesCount();

            return $this->json(["success" => true, "message" => "Post has been disliked.", "likeCount"=>$likeCount]);
        }

        $like = new CommentLike();
        $like->setUser($user);
        $comment->addCommentLike($like);

        $em->persist($like);
        $em->persist($comment);
        $em->flush();

        $likeCount = $comment->getLikesCount();

        return $this->json(["success" => true, "message" => "Comment has been liked.", "likeCount"=>$likeCount]);
    }
}
