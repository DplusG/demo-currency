<?php

namespace App\Models;

use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CurrencyRepositoryInterface;
use App\Components\Validators\DateValidator;
use App\Components\Currency\CurrencyRequest;


class DbCurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function getByDate($date, $format = 'd/m/Y', $andWhere = [])
    {
        if (!DateValidator::validateFormat($date, $format)) {
            throw new Exception('Date format must be ' . $format);
        }

        $d = DateTime::createFromFormat($format, $date);
        $d->setTime(0, 0, 0);

        $whereConditions = array_merge($andWhere, [['date', '=', $d->format('Y-m-d H:i:s')]]);

        $query = DB::table('currency');
        foreach ($whereConditions as $condition) {
            if (strtolower($condition[1]) == 'in') {
                $query->whereIn($condition[0], $condition[2]);
            } else {
                $query->where($condition[0], $condition[1], $condition[2]);
            }
        }
        $collection = $query->get();

        return $collection->all();
    }

    public function findByDate($date, $format = 'd/m/Y', $where = [])
    {
        $data = $this->getByDate($date, $format, $where);

        if (!$data) {
            $d = DateTime::createFromFormat($format, $date);
            $d->setTime(0, 0, 0);
            $res = $this->saveFromExternalByDate($d->format('d/m/Y'));

            if ($res) {
                $data = $this->getByDate($date, $format, $where);
            }
        }

        if (!$data) {
            throw new Exception('Ошибка получения данных, пожалуйста, обратитесь по этому номеру 8-123');
        }

        return $data;
    }

    public function saveFromExternalByDate($date)
    {
        $req = new CurrencyRequest();
        $req->setDate($date);
        $req->execute();
        return (new CurrencyRecord())->batchCurrencies($req->getCurrencyList());
    }
}