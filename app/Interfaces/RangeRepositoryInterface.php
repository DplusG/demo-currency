<?php

namespace App\Interfaces;

interface RangeRepositoryInterface
{
    /**
     * Сохранение
     *
     * @param $data
     * @return mixed
     */
    public function save($data);

    public function find($key, $value);
}