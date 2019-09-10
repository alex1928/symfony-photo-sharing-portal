<?php

namespace App\Service\TextParser;

class HashtagParser implements TextParserInterface
{
    private $rawText;
    private $parsedText;
    private $hashtags;
    private $replacementPattern;
    private $searchPattern = "/(#\w+)/";

    public function __construct(string $replacementPattern)
    {
        $this->replacementPattern = $replacementPattern;
    }

    public function parse(string $rawText): string
    {
        $this->rawText = $rawText;

        $matches = [];
        preg_match_all($this->searchPattern, $this->rawText, $matches);
        $this->hashtags = $matches;

        $this->parsedText = preg_replace($this->searchPattern, $this->replacementPattern, $this->rawText);

        return $this->parsedText;
    }

    public function getParsedText(): string
    {
        return $this->parsedText;
    }

    public function getRawText(): string
    {
        return $this->rawText;
    }

    public function getParsedData(): array
    {
        return $this->hashtags;
    }
}