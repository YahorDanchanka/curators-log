<?php

namespace App\Helpers;

class PhpWordPurifier
{
    public static function purify(mixed $value): mixed
    {
        return is_array($value) ? self::purifyArray($value) : self::purifyString((string) $value);
    }

    public static function purifyString(string $value): string
    {
        $text = htmlspecialchars($value);
        $text = preg_replace('/&lt;w:br\s?\/&gt;/', '<w:br />', $text);
        return $text;
    }

    public static function purifyArray(array $array): array
    {
        return collect($array)
            ->mapWithKeys(
                fn(mixed $value, $key) => [
                    $key => self::purify($value),
                ]
            )
            ->toArray();
    }
}
