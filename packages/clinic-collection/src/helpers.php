<?php

use Clinic\Collection\Collect;

if (!function_exists('collection')) {
    /**
     * @param iterable<mixed> $items
     */
    function collection(iterable $items = []): Collect
    {
        return Collect::wrap($items);
    }
}


