<?php

namespace App\Http\Controllers;

use App\Models\AttributeModel;
use App\Models\EquipmentModel;
use App\Models\LaboratoryModel;
use App\Models\PassportModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    //Web
    function getElements()
    {
        return view('/archive', ['labs' => LaboratoryModel::all(), 'eqs' => EquipmentModel::all(), 'users' => UserModel::all(), 'passports' => PassportModel::all()]);
    }

    function restoreLab($id){
        $lab = LaboratoryModel::find($id);
        $lab->enabled = '1';
        $lab->save();
        return redirect(route('archive'));

    }
    function restoreEq($id){
        $eq = EquipmentModel::find($id);
        $eq->enabled = '1';
        $eq->save();
        return redirect(route('archive'));
    }
    function restoreUser($id){
        if (auth()->user()->role=='1') {
            $user = UserModel::find($id);
            $user->enabled = '1';
            $user->save();
        }
        return redirect(route('archive'));
    }
    function restorePassport($id){
        $passport = PassportModel::find($id);
        $passport->enabled = '1';
        $passport->save();
        return redirect(route('archive'));
    }
}
