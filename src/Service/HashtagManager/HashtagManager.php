<?php

namespace App\Service\HashtagManager;

use App\Entity\Hashtag;
use App\Repository\HashtagRepository;
use App\Service\TextParser\HashtagParser;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class HashtagManager
 * @package App\Service\HashtagManager
 */
class HashtagManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var HashtagRepository
     */
    private $hashtagRepository;
    /**
     * @var HashtagParser
     */
    private $hashtagParser;

    /**
     * HashtagManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param HashtagRepository $hashtagRepository
     */
    public function __construct(EntityManagerInterface $entityManager, HashtagRepository $hashtagRepository)
    {
        $this->em = $entityManager;
        $this->hashtagRepository = $hashtagRepository;
        $this->hashtagParser = new HashtagParser('<a href="/hashtag/${1}">${1}</a>');
    }

    /**
     * @param Hashtaggable $hashtaggable
     */
    public function createHashtags(Hashtaggable $hashtaggable)
    {
        $this->hashtagParser->parse($hashtaggable->getContent());
        $hashtagsInText = $this->hashtagParser->getParsedData();
        $hashtagsInDatabase = $this->hashtagRepository->findHashtagsWithNames($hashtagsInText);

        foreach ($hashtagsInText as $textHashtag) {

            $newHashtag = null;

            foreach ($hashtagsInDatabase as $hashtagInDatabase) {
                if ($hashtagInDatabase->getName() === $textHashtag) {
                    $newHashtag = $hashtagInDatabase;
                    break;
                }
            }

            if ($newHashtag === null) {
                $newHashtag = $this->crateNewHashtag($textHashtag);
                $this->em->persist($newHashtag);
            }

            $hashtaggable->addHashtag($newHashtag);
        }
    }

    /**
     * @param string $textHashtag
     * @return Hashtag
     */
    private function crateNewHashtag(string $textHashtag): Hashtag
    {
        $newHashtag = new Hashtag();
        $newHashtag->setName($textHashtag);

        return $newHashtag;
    }
}