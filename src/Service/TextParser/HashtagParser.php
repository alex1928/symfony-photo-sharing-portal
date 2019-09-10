<?php

namespace App\Service\TextParser;


/**
 * Class HashtagParser
 * @package App\Service\TextParser
 */
class HashtagParser implements TextParserInterface
{

    /**
     * @var
     */
    private $rawText;
    /**
     * @var
     */
    private $parsedText;
    /**
     * @var
     */
    private $hashtags;
    /**
     * @var string
     */
    private $replacementPattern;
    /**
     * @var string
     */
    private $searchPattern = "/(#\w+)/";

    /**
     * HashtagParser constructor.
     * @param string $replacementPattern
     */
    public function __construct(string $replacementPattern)
    {
        $this->replacementPattern = $replacementPattern;
    }

    /**
     * @param string $rawText
     * @return string
     */
    public function parse(string $rawText): string
    {
        $this->rawText = $rawText;

        $matches = [];
        preg_match_all($this->searchPattern, $this->rawText, $matches);
        $this->hashtags = $matches[0];

        $this->parsedText = preg_replace($this->searchPattern, $this->replacementPattern, $this->rawText);

        return $this->parsedText;
    }

    /**
     * @return string
     */
    public function getParsedText(): string
    {
        return $this->parsedText;
    }

    /**
     * @return string
     */
    public function getRawText(): string
    {
        return $this->rawText;
    }

    /**
     * @return array
     */
    public function getParsedData(): array
    {
        return $this->hashtags;
    }
}