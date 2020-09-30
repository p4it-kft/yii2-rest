<?php
namespace p4it\rest\server\helpers;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class ArrayHelper extends \yii\helpers\ArrayHelper {
    /**
     * @param  array  $array
     * @param  array  $path
     * @return false|mixed
     */
    public static function removeValueByPath(array &$array, array $path) {
        $temp =& $array;

        $depth = count($path);
        foreach($path as $key) {
            $depth--;
            if($depth === 0) {
                $removedValue[$key] = $temp[$key];
                unset($temp[$key]);
                return $removedValue;
            }

            $temp =& $temp[$key];
        }

        return false;
    }

    /**
     * @param  array  $array
     * @param  string  $keyToSearch
     * @return array|false
     */
    public static function getValueAndPath(array $array, string $keyToSearch)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($array),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $path = [];
        $value = null;
        $depthOfTheFoundKey = null;
        foreach ($iterator as $key => $current) {
            if (
                $key === $keyToSearch
                || $iterator->getDepth() < $depthOfTheFoundKey
            ) {
                if (is_null($depthOfTheFoundKey)) {
                    $value = $current;
                }

                array_unshift($path, $key);
                $depthOfTheFoundKey = $iterator->getDepth();
            }
        }

        if (is_null($depthOfTheFoundKey)) {
            return false;
        }

        return [
            'path' => $path,
            'value' => $value
        ];
    }
}