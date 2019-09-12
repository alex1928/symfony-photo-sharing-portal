<?php

namespace App\Service\HashtagManager;

use App\Entity\Hashtag;
use Doctrine\Common\Collections\Collection;

interface Hashtaggable
{
    public function getHashtags(): Collection;
    public function addHashtag(Hashtag $hashtag);
    public function clearHashtags();
    public function getContent(): ?string;
}