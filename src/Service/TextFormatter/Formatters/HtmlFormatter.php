<?php

namespace App\Service\TextFormatter\Formatters;


/**
 * Class HtmlEntitiesFormatter
 * @package App\Service\TextFormatter\Formatters
 */
class HtmlFormatter implements TextFormatterInterface
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
     * @param string $rawText
     * @return string
     */
    public function format(string $rawText): string
    {
        $this->rawText = $rawText;

        $this->formattedText = htmlentities($this->rawText);
        $this->formattedText = nl2br($this->formattedText);

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
        return [];
    }
}