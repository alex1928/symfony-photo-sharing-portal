<?php

namespace App\Service\TextFormatter;

interface TextFormatterInterface
{
    public function format(Formattable $formattable);
    public function formatMany(array $formattables);
}