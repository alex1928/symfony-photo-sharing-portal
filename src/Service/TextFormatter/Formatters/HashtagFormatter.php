<?php

namespace App\Service\TextFormatter\Formatters;


/**
 * Class HashtagFormatter
 * @package App\Service\TextParser
 */
class HashtagFormatter implements TextFormatterInterface
{

    /**
     * @var
     */
    private $rawText;
    /**
     * @var
     */
    private $formattedText;
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
     * HashtagFormatter constructor.
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
    public function format(string $rawText): string
    {
        $this->rawText = $rawText;

        $matches = [];
        preg_match_all($this->searchPattern, $this->rawText, $matches);
        $this->hashtags = $matches[0];

        $this->formattedText = preg_replace($this->searchPattern, $this->replacementPattern, $this->rawText);

        return $this->formattedText;
    }

    /**
     * @return string
     */
    public function getFormattedText(): string
    {
        return $this->formattedText;
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