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
