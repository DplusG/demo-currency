<?php

namespace App\Interfaces;

use App\Components\Currency\Models\Range;

interface RangeRepository
{
    /**
     * Сохранение
     *
     * @param Range $range
     * @return mixed
     */
    public function save(Range $range);

    public function batch(array $arr);

    public function find($key, $value);
}