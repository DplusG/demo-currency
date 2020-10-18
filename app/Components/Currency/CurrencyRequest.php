<?php

namespace App\Components\Currency;

use Exception;
use App\Http\Request;
use App\Components\Validators\DateValidator;
use App\Components\Currency\Models\Currency;

class CurrencyRequest extends Request
{
    protected $url = "http://cbr.ru/scripts/XML_daily.asp";

    /**
     * Результаты
     */
    public $date;
    public $time;
    public $formatDate;
    protected $currencyList;

    public function afterExecute()
    {
        $this->date = $this->parseDateFromResponse();
        $this->time = strtotime($this->date);
        $this->formatDate = date('Y-m-d', $this->time);
        $this->currencyList = $this->parseListFromResponse();
        return $this;
    }

    public function getCurrencyList()
    {
        return $this->currencyList;
    }

    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $date
     * @return mixed
     * @throws Exception
     */
    public function setDate($date)
    {
        if (!DateValidator::validateFormat($date)) {
            throw new Exception('Date format must be 02/10/2020');
        }

        $url = explode('?', $this->url);
        $this->url =  $url[0] . '?' . http_build_query(['date_req' => $date]);
    }

    public function parseListFromResponse()
    {
        $res = $this->getResponse();
        $xml = simplexml_load_string($res);
        $arr = static::xml2array($xml->xpath('Valute'));

        $res = [];
        $date = $this->formatDate;
        foreach ($arr as $currencyData) {
            $curr = new Currency();
            $curr->fill([
                'code' => $currencyData['NumCode'],
                'ticker' => $currencyData['CharCode'],
                'name' => $currencyData['Name'],
                'date' => $date,
                'rate' => bcdiv(str_replace(',', '.', $currencyData['Value']), str_replace(',', '.', $currencyData['Nominal']), 4),
            ]);
            $res[] = $curr;
        }

        return $res;
    }

    public function parseDateFromResponse()
    {
        $res = $this->getResponse();
        $xml = simplexml_load_string($res);

        $xlmAttrs = (array)$xml->attributes();
        $attrs = $xlmAttrs['@attributes'];

        return $attrs['Date'];
    }

    public static function xml2array($xmlObject, $out = [])
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = is_object($node) ? static::xml2array($node) : $node;
        }

        return $out;
    }
}