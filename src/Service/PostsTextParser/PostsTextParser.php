<?php

namespace App\Service\PostsTextParser;

use App\Entity\Post;

/**
 * Class PostsTextParser
 * @package App\Service\PostsTextParser
 */
class PostsTextParser
{
    /**
     * @var array
     */
    private $parsers;

    /**
     * PostsTextParser constructor.
     * @param array $parsers
     */
    public function __construct(array $parsers)
    {
        $this->parsers = $parsers;
    }

    /**
     * @param Post $post
     */
    public function parse(Post $post)
    {
        foreach ($this->parsers as $parser) {
            $parsedText = $parser->parse($post->getContent());
            $post->setContent($parsedText);
        }
    }

    /**
     * @param array $posts
     */
    public function parseMany(array $posts)
    {
        foreach ($posts as $post) {
            $this->parse($post);
        }
    }
}