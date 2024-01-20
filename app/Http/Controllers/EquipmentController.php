<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Models\AttributeValuesModel;
use App\Models\AttributeModel;
use Illuminate\Http\Request;
use App\Models\EquipmentModel;
use Illuminate\Support\Str;

class EquipmentController extends Controller
{
    //API
    /**
     * Возвращает всё оборудование
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function allEq()
    {
        $eqs = EquipmentModel::all();
        foreach ($eqs as $eq) {
            if ($eq->enabled == 1)
                $eq->enabled = true;
            else
                $eq->enabled = false;
        }
        return response()->json($eqs, 200);
    }

    /**
     * Возвращает атрибуты, которые находятся в оборудовании по id оборудования
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getAtts($id)
    {
        $atts = AttributeModel::where('equipment', $id)->get()->all();
        foreach ($atts as $att) {
            if ($att->type == 1)
                $att->type = false;
            else
                $att->type = true;
        }
        return response()->json($atts, 200);
    }

    /**
     * Возвращает все атрибуты
     * @return \Illuminate\Http\JsonResponse
     */
    function allAtts()
    {
        $atts = AttributeModel::all();
        foreach ($atts as $att) {
            if ($att->type == 1)
                $att->type = false;
            else
                $att->type = true;
        }
        return response()->json($atts, 200);
    }

    /**
     * Вывод всех значений атрибутов
     * @return \Illuminate\Http\JsonResponse
     */
    function allAttVals(){
        $attVals = AttributeValuesModel::all();
        return response()->json($attVals, 200);
    }

    /**
     * Возвращает значения листового атрибута по его id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getAttVals($id)
    {
        $attVals = AttributeValuesModel::where('attribute', $id)->get()->all();
        if (count($attVals) == 0)
            $attVals = false;
        return response()->json($attVals, 200);
    }

    /**
     * Возвращает оборудование по его id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function eqById($id)
    {
        $eq = EquipmentModel::find($id);
        if ($eq->enabled == 1)
            $eq->enabled = true;
        else
            $eq->enabled = false;
        return response()->json($eq, 200);
    }

    /**
     * Добавляет оборудование
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addEq(Request $request)
    {
        $eq = new EquipmentModel;
        $eq->name = $request->name;
        $eq->enabled = 1;
        $eq->save();
        return response()->json(['id' => $eq->id], 200);
    }

    /**
     * Добавляет атрибуты к оборудованию
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addAtt(Request $request)
    {
        $att = new AttributeModel;
        $att->equipment = $request->equipment;
        $att->name = $request->name;
        if ($request->type == 0)
            $att->type = 1;
        else
            $att->type = 2;
        $att->save();
        return response()->json(['id' => $att->id], 200);
    }

    /**
     * Добавляет значения в листовые атрибуты
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addAttVal(Request $request)
    {
        $attVal = new AttributeValuesModel;
        $attVal->attribute = $request->attribute;
        $attVal->value = $request->value;
        $attVal->save();
        return response()->json(['id' => $attVal->id], 200);
    }

    /**
     * Изменяет оборудование по его идентификатору
     * @param Request $request
     * @param EquipmentModel $eq
     * @return \Illuminate\Http\JsonResponse
     */
    function editEq(Request $request)
    {
        $eq = new EquipmentModel;
        $eq = EquipmentModel::find($request->id);
        $eq->update($request->all());
    }

