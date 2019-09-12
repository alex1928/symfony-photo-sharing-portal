<?php

namespace App\Service\TextFormatter;


/**
 * Class PostsTextParser
 * @package App\Service\PostsTextParser
 */
class BasicTextFormatter
{
    /**
     * @var array
     */
    private $formatters;

    /**
     * PostsTextParser constructor.
     * @param array $parsers
     */
    public function __construct(array $formatters)
    {
        $this->formatters = $formatters;
    }

    /**
     * @param Formattable $formattable
     */
    public function format(Formattable $formattable)
    {
        foreach ($this->formatters as $formatter) {
            $formattedText = $formatter->format($formattable->getContent());
            $formattable->setContent($formattedText);
        }
    }

    /**
     * @param array $formattables
     */
    public function formatMany(array $formattables)
    {
        foreach ($formattables as $post) {
            $this->format($post);
        }
    }
}