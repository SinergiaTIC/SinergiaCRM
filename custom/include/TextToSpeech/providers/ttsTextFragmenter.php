<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class TtsTextFragmenter
{
    private $maxChars;

    public function __construct($maxChars = 2000)
    {
        $this->maxChars = $maxChars;
    }

    public function fragment($text)
    {
        $max = $this->maxChars;
        if (mb_strlen($text) <= $max) {
            return array($text);
        }

        $fragments = array();
        $remaining = $text;

        while (mb_strlen($remaining) > $max) {
            $chunk = mb_substr($remaining, 0, $max);
            $cutPos = $this->findCutPosition($chunk);

            if ($cutPos <= 0) {
                $cutPos = $max;
            }

            $fragments[] = mb_substr($remaining, 0, $cutPos);
            $remaining = mb_substr($remaining, $cutPos);
        }

        if (mb_strlen($remaining) > 0) {
            $fragments[] = $remaining;
        }

        return $fragments;
    }

    private function findCutPosition($text)
    {
        $length = mb_strlen($text);
        $sentenceDelimiters = array('. ', '! ', '? ', ".\n", "!\n", "?\n", ";\n", ";\r\n", ".\r\n", "!\r\n", "?\r\n");

        $bestPos = -1;
        foreach ($sentenceDelimiters as $delim) {
            $pos = mb_strrpos($text, $delim);
            if ($pos !== false && $pos > $bestPos) {
                $bestPos = $pos + mb_strlen($delim);
            }
        }
        if ($bestPos > 0) {
            return $bestPos;
        }

        $pos = mb_strrpos($text, ', ');
        if ($pos !== false) {
            return $pos + 2;
        }

        $pos = mb_strrpos($text, ' ');
        if ($pos !== false) {
            return $pos + 1;
        }

        return 0;
    }
}
