<?php

namespace App\Http\Controllers;

use App\Models\AttributeModel;
use App\Models\AttributeValuesModel;
use App\Models\EquipmentModel;
use App\Models\KeysModel;
use App\Models\KeysPassportsModel;
use App\Models\LabAttsModel;
use App\Models\LabEqModel;
use App\Models\LaboratoryModel;
use App\Models\LabRespModel;
use App\Models\LabSoftModel;
use App\Models\PassportModel;
use App\Models\SoftwareModel;
use App\Models\SoftwaresVersionsModel;
use App\Models\SubTypeModel;
use App\Models\TypeModel;
use App\Models\UserModel;
use Faker\Core\Number;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Str;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\TemplateProcessor;

class PassportController extends Controller
{
    //Api
    /**
     * Возвращает все паспорта
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllPassports()
    {
        $passports = PassportModel::all();
        foreach ($passports as $passport) {
            if ($passport->enabled == 1)
                $passport->enabled = true;
            else
                $passport->enabled = false;
        }
        return response()->json($passports, 200);
    }

    /**
     * Возвращает паспорт по его id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getPassportById($id)
    {
        $passport = PassportModel::find($id);
        if ($passport->enabled == 1)
            $passport->enabled = true;
        else
            $passport->enabled = false;
        return response()->json($passport, 200);;
    }

    /**
     * Возвращает все ключи
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllKeys()
    {
        $keys = KeysModel::all();
        return response()->json($keys, 200);
    }

    /**
     * Возвращает все маски паспорта
     * @param $id
     */
    function getAllMasks($id)
    {
        $masks = KeysPassportsModel::select('id', 'key', 'mask')->where('passport', $id)->get();
        return response()->json($masks, 200);
    }

    /**
     * Добавляет паспорт
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addPassport(Request $request)
    {
        $passport = new PassportModel;
        $passport->name = $request->name;
        $passport->creator = $request->creator;
        $message = '';
        if (isset($_FILES['file']['name'])) {
            $file_name = time() . basename($_FILES['file']['name']);
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if ($extension == 'doc' || $extension == 'docx') {
                $file = public_path('/files/') . $file_name;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                    $message = $file_name;
                    $passport->file = $file_name;
                } else {
                    $message = 'Что-то пошло не так';
                }
            } else {
                $message = 'Принимаются только файлы формата .doc, .docx';
            }
        } else {
            $message = 'Используйте метод post, пожалуйста';
        }
        $passport->save();
        return response()->json(['id' => $passport->id], 200);
    }

    /**
     * Добавляет маску в паспорт
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addMask(Request $request)
    {
        $mask = new KeysPassportsModel;
        $mask->key = $request->key;
        $mask->mask = $request->mask;
        $mask->passport = $request->passport;
        $mask->save();
        return response()->json(['id' => $mask->id], 200);
    }

    /**
     * Изменяет паспорт
     * @param Request $request
     */
    function editPassport(Request $request)
    {
        $passport = new PassportModel;
        $passport = PassportModel::find($request->id);
        $passport->update($request);
    }

