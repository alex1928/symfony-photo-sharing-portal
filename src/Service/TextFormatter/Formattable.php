<?php

namespace App\Service\TextFormatter;

interface Formattable
{
    public function getContent(): ?string;
    public function setContent(?string $content);
}