<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Hashtag;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Form\CommentFormType;
use App\Form\PostFormType;
use App\Repository\HashtagRepository;
use App\Service\HashtagManager\HashtagManager;
use App\Service\ImageUploader\PostImageUploader;
use App\Service\PostsTextParser\PostsTextParser;
use App\Service\TextParser\HashtagParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostLikeRepository;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{id<\d+>}", name="post_show", methods={"GET"})
     */
    public function index(Post $post)
    {
        $hashtagParser = new HashtagParser('<a href="/hashtag/${1}">${1}</a>');
        $postsParser = new PostsTextParser([$hashtagParser]);
        $postsParser->parse($post);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
            'comment_form' => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/post/new", name="post_new", methods={"POST"})
     * @param Request $request
     * @param PostImageUploader $imageUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newPost(Request $request, PostImageUploader $imageUploader, HashtagRepository $hashtagRepository, HashtagManager $hashtagManager)
    {
        $user = $this->getUser();
        $post = new Post();
        $newPostForm = $this->createForm(PostFormType::class, $post);
        $imagesDirectory = $this->getParameter('images_directory');
        $userImagesDirectory = $imagesDirectory . DIRECTORY_SEPARATOR .$user->getId();

        $newPostForm->handleRequest($request);

        if (!$newPostForm->isSubmitted() || !$newPostForm->isValid()) {

            $this->addFlash("error", 'Complete the form correctly.');
            return $this->redirectToRoute('feed');
        }

        $imageFile = $newPostForm['image']->getData();
        $imageUploader->upload($imageFile, $userImagesDirectory);

        if (!$imageUploader->isSuccessful()) {

            $this->addFlash("error", $imageUploader->getErrorMessage());
            return $this->redirectToRoute('feed');
        }

        $imageFileName = $imageUploader->getFileName();
        $post->setImage($imageFileName);
        $post->setUser($user);
        $hashtagManager->createHashtags($post);

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        $this->addFlash("success", "Post has been added!");
        return $this->redirectToRoute('feed');
    }

    /**
     * @Route("/post/{id<\d+>}/new-comment", name="new_comment", methods={"POST"})
     */
    public function newComment(Request $request, Post $post)
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);

        if (!$commentForm->isSubmitted() || !$commentForm->isValid()) {

            $this->addFlash('error', "Comment has not been added.");

            return $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $comment->setUser($user);
        $comment->setPost($post);

        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', "Comment has been added.");

        return $this->redirectToRoute('post_show', [
            'id' => $post->getId()
        ]);
    }

    /**
     * @Route("/post/{id<\d+>}/like", name="post_like", methods={"POST"})
     */
    public function like(Post $post)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($post->isLikedBy($user)) {

            $userLike = $post->getUserLike($user);
            $post->removePostLike($userLike);

            $message = "Post has been disliked.";
        } else {

            $like = new PostLike();
            $like->setUser($user);
            $post->addPostLike($like);
            $em->persist($like);

            $message = "Post has been liked.";
        }

        $em->persist($post);
        $em->flush();

        $likeCount = $post->getLikesCount();

        return $this->json(["success" => true, "message" => $message, "likeCount"=>$likeCount]);
    }
}
