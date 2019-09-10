<?php

namespace App\Service\PostsTextParser;

use App\Entity\Post;

class PostsTextParser
{
    private $parsers;

    public function __construct(array $parsers)
    {
        $this->parsers = $parsers;
    }

    public function parse(Post $post)
    {
        foreach($this->parsers as $parser) {
            $parsedText = $parser->parse($post->getContent());
            $post->setContent($parsedText);
        }
    }

    public function parseMany(array $posts)
    {
        foreach($posts as $post) {
            $this->parse($post);
        }
    }
}