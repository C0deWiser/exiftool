<?php

namespace Codewiser\Exiftool\Traits;

trait HasAltLang
{
    private function getLongestEtName(array $names): ?string
    {
        usort($names, function ($a, $b) {
            if (strlen($a) == strlen($b)) {
                return 0;
            }
            return (strlen($a) < strlen($b)) ? -1 : 1;
        });

        return array_pop($names);
    }

    /**
     * Name
     * Name-en
     * Name-en-EN
     * Name-en_EN
     *
     * Will return Name
     */
    public function pureEtName(string|array $names, string $etName): ?string
    {
        $names = is_array($names) ? $names : [$names];

        $matched = array_filter($names, function ($name) use ($etName) {
            return $etName == $name || $name.'-'.$this->langFromEtName($etName) == $etName;
        });

        return $this->getLongestEtName($matched);
    }

    /**
     * Name
     * Name-en -> en
     * Name-en-EN -> en_EN
     */
    public function langFromEtName(string $etName): ?string
    {
        $tails = [];
        if (substr($etName, -6, 1) == '-') {
            $tail = substr($etName, -5);
            if (substr($tail, 2, 1) == '-' || substr($tail, 2, 1) == '_') {
                $tails[] = $tail;
            }
        } elseif (substr($etName, -3, 1) == '-') {
            $tails[] = substr($etName, -2);
        }

        foreach ($tails as $tail) {
            if (locale_canonicalize($tail)) {
                return $tail;
            }
        }

        return null;
    }
}
