<?php

namespace App\Service\TextParser;

interface TextParserInterface
{
    public function parse(string $rawText): string;
    public function getParsedText(): string;
    public function getRawText(): string;
    public function getParsedData(): array;
}