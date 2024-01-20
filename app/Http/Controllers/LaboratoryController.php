<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttributeModel;
use App\Models\AttributeValuesModel;
use App\Models\EquipmentModel;
use App\Models\LabAttsModel;
use App\Models\LabEqModel;
use App\Models\LabRespModel;
use App\Models\LabSoftModel;
use App\Models\SoftVersTemplateSoftModel;
use App\Models\SoftwareModel;
use App\Models\SoftwaresVersionsModel;
use App\Models\SubTypeModel;
use App\Models\TemplateSoftModel;
use App\Models\TypeModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\LaboratoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaboratoryController extends Controller
{
    //API
    /**
     * Возвращает все лаборатории
     * @return \Illuminate\Http\JsonResponse
     */
    function allLabs()
    {
        $labs = LaboratoryModel::all();
        foreach ($labs as $lab) {
            if ($lab->enabled == 1)
                $lab->enabled = true;
            else
                $lab->enabled = false;
        }
        return response()->json($labs, 200);
    }

    /**
     * Возвращает ПО в выбранной лаборатории
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function softVers($id)
    {
        $labSoftVers = LabSoftModel::where('laboratory', $id)->get()->all();
        return response()->json($labSoftVers, 200);
    }

    /**
     * Вывести все связи
     * @return \Illuminate\Http\JsonResponse
     */
    function allSoftVers()
    {
        $labSoftVers = LabSoftModel::all();
        return response()->json($labSoftVers, 200);
    }

    /**
     * Возвращает лабораторию по её id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function labById($id)
    {
        $lab = LaboratoryModel::find($id);
        if ($lab->enabled == 1)
            $lab->enabled = true;
        else
            $lab->enabled = false;
        return response()->json($lab, 200);
    }

    /**
     * Возврашает всё ПО
     * @return \Illuminate\Http\JsonResponse
     */
    function getSoft()
    {
        return response()->json(SoftwareModel::all(), 200);
    }

    /**
     * Возвращает версию выбранного софта
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getVersionOfSoft($id)
    {
        $version = SoftwaresVersionsModel::select('id', 'version')->where('software', $id)->get();
        return response()->json($version, 200);
    }

    /**
     * Возвращает все версии
     * @return \Illuminate\Http\JsonResponse
     */
    function allVersions()
    {
        $version = SoftwaresVersionsModel::all();
        return response()->json($version, 200);
    }

    /**
     * Возвращает шаблоны софта
     * @return \Illuminate\Http\JsonResponse
     */
    function getTemplateSoftware()
    {
        $template = TemplateSoftModel::all();
        return response()->json($template, 200);
    }

    /**
     * Вывести весь софт в шаблонах
     * @return \Illuminate\Http\JsonResponse
     */
    function allTemplateSoftware()
    {
        $templateSoft = SoftVersTemplateSoftModel::all();
        return response()->json($templateSoft, 200);
    }

    /**
     * Возвращает софт и версию софта по id шаблона
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getVersionForTemplateSoftware($id)
    {
        $template = SoftVersTemplateSoftModel::select('id', 'soft_vers')->where('soft_template', $id)->get();
        return response()->json($template, 200);
    }

    /**
     * Возвращает все типы
     * @return \Illuminate\Http\JsonResponse
     */
    function allTypes()
    {
        return response()->json(TypeModel::all(), 200);
    }

    /**
     * Возвращает все подтипы у типа
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    function getSubTypes($type)
    {
        $subType = SubTypeModel::where('type', $type)->get()->all();
        return response()->json($subType, 200);
    }

    /**
     * Возвращает ответственных
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getResp($id)
    {
        $users = LabRespModel::select('id', 'responsible')->where('laboratory', $id)->get();
        return response()->json($users, 200);
    }

    /**
     * Возвращает всех ответственных
     * @return \Illuminate\Http\JsonResponse
     */
    function allResp()
    {
        $resps = LabRespModel::all();
        return response()->json($resps, 200);
    }

    /**
     * Возвращает оборудование в лаборатории
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getLabEquipment($id)
    {
        $eqs = LabEqModel::select('id', 'equipment', 'amount', 'teacher')->where('laboratory', $id)->get();
        foreach ($eqs as $eq) {
            if ($eq->teacher == 1)
                $eq->teacher = true;
            else
                $eq->teacher = false;
        }
        return response()->json($eqs, 200);
    }

    /**
     * Вывести все связи лаб с оборудованием
     * @return \Illuminate\Http\JsonResponse
     */
    function allLabEq()
    {
        $eqs = LabEqModel::all();
        foreach ($eqs as $eq) {
            if ($eq->teacher == 1)
                $eq->teacher = true;
            else
                $eq->teacher = false;
        }
        return response()->json($eqs, 200);
    }

    /**
     * Возвращает значения атрибутов оборудования
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getEquipmentValue($id)
    {
        $attVal = LabAttsModel::select('id', 'attribute', 'value')->where('lab_equipment', $id)->get();
        return response()->json($attVal, 200);
    }

    /**
     * Возвращает все значения атрибутов
     * @return \Illuminate\Http\JsonResponse
     */
    function allEqVal()
    {
        $attVals = LabAttsModel::all();
        return response()->json($attVals, 200);
    }

    /**
     * Добавляет лабораторию
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addLab(Request $request)
    {
        $lab = new LaboratoryModel;
        if (isset($_FILES['image']['name'])) {
            $file_name = time() . basename($_FILES['image']['name']);
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                if ($_FILES["image"]["size"] < 4000001) {
                    $file = public_path('img/labs/') . $file_name;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $file)) {
                        $request->merge(['image' => $file_name]);
                    }
                }
            }
        }
        $lab->name = $request->name;
        $lab->type = $request->type;
        $lab->sub_type = $request->sub_type;
        $lab->descr = $request->descr;
        $lab->image = $request->image;
        $lab->save();
        return response()->json(['id' => $lab->id], 200);
    }

    /**
     * Добавляет оборудование в лабораторию
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addLabEq(Request $request)
    {
        $labEq = new LabEqModel;
        $labEq->laboratory = $request->laboratory;
        $labEq->equipment = $request->equipment;
        $labEq->amount = $request->amount;
        $labEq->teacher = $request->teacher;
        $labEq->save();
        return response()->json(['id' => $labEq->id], 200);
    }

    /**
     * Добавляет значени атрибута оборудования
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addLabAttVal(Request $request)
    {
        $labAttVal = new LabAttsModel;
        $labAttVal->lab_equipment = $request->lab_equipment;
        $labAttVal->attribute = $request->attribute;
        $labAttVal->value = $request->value;
        $labAttVal->save();
        return response()->json(['id' => $labAttVal->id], 200);
    }

    /**
     * Добавляет фотографию в лабораторию
     * @param $id
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function addPicture($id, Request $req)
    {
        $lab = LaboratoryModel::find($id);
        $message = '';
        if (isset($_FILES['image']['name'])) {
            $file_name = time() . basename($_FILES['image']['name']);
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                if ($_FILES["image"]["size"] < 4000001) {
                    $file = public_path('img/labs/') . $file_name;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $file)) {
                        $message = $file_name;
                        if ($lab->image != '')
                            unlink(public_path('img/labs/') . $lab->image);
                        $lab->image = $file_name;
                        $lab->update();
                    } else {
                        $message = 'Что-то пошло не так';
                    }
                } else {
                    $message = 'Размер файла не может быть больше 4 мегабайт';
                }
            } else {
                $message = 'Принимаются только файлы формата .png, .jpg и .jpeg';
            }
        } else {
            $message = 'Используйте метод post, пожалуйста';
        }
        return response()->json(['message' => $message], 200);
    }

    /**
     * Добавляет тип
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function addType(Request $req)
    {
        $type = new TypeModel;
        $type->name = $req->name;
        $type->save();
        return response()->json(['id' => $type->id], 200);
    }

    /**
     * Добавляет подтип
     * @param $id
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function addSubType($id, Request $req)
    {
        $subType = new SubTypeModel;
        $subType->type = $id;
        $subType->name = $req->name;
        $subType->save();
        return response()->json(['id' => $subType->id], 200);
    }

    /**
     * Добавляет софт в лабораторию
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addLaboratoriesSoftware(Request $request)
    {
        $labSoft = new LabSoftModel;
        $labSoft->laboratory = $request->laboratory;
        $labSoft->soft_ver = $request->soft_ver;
        $labSoft->save();
        return response()->json(['id' => $labSoft->id], 200);
    }

    /**
     * Добавляет шаблон софта
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addTemplate(Request $request)
    {
        $temp = new TemplateSoftModel;
        $temp->name = $request->name;
        $temp->save();
        return response()->json(['id' => $temp->id], 200);
    }

    /**
     * Соединяет шаблон с софтом
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addTemplateSoftware(Request $request)
    {
        $tempSoft = new SoftVersTemplateSoftModel;
        $tempSoft->soft_vers = $request->soft_vers;
        $tempSoft->soft_template = $request->soft_template;
        $tempSoft->save();
        return response()->json(['id' => $tempSoft->id], 200);
    }

    /**
     * Добавляет софт
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function addSoftware(Request $req)
    {
        $soft = new SoftwareModel;
        $soft->name = $req->name;
        $soft->save();
        return response()->json(['id' => $soft->id], 200);
    }

    /**
     * Добавить версию
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addVersion(Request $request)
    {
        $version = new SoftwaresVersionsModel;
        $version->software = $request->software;
        $version->version = $request->version;
        $version->save();
        return response()->json(['id' => $version->id], 200);
    }

    /**
     * Добавляет ответственных
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addApiResp(Request $request)
    {
        $reps = new LabRespModel;
        $reps->laboratory = $request->laboratory;
        $reps->responsible = $request->responsible;
        $reps->save();
        return response()->json(['id' => $reps->id], 200);
    }

    /**
     * Изменяет лабораторию
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function editLab(Request $request)
    {
        $lab = new LaboratoryModel;
        $lab = LaboratoryModel::find($request->id);
        $lab->update($request->all());
    }

    /**
     * Изменить картинку лаборатории
     * @param $id
     * @param Request $request
     */
    function editImage($id, Request $request)
    {
        $lab = new LaboratoryModel;
        $lab = LaboratoryModel::find($request->id);
        $lab->update($request);
    }

    /**
     * Изменить ответственных
     * @param $id
     * @param Request $request
     */
    function editResp($id, Request $request)
    {
        $labResp = new LabRespModel;
        $labResp = LabRespModel::find($request->id);
        $labResp->update($request);
    }

    /**
     * Изменить софт
     * @param $id
     * @param Request $request
     */
    function editLabSoft($id, Request $request)
    {
        $labSoft = new LabSoftModel;
        $labSoft = LabSoftModel::find($request->id);
        $labSoft->update($request);
    }

    /**
     * Изменить оборудование в лаборатории
     * @param $id
     * @param Request $request
     */
    function editLabEq(Request $request)
    {
        $labEq = new LabEqModel;
        $labEq = LabEqModel::find($request->id);
        if ($request->teacher == 'false')
            $request->merge(['teacher' => 0]);
        else
            $request->merge(['teacher' => 1]);
        $labEq->update($request->all());
    }

    /**
     * Изменить значение атрибута у оборудования в лаборатории
     * @param $id
     * @param Request $request
     */
    function editLabAttVal($id, Request $request)
    {
        $labAtt = new LabAttsModel;
        $labAtt = LabAttsModel::find($request->id);
        $labAtt->update($request);
    }

    /**
     * Удаляет лабораторию
     * @param $id
     * @param $status
     */
    function deleteLab($id, $status)
    {
        LaboratoryModel::where('id', $id)->update(array('enabled' => $status));
    }

    /**
     * Удаляет софт лаборатории
     * @param $id
     */
    function deleteSoft($id)
    {
        LabSoftModel::find($id)->delete();
    }

    /**
     * Удаляет ответственного лаборатории
     * @param $id
     */
    function deleteResp($id)
    {
        LabRespModel::find($id)->delete();
    }

    /**
     * Удаляет оборудование лаборатории
     * @param $id
     */
    function deleteEq($id)
    {
        LabEqModel::find($id)->delete();
    }

    /**
     * Удаляет значение атрибута оборудования лаборатории
     * @param $id
     */
    function deleteAttVal($id)
    {
        LabAttsModel::find($id)->delete();
    }

    //Web
    function getLabs()
    {
        $labs = LaboratoryModel::all();
        foreach ($labs as $lab) {
            $tmp = TypeModel::find($lab->type);
            $lab->type = $tmp->name;
            $tmp = SubTypeModel::find($lab->sub_type);
            $lab->sub_type = $tmp->name;
        }
        return view('/laboratories/home', ['data' => $labs]);
    }

    function deleteWebLab($id)
    {
        $lab = LaboratoryModel::find($id);
        $lab->enabled = '0';
        $lab->save();
        return $this->getLabs();
    }

    function createLab()
    {
        return $this->changeCreateLab(-1);
    }

    function changeCreateLab($id)
    {
        $users = UserModel::all();
        $eqs = EquipmentModel::all();
        $types = TypeModel::all();
        $softwares = SoftwareModel::all();
        if ($id == -1) {
            $responsibles = LabRespModel::all();
            return view('/laboratories/createUpdateLab', ['id' => $id, 'users' => $users, 'eqs' => $eqs, 'types' => $types, 'softwares' => $softwares, 'resps' => $responsibles]);
        } else {
            $lab = LaboratoryModel::find($id);
            $labSoft = LabSoftModel::where('laboratory', $id)->get()->all();
            $labEq = LabEqModel::where('laboratory', $id)->get()->all();
            $responsibles = LabRespModel::where('laboratory', $id)->get()->all();
            $atts = AttributeModel::all();
            $attVals = AttributeValuesModel::all();
            $labAtts = LabAttsModel::all();
            $subTypes = SubTypeModel::all();
            $softVers = SoftwaresVersionsModel::all();
            return view('/laboratories/createUpdateLab', ['id' => $id, 'users' => $users, 'lab' => $lab, 'eqs' => $eqs, 'atts' => $atts,
                'attVals' => $attVals, 'types' => $types, 'subTypes' => $subTypes, 'softwares' => $softwares, 'softVers' => $softVers,
                'labSofts' => $labSoft, 'labEqs' => $labEq, 'labAtts' => $labAtts, 'resps' => $responsibles]);
        }
    }

    //Обновление подтипов
    function loadSubTypes(Request $req)
    {
        $subTypes = SubTypeModel::where('type', '=', $req->id)->get();
        $message = '';
        foreach ($subTypes as $subType) {
            $message .= '<option id="' . $subType->id . '" value="' . $subType->name . '"></option>';
        }
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    //Добавление софта
    function addSoft(Request $req)
    {
        $sofwares = SoftwareModel::all();
        $message = '<div class="row col" id="softwareDiv' . $req->id . '" style="margin-right: 0; margin-left: 0; padding: 0 0">
                    <input class="form-control col soft" type="text" position="' . $req->id . '" name="software' . $req->id . '" id="software' . $req->id . '" onchange="loadVersions(this)" placeholder="Софт" list="softList' . $req->id . '">
                    <datalist id="softList' . $req->id . '">';
        foreach ($sofwares as $software) {
            $message .= '<option id="' . $software->id . '" value="' . $software->name . '"></option>';
        }
        $message .= '
        </datalist>
        <input class="form-control col" type="text" position="' . $req->id . '" name="version' . $req->id . '" id="version' . $req->id . '" onchange="addSoft(this)" placeholder="Версия" list="versionList' . $req->id . '">
        <datalist id="versionList' . $req->id . '">
        </datalist>
        </div>';
        //dd($message);
        return response()->json(['status' => 'success', 'soft' => $message]);
    }

    //Добавление ответственных
    function addResp(Request $req)
    {
        $users = UserModel::all();
        $message = '<div id="resp' . $req->id . '" class="mb-1">
                    <select class="form-control resps" onchange="addResp(this)" id="responsible' . $req->id . '" position="' . $req->id . '" name="responsible' . $req->id . '">
                    <option value="-1">---Выбрать---</option>';
        foreach ($users as $user) {
            //if (!in_array($user->id, $req->included))
            $message .= '<option class="options" position="' . $req->id . '" value="' . $user->id . '">' . $user->surname . ' ' . $user->name . '</option>';
        }
        $message .= '</select>
                     </div>';
        return response()->json(['status' => 'success', 'resp' => $message]);
    }

    //Обновление версий
    function loadVersions(Request $req)
    {
        $tmpSoftVers = SoftwaresVersionsModel::where('software', '=', $req->id)->pluck('version');
        $message = '';
        foreach ($tmpSoftVers as $tmpSoftVer) {
            $message .= '<option id="' . $tmpSoftVer . '" value="' . $tmpSoftVer . '"></option>';
        }
        //dd($message);
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    //Добавление оборудований
    function addNewEqs(Request $req)
    {
        $i = 0;
        $amount = $req->amount;
        $constIds = $req->allEqAmount;
        $currentEq = 0;
        $eqs = EquipmentModel::where('enabled', '=', '1')->get();
        $atts = AttributeModel::where('equipment', '=', $req->id)->get();
        $attVals = AttributeValuesModel::all();
        $eqsOptionMessage = '';
        $eqsMessage = '';
        $attsMessage = '';
        $attId = 0;
        foreach ($eqs as $eq) {
            if ($eq->id == $req->id)
                $eqsOptionMessage .= '<option selected value="' . $eq->id . '">' . $eq->name . '</option>';
            else
                $eqsOptionMessage .= '<option value="' . $eq->id . '">' . $eq->name . '</option>';
        }
        for ($i = 0; $i < $amount; $i++) {
            $currentEq = $constIds - $amount + $i;
            $eqsMessage .= '
            <div class="mb-1 d-flex align-items-center" id="eq' . $currentEq . '">
                <input class="btn btn-danger mr-1" type="button" position="' . $currentEq . '" style="font-size: 15px" id="deleteEq' . $currentEq . '" onclick="deleteEq(this)" value="' . $currentEq . '">
                    <select position="' . $currentEq . '" name="selectedEq' . $currentEq . '" id="selectedEq' . $currentEq . '" onchange="changeEq(this)" class="mr-1 rounded w-100">
                    <option value="-1">Пусто</option>
                    ' . $eqsOptionMessage . '
                    </select>
                <input type="number" name="amount' . $currentEq . '" id="amount' . $currentEq . '" min="1" max="50" class="mr-1 form-control" placeholder="Кол-во" value="1" style="width: 100px; font-size: 15px">
                <input class="btn btn-primary mr-1" type="button" position="' . $currentEq . '" eqId="' . $req->id . '" id="copyEq' . $currentEq . '" onclick="copyEq(this)" value="+">
                <input class="btn btn-primary" position="' . $currentEq . '" type="button" id="showAtts' . $currentEq . '" onclick="showAtts(this)" value=">">
            </div>
            ';
            $attsMessage .= '
                    <div name="panel" id="panel' . $currentEq . '" class="mb-3" style="display: none; overflow: auto; height: 50vh">';
            foreach ($atts as $att) {
                if ($att->equipment == $req->id) {
                    $attsMessage .= '
                        <label class="d-block">' . $att->name . '</label>
                        ';
                    if ($att->type == '2') {
                        $attsMessage .=
                            '<select class="w-100 rounded val' . $currentEq . '" name="attVal' . $currentEq . '.' . $att->id . '">';
                        foreach ($attVals as $attVal) {
                            if ($attVal->attribute == $att->id) {
                                if ($attVal->id == $req->values[$attId])
                                    $attsMessage .= '<option selected value="' . $attVal->id . '">' . $attVal->value . '</option>';
                                else
                                    $attsMessage .= '<option value="' . $attVal->id . '">' . $attVal->value . '</option>';
                            }
                        }
                        $attsMessage .= '</select>';
                    } else {
                        $attsMessage .= '<input class="w-100 form-control val' . $currentEq . '" type="text" name="attVal' . $currentEq . '.' . $att->id . '" placeholder="Значение" value="' . $req->values[$attId] . '"> ';
                    }
                    $attId++;
                    if ($attId >= count($atts))
                        $attId = 0;
                }
            }
            $attsMessage .= '</div>';
        }
        return response()->json(['status' => 'success', 'eqs' => $eqsMessage, 'atts' => $attsMessage]);
    }

    //При смене оборудования
    function changeEq(Request $req)
    {
        $currentEq = $req->position;
        $attsMessage = '';
        $atts = AttributeModel::where('equipment', '=', $req->id)->get();
        $attVals = AttributeValuesModel::all();
        $attsMessage .= '
                    <div name="panel" id="panel' . $currentEq . '" class="mb-3" style="overflow: auto; height: 50vh">';
        foreach ($atts as $att) {
            if ($att->equipment == $req->id) {
                $attsMessage .= '
                        <label class="d-block">' . $att->name . '</label>
                        ';
                if ($att->type == '2') {
                    $attsMessage .=
                        '<select class="w-100 rounded val' . $currentEq . '" name="attVal' . $currentEq . '.' . $att->id . '">';
                    foreach ($attVals as $attVal) {
                        if ($attVal->attribute == $att->id) {
                            $attsMessage .= '<option value="' . $attVal->id . '">' . $attVal->value . '</option>';
                        }
                    }
                    $attsMessage .= '</select>';
                } else {
                    $attsMessage .= '<input class="w-100 form-control val' . $currentEq . '" type="text" name="attVal' . $currentEq . '.' . $att->id . '" placeholder="Значение"> ';
                }
            }
        }
        $attsMessage .= '</div>';
        return response()->json(['status' => 'success', 'atts' => $attsMessage]);
    }

    //Сохранение лаборатории
    function saveLab(Request $req)
    {
        //dd($req->all());
        $lab = new LaboratoryModel;
        $type = new TypeModel;
        $subType = new SubTypeModel;
        $softVerPosition = []; //For soft_version to labs_soft ids
        $eqPos = []; //For equipments to attributes ids
        $labEqPos = []; //Position of lab_eq new element
        $i = 0;
        //Adding types and subtypes if there is no such
        $typeFound = TypeModel::where('name', $req->labType)->get();
        if ($typeFound->count() > 0) {
            $typeId = $typeFound[0]->id;
            if (SubTypeModel::where('name', $req->labSubType)->get()->count() == 0) {
                $subType->type = $typeId;
                $subType->name = $req->labSubType;
                $subType->save();
                $subTypeId = $subType->id;
            } else {
                $subTypeId = SubTypeModel::where('name', $req->labSubType)->pluck('id')[0];
            }
        } else {
            $type->name = $req->labType;
            $type->save();
            $typeId = $type->id;
            $subType->type = $typeId;
            $subType->name = $req->labSubType;
            $subType->save();
            $subTypeId = $subType->id;
        }
        //Updating or saving lab
        if (isset($req->update)) {
            LabSoftModel::where('laboratory', $req->id)->delete();
            LabEqModel::where('laboratory', $req->id)->delete();
            LabRespModel::where('laboratory', $req->id)->delete();
            $lab = LaboratoryModel::findOrFail($req->id);
            $lab->name = $req->labName;
            $lab->type = $typeId;
            $lab->sub_type = $subTypeId;
            $lab->descr = $req->labDesc;
            if ($req->image != null) {
                $imageName = time() . '.' . $req->image->extension();
                $req->image->move(public_path('img/labs/'), $imageName);
                if ($lab->image != null && file_exists(public_path('img/labs/') . $lab->image))
                    unlink(public_path('img/labs/') . $lab->image);
                $lab->image = $imageName;
            }
            $lab->update();
            $currentLab = $req->id;
        } else {
            $lab->name = $req->labName;
            $lab->type = $typeId;
            $lab->sub_type = $subTypeId;
            $lab->descr = $req->labDesc;
            if ($req->image != null) {
                $imageName = time() . '.' . $req->image->extension();
                $req->image->move(public_path('/img/labs'), $imageName);
                $lab->image = $imageName;
            }
            $lab->save();
            $currentLab = $lab->id;
        }
        foreach ($req->all() as $key => $value) {
            $tmpId = preg_replace('/[^0-9 _]/', '', $key);
            //Adding soft
            if (Str::contains($key, 'software')) {
                if ($value != null) {
                    $softVers = new SoftwaresVersionsModel;
                    $softValue = $value;
                    $softFind = SoftwareModel::where('name', $value)->pluck('id');
                    if ($softFind->count() == 0) {
                        $soft = new SoftwareModel;
                        $soft->name = $value;
                        $soft->push();
                        $softVers->software = $soft->id;
                    } else {
                        $softVers->software = SoftwareModel::where('name', $value)->pluck('id')[0];
                    }
                } else {
                    $softValue = null;
                    $softVerPosition[] = null;
                }
            }
            //Adding version
            if (Str::contains($key, 'version') && $softValue != null) {
                if ($value != null)
                    $softVers->version = $value;
                if (SoftwaresVersionsModel::where('software', $softVers->software)->where('version', $value)->get()->count() == 0) {
                    $softVers->push();
                    $softVerPosition[] = $softVers->id;
                } else {
                    $softVerPosition[] = SoftwaresVersionsModel::where('software', $softVers->software)->where('version', $value)->pluck('id')->all()[0];
                }
            }
            //Adding responsible
            if (Str::contains($key, 'responsible') && $value != -1) {
                $responsible = new LabRespModel;
                $responsible->laboratory = $currentLab;
                $responsible->responsible = $value;
                $responsible->push();
            }
            //Getting selected eq
            if (Str::contains($key, 'selectedEq')) {
                if ($value != -1) {
                    $eqId = $value;
                    $eqPos[] = $tmpId;
                } else {
                    $eqPos[] = null;
                    $amount = null;
                }
            }
            //Getting amount of eq
            if (Str::contains($key, 'amount')) {
                if ($eqPos[count($eqPos) - 1] != null) {
                    if ($value > 0)
                        $amount = $value;
                    else
                        $amount = 1;
                    $labEq = new LabEqModel;
                    $labEq->laboratory = $currentLab;
                    $labEq->equipment = $eqId;
                    $labEq->amount = $amount;
                    $labEq->push();
                    $labEqPos[] = $labEq->id;
                } else {
                    $labEqPos[] = null;
                }
            }
            //Getting attribute and value
            if (Str::contains($key, 'attVal')) {
                $tmpId = explode('_', $tmpId);
                for ($i = 0; $i < count($eqPos); $i++) {
                    if ($tmpId[0] == $eqPos[$i]) {
                        $labAtt = new LabAttsModel;
                        $labAtt->lab_equipment = $labEqPos[$i];
                        $labAtt->attribute = $tmpId[1];
                        $labAtt->value = $value;
                        $labAtt->push();
                        break;
                    }
                }
            }
        }
        for ($i = 0; $i < count($softVerPosition); $i++) {
            if ($softVerPosition[$i] != null) {
                $labSoft = new LabSoftModel;
                $labSoft->laboratory = $currentLab;
                $labSoft->soft_ver = $softVerPosition[$i];
                $labSoft->push();
            }
        }
        return redirect(route('home'));
    }

    function truncateAll()
    {
        DB::select("call truncate_all()");
    }
}
