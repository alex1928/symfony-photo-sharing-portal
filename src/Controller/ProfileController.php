<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function edit(Request $request, PostRepository $postRepository)
    {
        $user = $this->getUser();
        $posts = $postRepository->getLastPostsByUser($user, 15);

        $form = $this->createFormBuilder($user)
            ->add('aboutMe', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Yours description has been saved.");
            return $this->redirectToRoute('profile_show', [
                'id'=> $user->getId()
            ]);
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'posts' => $posts,
            'form' => $form->createView(),
            'edit' => true
        ]);
    }

    /**
     * @Route("/profile/{id<\d+>}", name="profile_show")
     */
    public function index(User $user, PostRepository $postRepository)
    {
        $posts = $postRepository->getLastPostsByUser($user, 15);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'posts' => $posts,
            'edit' => false
        ]);
    }



}
