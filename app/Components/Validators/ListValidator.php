<?php

namespace App\Components\Validators;

use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;

class ListValidator
{
    public static function validateTickers($arr)
    {
        foreach ($arr as $ticker) {
            $res = static::validateTicker($ticker);
            if (!$res) {
                return $res;
            }
        }

        return true;
    }

    public static function validateTicker($ticker)
    {
        $collection = DB::table('currency')->select('ticker')->groupBy('ticker')->get();
        $tickers = array_map(function ($el) {
            return $el->ticker;
        }, $collection->all());

        /*Log::debug($ticker);
        Log::debug($tickers);*/

        return in_array(mb_strtoupper($ticker), $tickers);
    }
}