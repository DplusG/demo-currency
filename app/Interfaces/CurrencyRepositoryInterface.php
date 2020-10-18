<?php

namespace App\Interfaces;

interface CurrencyRepositoryInterface
{
    /**
     * Поиск и сохранение
     *
     * @param $date
     * @param string $format
     * @param $where
     * @return mixed
     */
    public function findByDate($date, $format = 'd/m/Y', $where);

    /**
     * Поиск
     *
     * @param $date
     * @param string $format
     * @param array $andWhere
     * @return bool
     */
    public function getByDate($date, $format = 'd/m/Y', $andWhere = []);

    /**
     * Сохранение
     *
     * @param $date
     * @return mixed
     */
    public function saveFromExternalByDate($date);
}