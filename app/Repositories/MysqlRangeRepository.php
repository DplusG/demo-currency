<?php

namespace App\Repositories;

use App\Components\Currency\Models\Range;
use App\Interfaces\RangeRepository;
use Illuminate\Support\Facades\DB;

class MysqlRangeRepository implements RangeRepository
{
    protected $table = 'range';

    public function save(Range $range)
    {
        $attrs = $range->getAttributes();
        $attrs['currencyList'] = implode(', ', $attrs['currencyList']);
        $attrs['result'] = json_encode($attrs['result']);

        DB::table($this->table)->insert($attrs);

        return true;
    }

    public function batch(array $arr)
    {
        foreach ($arr as $curr) {
            $this->save($curr);
        }

        return true;
    }

    public function find($key, $value)
    {
        $query = DB::table($this->table)->where($key, '=', $value);
        $collection = $query->get();
        $res = $collection->all();

        return !empty($res[0]) ? json_encode($res[0]) : '{}';
    }
}