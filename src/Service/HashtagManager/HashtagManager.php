<?php

namespace App\Service\HashtagManager;

use App\Entity\Hashtag;
use App\Repository\HashtagRepository;
use App\Service\TextFormatter\Formatters\HashtagFormatter;
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
     * @var HashtagFormatter
     */
    private $hashtagFormatter;

    /**
     * HashtagManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param HashtagRepository $hashtagRepository
     */
    public function __construct(EntityManagerInterface $entityManager, HashtagRepository $hashtagRepository)
    {
        $this->em = $entityManager;
        $this->hashtagRepository = $hashtagRepository;
        $this->hashtagFormatter = new HashtagFormatter('<a href="/hashtag/${1}">${1}</a>');
    }

    /**
     * @param Hashtaggable $hashtaggable
     */
    public function createHashtags(Hashtaggable $hashtaggable)
    {
        $this->hashtagFormatter->format($hashtaggable->getContent());
        $hashtagsInText = $this->hashtagFormatter->getParsedData();
        $hashtagsInDatabase = $this->hashtagRepository->findHashtagsWithNames($hashtagsInText);

        $assignedHashtags = $hashtaggable->getHashtags();

        if($assignedHashtags->count() > 0) {
            $hashtaggable->clearHashtags();
        }

        foreach ($hashtagsInText as $textHashtag) {
            $hashtag = $this->getOrCreateHashtag($textHashtag, $hashtagsInDatabase);
            $hashtaggable->addHashtag($hashtag);
        }
    }

    /**
     * @param string $textHashtag
     * @param array $hashtagsInDatabase
     */
    private function getOrCreateHashtag(string $textHashtag, array $hashtagsInDatabase): Hashtag
    {
        $newHashtag = null;

        foreach ($hashtagsInDatabase as $hashtagInDatabase) {
            if ($hashtagInDatabase->getName() === $textHashtag) {
                $newHashtag = $hashtagInDatabase;
                break;
            }
        }

        if ($newHashtag === null) {
            $newHashtag = new Hashtag($textHashtag);
            $this->em->persist($newHashtag);
        }

        return $newHashtag;
    }
}