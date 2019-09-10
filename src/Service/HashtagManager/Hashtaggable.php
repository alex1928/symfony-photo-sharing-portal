<?php

namespace App\Service\HashtagManager;

use App\Entity\Hashtag;

interface Hashtaggable
{
    public function addHashtag(Hashtag $hashtag);
    public function getContent(): ?string;
}