<?php

namespace App\Models;

use App\Interfaces\RangeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DbRangeRepository implements RangeRepositoryInterface
{
    public function save($data)
    {
        return (new RangeRecord())->saveRange($data);
    }

    public function find($key, $value)
    {
        $query = DB::table('range')->where($key, '=', $value);
        $collection = $query->get();
        $res = $collection->all();

        return !empty($res[0]) ? json_encode($res[0]) : '{}';
    }
}