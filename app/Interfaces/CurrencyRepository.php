<?php

namespace App\Interfaces;

use App\Components\Currency\Models\Currency;

interface CurrencyRepository
{
    /**
     * Сохранение
     *
     * @param Currency $curr
     * @return mixed
     */
    public function save(Currency $curr);

    public function batch(array $arr);

    /**
     * @param $date
     * @param string $format
     * @param $where
     * @return mixed
     */
    public function findByDate($date, $format = 'd/m/Y', $where);
}