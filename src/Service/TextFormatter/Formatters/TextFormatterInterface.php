<?php

namespace App\Service\TextFormatter\Formatters;

interface TextFormatterInterface
{
    public function format(string $rawText): string;
    public function getFormattedText(): string;
    public function getRawText(): string;
    public function getParsedData(): array;
}