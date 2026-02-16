<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\Helper;
use Faker\Core\Extension\LoremExtension;
use Faker\Core\Extension\TextExtension;

class Text implements TextExtension
{
    public function realText(int $min = 0, int $max = 200, int $indexSize = 2): string
    {
        // Ensure index size is at least 1
        $indexSize = max(1, $indexSize);

        // Load the example text from the reference file or class
        $exampleText = $this->baseText;

        // Split the text into words
        $words = preg_split('/\s+/', $exampleText, -1, PREG_SPLIT_NO_EMPTY);

        // Build the Markov chain table
        $table = [];
        $wordCount = count($words);
        for ($i = 0; $i < $wordCount - $indexSize; $i++) {
            $key = implode(' ', array_slice($words, $i, $indexSize));
            $value = $words[$i + $indexSize];
            $table[$key][] = $value;
        }

        // Generate the text
        $currentKey = array_rand($table);
        $result = explode(' ', $currentKey);

        while (strlen(implode(' ', $result)) < $max) {
            if (!isset($table[$currentKey])) {
                break;
            }

            $nextWord = $table[$currentKey][array_rand($table[$currentKey])];
            $result[] = $nextWord;

            // Update the current key
            $currentKey = implode(' ', array_slice($result, -$indexSize));
        }

        // Ensure the text meets the minimum length requirement
        $resultText = implode(' ', $result);
        if (strlen($resultText) < $min) {
            return $this->realText($min, $max, $indexSize);
        }

        return $resultText;
    }
}
