<?php

namespace App\Service\TextParser;

class HashtagParser implements TextParserInterface
{
    private $rawText;
    private $parsedText;
    private $hashtags;

    private $searchPattern = "/(#\w+)/";

    public function __construct(string $rawText, string $replacementPattern)
    {
        $this->rawText = $rawText;
        $this->parse($this->rawText, $replacementPattern);
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

    private function parse(string $text, string $replacementPattern)
    {
        $matches = [];

        preg_match_all($this->searchPattern, $this->rawText, $matches);
        $this->hashtags = $matches;

        $this->parsedText = preg_replace($this->searchPattern, $replacementPattern, $this->rawText);
    }
}