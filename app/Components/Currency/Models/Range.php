<?php

namespace App\Components\Currency\Models;

use App\Components\Validators\ListValidator;
use App\Interfaces\CurrencyRepositoryInterface;
use App\Interfaces\RangeRepositoryInterface;
use DateTime;
use Exception;

class Range
{
    use GetSetTrait;

    protected $id;
    protected $date;
    protected $currencyList;
    protected $result;
    protected $note;

    protected $currRepo;
    protected $rangeRepo;

    public function __construct(CurrencyRepositoryInterface $currRepo, RangeRepositoryInterface $rangeRepo)
    {
        $this->currRepo = $currRepo;
        $this->rangeRepo = $rangeRepo;
    }

    public function rules()
    {
        return [
            'currencyList' => [ListValidator::class, 'validateTickers'],
        ];
    }

    public function prepareAndSave()
    {
        try {
            $this->result = $this->prepareResult();

            $d = DateTime::createFromFormat('d/m/Y', $this->date);
            $d->setTime(0, 0, 0);
            $this->date = $d->format('Y-m-d H:i:s');

            return $this->rangeRepo->save($this);
        } catch (Exception $e) {
            return false;
        }
    }

    public function prepareResult()
    {
        $data = $this->currRepo->findByDate($this->date, 'd/m/Y', [['ticker', 'IN', $this->currencyList]]);

        $sorter = array_flip($this->currencyList);

        usort($data, function ($a, $b) use ($sorter) {
            $aIndex = $sorter[$a->ticker];
            $bIndex = $sorter[$b->ticker];
            return $aIndex <= $bIndex ? -1 : 1;
        });

        $res = [];
        $nominal = null;
        foreach ($data as $row) {
            $row = (array)$row;
            if (!$nominal) {
                $nominal = $row['rate'];
            }

            $key = $row['ticker'];
            $res[$key] = bcdiv($nominal, $row['rate'], 4);
        }

        return $res;
    }
}