    /**
     * Изменение атрибута по его идентификатору
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function editAtt(Request $request)
    {
        $att = new AttributeModel;
        $att = AttributeModel::find($request->id);
        if (isset($request->type)) {
            if ($request->type == 0)
                $request->merge(['type' => 1]);
            else
                $request->merge(['type' => 2]);
        }
        $att->update($request->all());
    }

    function editAttVal(Request $request)
    {
        $attVal = new AttributeValuesModel;
        $attVal = AttributeValuesModel::find($request->id);
        $attVal->update($request->all());
    }

    /**
     * Удаляет оборудование по его идентификатору
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    function deleteEq($id, $status)
    {
        EquipmentModel::find($id)->update(array('enabled' => $status));
        return response()->json('', 200);
    }

    /**
     * Удаляет атрибут
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function deleteAtt($id)
    {
        $att = new AttributeModel;
        $att = AttributeModel::find($id)->delete();
        return response()->json('', 200);
    }

    /**
     * Удаляет значение листового атрибута
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function deleteAttVal($id)
    {
        AttributeValuesModel::find($id)->delete();
        return response()->json('', 200);
    }

    //Web
    function getEqs()
    {
        return view('/equipments/index', ['data' => EquipmentModel::all(), 'attributes' => AttributeModel::all()]);
    }

    function deleteWebEq($id)
    {
        $eq = EquipmentModel::find($id);
        $eq->enabled = '0';
        $eq->save();
        return $this->getEqs();
    }

    function updateEq($id)
    {
        $eq = EquipmentModel::find($id);
        $atts = AttributeModel::where('equipment', '=', $id)->get();
        $attsVal = collect();
        $tmpAttVal = new AttributeValuesModel;
        for ($i = 0; $i < count($atts); $i++) {
            $tmpAttVal = AttributeValuesModel::where('attribute', '=', $atts[$i]->id)->get();
            $attsVal->push($tmpAttVal);
        }
        $attsVal = $attsVal->toArray();
        return view('equipments/updateEq', ['eq' => $eq, 'atts' => $atts, 'attsVal' => $attsVal]);
    }

    function createEq()
    {
        return view('equipments/createEq');
    }

    function createUpdateEqSubmit(EquipmentRequest $req)
    {
        $eq = new EquipmentModel;
        if (isset($req->update)) {
            $eq = EquipmentModel::findOrFail($req->id);
            $eq->name = $req->eqName;
            $eq->update();
        } else {
            $eq->name = $req->eqName;
            $eq->save();
        }
        $eqId = $eq->id;
        $att = new AttributeModel;
        $newAttId = -1;
        $checkAtt = AttributeModel::where('equipment', '=', $eqId)->get();
        $checkAttVals = new AttributeValuesModel;
        $updateAtts = $req->all();
        $updateAttVals = $req->all();
        $updateAtts = array_filter(
            $updateAtts,
            function ($key) {
                return Str::contains($key, 'updatt');
            },
            ARRAY_FILTER_USE_KEY
        );
        $updateAttVals = array_filter(
            $updateAttVals,
            function ($key) {
                return Str::contains($key, 'updval');
            },
            ARRAY_FILTER_USE_KEY
        );
        $tmpArr = [];
        if (isset($req->update)) {
            foreach ($updateAtts as $key => $value) {
                $value = preg_replace('/[^0-9 _]/', '', $key);
                $updateAtts[$key] = $value;
            }
            foreach ($updateAttVals as $key => $value) {
                $value = preg_replace('/[^0-9 _]/', '', $key);
                $value = explode('_', $value);
                $updateAttVals[$key] = $value[1];
            }
            foreach ($checkAtt as $attribute) {
                if (!in_array($attribute->id, $updateAtts)) {
                    AttributeModel::find($attribute->id)->delete();
                } else {
                    $checkAttVals = AttributeValuesModel::where('attribute', '=', $attribute->id)->get();
                    foreach ($checkAttVals as $checkAttVal)
                        if (!in_array($checkAttVal->id, $updateAttVals)) {
                            AttributeValuesModel::find($checkAttVal->id)->delete();
                        }
                }
            }
        }
        $data = $req->all();
        $tmpId = "";
        $type = 1;
        foreach ($data as $key => $value) {
            if (Str::contains($key, 'upd')) {
                $tmpId = preg_replace('/[^0-9 _]/', '', $key);
                if (Str::contains($key, 'att')) {
                    $att = new AttributeModel;
                    $att = AttributeModel::findOrFail($tmpId);
                    $att->name = $value;
                    $newAttId = $att->id;
                }
                if (Str::contains($key, 'type') && ($att->name != null || $att->name != "")) {
                    $att->type = $value;
                    $att->update();
                }
                if (Str::contains($key, 'val') && $value != null) {
                    $attVal = new AttributeValuesModel;
                    $tmpId = explode('_', $tmpId);
                    $attVal = AttributeValuesModel::find($tmpId[1]);
                    $attVal->value = $value;
                    $attVal->update();
                }
            } else {
                if (Str::contains($key, 'att')) {
                    $att = new AttributeModel;
                    $newAttId = -1;
                    $att->name = $value;
                    $att->equipment = $eqId;
                }
                if (Str::contains($key, 'type') && ($att->name != null || $att->name != "")) {
                    $att->type = $value;
                    if ($value == 1) {
                        $att->push();
                        $type = 1;
                    } else
                        $type = 2;
                }
                if (Str::contains($key, 'val')) {
                    if ($type == 2) {
                        if ($value != null) {
                            $att->type = $type;
                        } else {
                            $att->type = 1;
                        }
                        $att->push();
						$newAttId = $att->id;
                        $type = -1;
                    }
                    if ($value != null) {
                        $attVal = new AttributeValuesModel;
                        $attVal->attribute = $newAttId;
                        $attVal->value = $value;
                        $attVal->push();
                    }
                }
            }
        }
        return redirect(route('equipments'));
    }
}
