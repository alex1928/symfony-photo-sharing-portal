<?php

namespace App\Controller;

use App\Entity\Hashtag;
use App\Repository\HashtagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HashtagController extends AbstractController
{
    /**
     * @Route("/hashtag/{hashtagName}", name="hashtag")
     */
    public function index(string $hashtagName, HashtagRepository $hashtagRepository)
    {
        $hashtagName = "#".$hashtagName;

        $hashtag = $hashtagRepository->findOneBy(['name' => $hashtagName]);

        return $this->render('hashtag/index.html.twig', [
            'controller_name' => 'HashtagController',
            'hashtag' => $hashtag,
        ]);
    }
}