    /**
     * Изменяет файл в паспорте
     * @param Request $request
     * @return string
     */
    function editFile(Request $request)
    {
        $passport = new PassportModel;
        $passport = PassportModel::find($request->id);
        $message = '';
        if (isset($_FILES['file']['name'])) {
            $file_name = time() . basename($_FILES['file']['name']);
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if ($extension == 'doc' || $extension == 'docx') {
                $file = public_path('files/') . $file_name;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                    $message = $file_name;
                    if ($passport->file != '')
                        unlink(public_path('files/') . $passport->file);
                    $passport->file = $file_name;
                } else {
                    $message = 'Что-то пошло не так';
                }
            } else {
                $message = 'Принимаются только файлы формата .doc, .docx';
            }
        } else {
            $message = 'Используйте метод post, пожалуйста';
        }
        $passport->update();
        return response()->json(['message' => $message], 200);
    }

    /**
     * Изменение маски
     * @param Request $request
     */
    function editMask(Request $request)
    {
        $mask = new KeysPassportsModel;
        $mask = KeysPassportsModel::find($request->id);
        $mask->update(['mask' => $request->mask]);
    }

    /**
     * Удаление паспорта
     * @param $id
     * @param $status
     */
    function delPassport($id, $status)
    {
        $passport = PassportModel::find($id);
        $passport->update(['enabled' => $status]);
    }

    /**
     * Удаление маски
     * @param $id
     */
    function delMask($id)
    {
        $mask = KeysPassportsModel::find($id);
        $mask->delete();
    }

    //Web
    function getPassports()
    {
        $passports = PassportModel::all();
        $users = UserModel::all();
        $labs = LaboratoryModel::where('enabled', 1)->get()->all();
        return view('/passports/index', ['data' => $passports, 'users' => $users, 'labs' => $labs]);
    }

    function createPassport()
    {
        return $this->changeCreatePassport(-1);
    }

    function changeCreatePassport($id)
    {
        $keys = KeysModel::all();
        $files = scandir(public_path('files'));
        unset($files[0]);
        unset($files[1]);
        if ($id != -1) {
            $passport = PassportModel::find($id);
            $keysMasks = KeysPassportsModel::where('passport', $id)->get()->all();
            return view('/passports/createChangePassport', ['id' => $id, 'keys' => $keys, 'files' => $files, 'passport' => $passport, 'masks' => $keysMasks]);
        } else {
            return view('/passports/createChangePassport', ['id' => $id, 'keys' => $keys, 'files' => $files]);
        }
    }

    function deletePassport($id)
    {
        $passport = PassportModel::find($id);
        $passport->enabled = '0';
        $passport->save();
        return redirect(route('passports'));
    }

    function savePassport(Request $req)
    {
        if (isset($req->id)) {
            $keysMasks = KeysPassportsModel::where('passport', $req->id);
            $passport = PassportModel::find($req->id);
        } else {
            $passport = new PassportModel;
        }
        $passport->name = $req->namePassport;
        if ($req->choose == 'local') {
            $fileName = $req->file->getClientOriginalName();
            $req->file->move(public_path('/files'), $fileName);
            $passport->file = $fileName;
        } else {
            $passport->file = $req->fileList;
        }
        if (isset($req->id)) {
            $passport->update();
            $passportId = $req->id;
        } else {
            $passport->creator = auth()->user()->id;
            $passport->save();
            $passportId = $passport->id;
        }
        foreach ($req->all() as $key => $value) {
            $keyId = preg_replace('/[^0-9 _]/', '', $key);
            if (Str::contains($key, 'value')) {
                if (isset($req->id) && KeysPassportsModel::where('key', $keyId)->where('passport', $passportId)->get()->all() != null) {
                    $keyMask = KeysPassportsModel::where('key', $keyId)->where('passport', $passportId)->get()->all()[0];
                    if ($value === null)
                        $keyMask->delete();
                    else {
                        if (mb_strlen($value) > 20)
                            $value = mb_substr($value, 0, 20);
                        $keyMask->mask = $value;
                        $keyMask->update();
                    }
                } else if ($value != null) {
                    $keyMask = new KeysPassportsModel;
                    $keyMask->key = $keyId;
                    if (mb_strlen($value) > 20)
                        $value = mb_substr($value, 0, 20);
                    $keyMask->mask = $value;
                    $keyMask->passport = $passportId;
                    $keyMask->push();
                }
            }
        }
        return redirect(route('passports'));
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    function downloadFile(Request $req)
    {
        $files = glob(public_path('files/tmp') . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
        $passport = PassportModel::find($req->id);
        $masks = KeysPassportsModel::where('passport', $req->id)->get()->all();
        $lab = LaboratoryModel::find($req->lab);
        $file = $passport->file;
        $downloadFile = public_path('/files/') . "tmp/" . $file;
        $phpword = new TemplateProcessor(public_path('/files/') . $file);
        print_r($masks);
        foreach ($masks as $mask) {
            switch ($mask->key) {
                //Name
                case 1:
                {
                    $phpword->setValue($mask->mask, $lab->name);
                }
                //Type
                case 2:
                {
                    $type = TypeModel::find($lab->type);
                    $phpword->setValue($mask->mask, $type->name);
                }
                //SubType
                case 3:
                {
                    $subType = SubTypeModel::find($lab->sub_type);
                    $phpword->setValue($mask->mask, $subType->name);
                }
                //Responsible
                case 4:
                {
                    $responsible = LabRespModel::where('laboratory', $lab->id)->get()->all();
                    $message = '';
                    foreach ($responsible as $resp) {
                        $user = UserModel::find($resp->responsible);
                        $message .= $user->surname . ' ';
                        $message .= $user->name . ' ';
                        $message .= $user->patronymic;
                        $message .= "<w:br/>";
                    }
                    $phpword->setValue($mask->mask, $message);
                }
                //Desc
                case 5:
                {
                    $phpword->setValue($mask->mask, $lab->descr);
                }
                //Eq
                case 6:
                {
                    $labEqs = LabEqModel::where('laboratory', $lab->id)->get()->all();
                    $needEqs = [];
                    $goneEq = [];
                    foreach ($labEqs as $labEq) {
                        if (!in_array($labEq->equipment, $goneEq)) {
                            $currentAmount = 0;
                            foreach ($labEqs as $counterEq) {
                                if ($counterEq->equipment == $labEq->equipment) {
                                    $currentAmount = $currentAmount + LabEqModel::where('laboratory', $lab->id)->where('equipment', $counterEq->equipment)->pluck('amount')[0];
                                }
                            }
                            $eqCount = $currentAmount;
                            $goneEq[] = $labEq->equipment;
                            $needEqs[] = ['id' => $labEq->equipment, 'amount' => $eqCount];
                        }
                    }
                    $table = new Table(['unit' => TblWidth::PERCENT, 'borderSize' => 5, 'borderColor' => '000000']);
                    $tableWidth = 100;
                    $firstCell = 5;
                    $secondCell = 45;
                    $thirdCell = 100 - $firstCell - $secondCell;
                    $table->setWidth(100 * $tableWidth);
                    $table->addRow();
                    $table->addCell($firstCell * $tableWidth)->addText('№', ['bold' => true], ['align' => 'center']);
                    $table->addCell($secondCell * $tableWidth)->addText('Оборудование', ['bold' => true], ['align' => 'center']);
                    $table->addCell($thirdCell * $tableWidth)->addText('Количество', ['bold' => true], ['align' => 'center']);
                    $count = 0;
                    foreach ($needEqs as $needEq) {
                        $count++;
                        $table->addRow();
                        $eq = EquipmentModel::find($needEq['id']);
                        $table->addCell($firstCell * $tableWidth)->addText($count, ['align' => 'center']);
                        $table->addCell($secondCell * $tableWidth)->addText($eq->name);
                        $table->addCell($thirdCell * $tableWidth)->addText($needEq['amount'] . ' шт.', null, ['align' => 'center']);
                    }
                    $phpword->setComplexBlock($mask->mask, $table);
                }
                //Soft
                case 7:
                {
                    $softwares = LabSoftModel::where('laboratory', $lab->id)->pluck('soft_ver');
                    $allSofts = [];
                    foreach ($softwares as $software) {
                        $softVers = SoftwaresVersionsModel::find($software);
                        $version = $softVers->version;
                        $allSofts[] = ['soft' => SoftwareModel::find($softVers->software)->name, 'version' => $version];
                    }
                    $table = new Table(['unit' => TblWidth::PERCENT, 'borderSize' => 5, 'borderColor' => '000000']);
                    $tableWidth = 100;
                    $firstCell = 5;
                    $secondCell = 45;
                    $thirdCell = 100 - $firstCell - $secondCell;
                    $table->setWidth(100 * $tableWidth);
                    $table->addRow();
                    $table->addCell($firstCell * $tableWidth)->addText('№', ['bold' => true], ['align' => 'center']);
                    $table->addCell($secondCell * $tableWidth)->addText('Программное обеспечение', ['bold' => true], ['align' => 'center']);
                    $table->addCell($thirdCell * $tableWidth)->addText('Версия', ['bold' => true], ['align' => 'center']);
                    $count = 0;
                    foreach ($allSofts as $soft) {
                        $count++;
                        $table->addRow();
                        $table->addCell($firstCell * $tableWidth)->addText($count, ['align' => 'center']);
                        $table->addCell($secondCell * $tableWidth)->addText($soft['soft']);
                        $table->addCell($thirdCell * $tableWidth)->addText($soft['version'], null, ['align' => 'left']);
                    }
                    $phpword->setComplexBlock($mask->mask, $table);
                }
                //Image
                case 8:
                {
                    $phpword->setImageValue($mask->mask, ['path' => public_path('img/labs/') . $lab->image, 'width' => 500, 'height' => 250, 'ratio' => false]);
                }
                //All eq description
                case 9:
                {
                    $labEqs = LabEqModel::where('laboratory', $lab->id)->orderBy('equipment', 'asc')->get()->all();
                    $tableWidth = 100;
                    $firstCell = 5;
                    $secondCell = 30;
                    $thirdCell = 100 - $firstCell - $secondCell;
                    $count = 0;
                    $attCount = 0;
                    $prevId = 0;
                    $table = new Table(['unit' => TblWidth::PERCENT, 'borderSize' => 5, 'borderColor' => '000000']);
                    foreach ($labEqs as $labEq) {
                        for ($i = 0; $i < $labEq->amount; $i++) {
                            if ($prevId != $labEq->equipment) {
                                $count = 0;
                                $prevId = $labEq->equipment;
                                $atts = AttributeModel::where('equipment', $labEq->equipment)->get()->all();
                                $attCount = 0;
                            }
                            $count++;
                            $table->setWidth(100 * $tableWidth);
                            if ($count != 1) {
                                $table->addRow();
                                $table->addCell(null, ['gridSpan' => 3])->addText('');
                            }
                            $table->addRow();
                            $table->addCell(null, ['gridSpan' => 3])->addText(EquipmentModel::find($labEq->equipment)->name . ' ' . $count, ['bold' => true], ['align' => 'left']);
                            $table->addRow();
                            $table->addCell($firstCell * $tableWidth)->addText('№', ['bold' => true], ['align' => 'center']);
                            $table->addCell($secondCell * $tableWidth)->addText('Атрибут', ['bold' => true], ['align' => 'center']);
                            $table->addCell($thirdCell * $tableWidth)->addText('Значение', ['bold' => true], ['align' => 'center']);
                            //Atts + attvals
                            foreach ($atts as $att) {
                                $attCount++;
                                $table->addRow();
                                $table->addCell($firstCell * $tableWidth)->addText($attCount, null, ['align' => 'center']);
                                $table->addCell($secondCell * $tableWidth)->addText($att->name, null, ['align' => 'left']);
                                $attVal = LabAttsModel::where('lab_equipment', $labEq->id)->where('attribute', $att->id)->get()[0];
                                if ($attVal == '[]')
                                    $table->addCell($thirdCell * $tableWidth)->addText('', null, ['align' => 'left']);
                                else if ($att->type == 1) {
                                    $table->addCell($thirdCell * $tableWidth)->addText($attVal->value, null, ['align' => 'left']);
                                } else {
                                    $listAttVal = AttributeValuesModel::find($attVal->value);
                                    $table->addCell($thirdCell * $tableWidth)->addText($listAttVal->value, null, ['align' => 'left']);
                                }
                            }
                        }
                    }
                    $phpword->setComplexBlock($mask->mask, $table);
                }
            }
        }
        $phpword->saveAs($downloadFile);
        return response()->download($downloadFile);
    }
}
