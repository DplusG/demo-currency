<?php

namespace App\Models;

use App\Components\Currency\Models\Currency;
use Illuminate\Database\Eloquent\Model;

class CurrencyRecord extends Model
{
    protected $fillable = ['code', 'ticker', 'name', 'date', 'rate'];

    protected $table = 'currency';

    public function batchCurrencies(array $arr)
    {
        foreach ($arr as $curr) {
            $this->saveCurrency($curr);
        }

        return true;
    }

    public function saveCurrency(Currency $curr)
    {
        $model = (new static());
        $model->fill($curr->getAttributes());
        return $model->save();
    }
}
