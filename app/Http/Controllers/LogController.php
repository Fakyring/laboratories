<?php

namespace App\Http\Controllers;

use App\Models\LogModel;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Возвращает последний лог
     */
    function getLast(){
        $log = LogModel::latest('id')->first();
        return $log;
    }

    /**
     * Возвращает лог по его id
     * @param $id
     * @return mixed
     */
    function getById($id){
        $log = LogModel::find($id);
        return $log;
    }
}
