<?php

namespace App\Models;

use App\Components\Currency\Models\Range;
use Illuminate\Database\Eloquent\Model;

class RangeRecord extends Model
{
    protected $fillable = ['date', 'currencyList', 'result', 'note'];

    protected $table = 'range';

    public function batch(array $arr)
    {
        foreach ($arr as $curr) {
            $this->saveRange($curr);
        }

        return true;
    }

    public function saveRange(Range $range)
    {
        $attrs = $range->getAttributes();
        $attrs['currencyList'] = implode(', ', $attrs['currencyList']);
        $attrs['result'] = json_encode($attrs['result']);

        $model = (new static());
        $model->fill($attrs);
        $model->save();
        return $model;
    }
}
