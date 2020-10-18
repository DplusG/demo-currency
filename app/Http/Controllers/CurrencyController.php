<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\DbRangeRepository;
use App\Models\DbCurrencyRepository;
use App\Components\Currency\Models\Range;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function response($data)
    {
        return response($data, 200)->header('Content-Type', 'text/json');
    }

    public function actionGetByDate($day, $month, $year)
    {
        try {
            $repo = new DbCurrencyRepository();
            $res = $repo->findByDate("$day/$month/$year");
            return $this->response($res);
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }
    }

    public function actionForm()
    {
        try {
            return view('/welcome');
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }
    }

    public function actionGetListByDate(Request $request)
    {
        try {
            $data = $request->post();
            $d = DateTime::createFromFormat('Y-m-d', $data['date']);
            $date = $d->format('d/m/Y');
            $currencyList = array_map('trim', explode(',', $data['list']));
            $note = $data['note'];

            $range = new Range(new DbCurrencyRepository(), new DbRangeRepository());
            $range->fill(compact('currencyList', 'date', 'note'));
            return $this->response($range->prepareAndSave());
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }
    }

    public function actionGetListById($id)
    {
        try {
            $repo = new DbRangeRepository();
            return $this->response($repo->find('id', $id));
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }
    }
}
