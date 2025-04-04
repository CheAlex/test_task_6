<?php

declare(strict_types=1);

namespace App\Util;

class ArrayUtil
{
    public static function flattenArray(array $array, string $delimiter = '.'): array
    {
        $result = [];
        $stack = [['', $array]];

        while ($stack) {
            [$prefix, $current] = array_pop($stack);

            foreach ($current as $key => $value) {
                $newKey = $prefix === '' ? $key : ($prefix.$delimiter.$key);

                if (is_array($value)) {
                    $stack[] = [$newKey, $value];
                } else {
                    $result[$newKey] = $value;
                }
            }
        }

        return $result;
    }

    public static function unflattenArray(array $flatArray, string $delimiter = '.'): array
    {
        $result = [];

        foreach ($flatArray as $flatKey => $value) {
            $keys = explode($delimiter, $flatKey);
            $temp =& $result;

            foreach ($keys as $key) {
                if (!isset($temp[$key]) || !is_array($temp[$key])) {
                    $temp[$key] = [];
                }
                $temp =& $temp[$key];
            }

            $temp = $value;
        }

        return $result;
    }
}
