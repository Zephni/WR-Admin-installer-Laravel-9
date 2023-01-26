<?php
    /**
     * Useful for coalescing to the next value in a chain of ?? operators, like so:
     * coalesce($condition) ?? $someValue
     * The above will return $someValue if $condition is true, otherwise false.
     * @param bool $condition
     * @return null|bool
     */
    function coalesce(bool $condition): null|bool
    {
        return $condition ? null : false;
    }

    /**
     * Removes all false values from the given array
     * @param array $array
     * @return array filtered array
     */
    function falseless(array $array): array
    {
        return array_filter($array, function($item) {
            return $item !== false;
        });
    }

    /**
     * Returns a key value array as a string, with each key value pair separated by the given separator
     * @param array $array
     * @param string $groupSeparator
     * @param string $keyValueSeparator
     * @return string
     */
    function arrayToString(array $array, string $groupSeparator = '<br />', $keyValueSeparator = ': ', $nestedArraySeperator = '<br />', $nestedIndentor ='&emsp;', bool $renderNonStringValues = true, $indentationLevel = 1): string
    {
        return implode($groupSeparator, array_map(function ($v, $k) use ($groupSeparator, $keyValueSeparator, $nestedArraySeperator, $renderNonStringValues, $nestedIndentor, $indentationLevel){
            $repeatedNestedIndentor = Str::repeat($nestedIndentor, $indentationLevel);

            if(is_array($v))
            {
                $indentationLevel++;
                $v = $nestedArraySeperator.arrayToString($v, $groupSeparator, $keyValueSeparator, $nestedArraySeperator, $nestedIndentor, $renderNonStringValues, $indentationLevel);
            }

            if($renderNonStringValues && !is_string($v))
            {
                $v = json_encode($v);
            }

            return $repeatedNestedIndentor . $k . $keyValueSeparator . $v;

        }, $array, array_keys($array)));

    }
