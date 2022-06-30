@extends('layouts.app')
@section('title-block')
    Редактирование лабораторий
@endsection
@section('content')
    <style>
        .btn{
            padding: 4px .75rem;
        }
        select{
            padding: 3px 3px;
            font-size: 18px;
        }
    </style>
    <div>
        <form id="mainForm" onsubmit="return validateMainInfo();" action="{{route('saveLab')}}" method="post" enctype="multipart/form-data">
            @csrf
            <meta name="CSRF" content="{{csrf_token()}}">
            <!--Основная информация-->
            <div class="row mx-auto justify-content-center mb-3">
                <!--Основное-->
                <div class="col-auto mr-3" id="mainInfo">
                    @if($id!=-1)
                        <input type="hidden" name="update" value="1">
                        <input type="hidden" name="id" value={{$id}}>
                        <input class="d-block form-control mb-3 w-100" type="text" name="labName" id="labName" placeholder="Наименование" minlength="4" maxlength="20" value="{{$lab->name}}">
                        <input class="d-block form-control mb-3 w-100" type="text" name="labType" id="labType" onchange="loadSubTypes()" placeholder="Тип"
                               list="typeList" minlength="2" maxlength="70" value="{{$types->firstWhere('id', $lab->type)->name}}">
                        <datalist id="typeList">
                            @foreach($types as $type)
                                <option id="{{$type->id}}" value="{{$type->name}}"></option>
                            @endforeach
                        </datalist>
                        <input class="d-block form-control w-100" type="text" name="labSubType" id="labSubType" placeholder="Подтип"
                               list="subTypeList" minlength="2" maxlength="70" value="{{$subTypes->firstWhere('id', $lab->sub_type)->name}}">
                        <datalist id="subTypeList">
                            @foreach($subTypes as $subType)
                                <option id="{{$subType->id}}" value="{{$subType->name}}"></option>
                            @endforeach
                        </datalist>
                        <span class="alert-danger d-block text-left mt-1 w-100" style="width: fit-content" id="errorMessage"></span>
                        <textarea class="rounded w-100 d-block d-block mt-3" name="labDesc" id="labDesc" placeholder="Описание">{{$lab->descr}}</textarea>
                    @else
                        <input class="d-block form-control mb-3 w-100" type="text" name="labName" id="labName" placeholder="Наименование" minlength="4" maxlength="20" style="width: fit-content">
                        <input class="d-block form-control mb-3 w-100" type="text" name="labType" id="labType" onchange="loadSubTypes()" placeholder="Тип" list="typeList" minlength="2" maxlength="70" style="width: fit-content">
                        <datalist id="typeList">
                            @foreach($types as $type)
                                <option id="{{$type->id}}" value="{{$type->name}}"></option>
                            @endforeach
                        </datalist>
                        <input class="d-block form-control w-100" type="text" name="labSubType" id="labSubType" placeholder="Подтип" list="subTypeList" minlength="2" maxlength="70" style="width: fit-content">
                        <datalist id="subTypeList"></datalist>
                        <span class="alert-danger d-block text-left mt-1 w-100" style="width: fit-content" id="errorMessage"></span>
                        <textarea class="rounded w-100 d-block d-block mt-3" name="labDesc" id="labDesc" placeholder="Описание" style="width: fit-content"></textarea>
                    @endif
                </div>
                <!--Софт-->
                <div class="col-auto rounded mr-4" id="softwareInfo" style="border: solid 1px; padding: 0px 0px; max-height: 9em; overflow: auto">
                    @if($id!=-1)
                        <meta {{$position = -1}}>
                        @foreach($labSofts as $labSoft)
                            @if($labSoft->laboratory == $id)
                                @foreach($softVers as $softVer)
                                    @if($softVer->id == $labSoft->soft_ver)
                                        <div class="row col" id="softwareDiv{{++$position}}" style="margin-right: 0; margin-left: 0; padding: 0 0">
                                            <input class="form-control col soft" type="text" position="{{$position}}" name="software{{$position}}" id="software{{$position}}"
                                                   onchange="loadVersions(this)" placeholder="Софт" list="softList{{$position}}" value="{{$softwares->firstWhere('id', $softVer->software)->name}}">
                                            <datalist id="softList{{$position}}">
                                                @foreach($softwares as $software)
                                                    <option id="{{$software->id}}" value="{{$software->name}}"></option>
                                                @endforeach
                                            </datalist>
                                            <input class="form-control col" type="text" position="{{$position}}" name="version{{$position}}" id="version{{$position}}"
                                                   onchange="addSoft(this)" placeholder="Версия" list="versionList{{$position}}" value="{{$softVer->version}}">
                                            <datalist id="versionList{{$position}}">
                                                @foreach($softVers as $versionEl)
                                                    @if($versionEl->software == $softVer->software)
                                                        <option id="{{$versionEl->version}}" value="{{$versionEl->version}}"></option>
                                                    @endif
                                                @endforeach
                                            </datalist>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        <div class="row col" id="softwareDiv{{++$position}}" style="margin-right: 0; margin-left: 0; padding: 0 0">
                            <input class="form-control col soft" type="text" position="{{$position}}" name="software{{$position}}" id="software{{$position}}"
                                   onchange="loadVersions(this)" placeholder="Софт" list="softList{{$position}}">
                            <datalist id="softList{{$position}}">
                                @foreach($softwares as $software)
                                    <option id="{{$software->id}}" value="{{$software->name}}"></option>
                                @endforeach
                            </datalist>
                            <input class="form-control col" type="text" position="{{$position}}" name="version{{$position}}" id="version{{$position}}"
                                   onchange="addSoft(this)" placeholder="Версия" list="versionList{{$position}}">
                            <datalist id="versionList{{$position}}">
                            </datalist>
                        </div>
                    @else
                        <div class="row col" id="softwareDiv0" style="margin-right: 0; margin-left: 0; padding: 0 0">
                            <input class="form-control col soft" type="text" position="0" name="software0" id="software0" onchange="loadVersions(this)" placeholder="Софт" list="softList0">
                            <datalist id="softList0">
                                @foreach($softwares as $software)
                                    <option id="{{$software->id}}" value="{{$software->name}}"></option>
                                @endforeach
                            </datalist>
                            <input class="form-control col" type="text" position="0" name="version0" id="version0" onchange="addSoft(this)" placeholder="Версия" list="versionList0">
                            <datalist id="versionList0">
                            </datalist>
                        </div>
                    @endif
                </div>
                <div class="mb-3 mr-3" id="responsibleData" style="width: fit-content; border: solid 1px; padding: 0px 0px; max-height: 9em; overflow: auto">
                    <meta {{$position = -1}}>
                    <label for="resps">Ответственные</label>
                    <div id="resps">
                        @if($id!=-1)
                            @foreach($resps as $resp)
                                <div {{++$position}} id="resp{{$position}}" class="mb-1">
                                    <select class="form-control resps" onchange="addResp(this)" id="responsible{{$position}}" position="{{$position}}" name="responsible{{$position}}">
                                        <option value="-1">---Выбрать---</option>
                                        @foreach($users as $user)
                                            @if($user->id == $resp->responsible)
                                                <option class="options" position="{{$position}}" selected value="{{$user->id}}">{{$user->surname}} {{$user->name}}</option>
                                            @else
                                                <option class="options" position="{{$position}}" value="{{$user->id}}">{{$user->surname}} {{$user->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                            <div {{++$position}} id="resp{{$position}}" class="mb-1">
                                <select class="form-control resps" onchange="addResp(this)" id="responsible{{$position}}" position="{{$position}}" name="responsible{{$position}}">
                                    <option value="-1">---Выбрать---</option>
                                    @foreach($users as $user)
                                        <option class="options" position="{{$position}}" value="{{$user->id}}">{{$user->surname}} {{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div id="resp0" class="mb-1">
                                <select class="form-control resps" onchange="addResp(this)" id="responsible0" position="0" name="responsible0">
                                    <option value="-1">---Выбрать---</option>
                                    @foreach($users as $user)
                                        <option class="options" position="0" value="{{$user->id}}">{{$user->surname}} {{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    @if($id!=-1)
                        <a onclick="document.getElementById('image-input').click();"><img style="width: 20em; height: 13em" id="image" src="{{'/img/labs/'.$lab->image}}" alt="Фотография лаборатории"></a>
                    @else
                        <a onclick="document.getElementById('image-input').click();"><img style="width: 20em; height: 13em" id="image" src="#" alt="Фотография лаборатории"></a>
                    @endif
                    <input id="image-input" onchange="loadImage(this)" type="file" name="image" style="display: none;" accept="image/png, image/gif, image/jpeg">
                </div>
            </div>
            <!--Оборудования и атрибуты-->
            <div class="row mx-auto" style="width: fit-content">
                <!--Оборудование-->
                <div id="eqs" class="col-auto border rounded">
                    <!--Создание множества-->
                    <div id="eq0" class="mb-1 mt-2 d-flex align-items-center eqs">
                        <select position="0" name="selectedEq0" id="selectedEq0" onchange="changeEq(this)" class="mr-1 rounded w-100">
                            <option value="-1">
                                Пусто
                            </option>
                            @foreach($eqs as $eq)
                                @if($eq->enabled == '1')
                                    <option value="{{$eq->id}}">
                                        {{$eq->name}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <input type="number" name="amount0" id="amount0" min="1" value="1" class="mr-1 form-control" placeholder="Кол-во" style="width: 100px; font-size: 15px">
                        <input class="btn btn-primary mr-1" type="button" position="0" onclick="copyEq(this)" value="+">
                        <input class="btn btn-primary" position="0" type="button" id="showAtts0" onclick="showAtts(this)" value=">">
                    </div>
                    <!--Новосозданные + редактирование-->
                    <div class="d-block" style="overflow: auto; height: 45vh">
                        @if($id != -1)
                            <meta {{$position = 0}} {{$currentEq = -1}}>
                            @foreach($labEqs as $labEq)
                                <div class="mb-1 d-flex align-items-center eqs" id="eq{{++$position}}">
                                    <input class="btn btn-danger mr-1" type="button" position="{{$position}}" style="font-size: 15px" id="deleteEq{{$position}}" onclick="deleteEq(this)" value="{{$position}}">
                                    <select position="{{$position}}" name="selectedEq{{$position}}" id="selectedEq{{$position}}" onchange="changeEq(this)" class="mr-1 rounded w-100">
                                        <option value="-1">Пусто</option>
                                        @foreach($eqs as $eq)
                                            @if($eq->id == $labEq->equipment)
                                                <option {{$currentEq = $eq->id}} selected value="{{$eq->id}}">{{$eq->name}}</option>
                                            @else
                                                <option value="{{$eq->id}}">{{$eq->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <input type="number" name="amount{{$position}}" id="amount{{$position}}" min="1" max="50" class="mr-1 form-control" placeholder="Кол-во" value="{{$labEq->amount}}" style="width: 100px; font-size: 15px">
                                    <input class="btn btn-primary mr-1" type="button" position="{{$position}}" eqId="{{$currentEq}}" id="copyEq{{$position}}" onclick="copyEq(this)" value="+">
                                    <input class="btn btn-primary" position="{{$position}}" type="button" id="showAtts{{$position}}" onclick="showAtts(this)" value=">">
                                </div>
                            @endforeach
                        @endif
                        <div id="insertHere"></div>
                        <!--Редактирование-->
                    </div>
                </div>
                <!--Атрибуты-->
                <div class="border col rounded" style="min-width: 30vh; ">
                    <div class="w-100 text-center"><h3 id="attPanelsName"></h3></div>
                    @if($id != -1)
                        <meta {{$position = 0}} {{$currentLabEq = -1}}>
                        <div>
                            @foreach($labEqs as $labEq)
                                @foreach($labAtts as $labAtt)
                                    @if($labAtt->lab_equipment==$labEq->id)
                                        @if($currentLabEq != $labEq->id)
                        </div>
                        <div name="panel" {{$currentLabEq=$labEq->id}} id="panel{{++$position}}" class="mb-3" style="display: none; overflow: auto; height: 50vh">
                            @endif
                            <label class="d-block">{{$atts->firstWhere('id', $labAtt->attribute)->name}}</label>
                            @if($atts->firstWhere('id', $labAtt->attribute)->type == 2)
                                <select class="w-100 rounded val{{$position}}" name="attVal{{$position}}.{{$labAtt->attribute}}">
                                    @foreach($attVals as $attVal)
                                        @if($attVal->attribute == $labAtt->attribute)
                                            @if($attVal->id == $labAtt->value)
                                                <option selected value="{{$attVal->id}}">{{$attVal->value}}</option>
                                            @else
                                                <option  value="{{$attVal->id}}">{{$attVal->value}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            @else
                                <input class="w-100 form-control val{{$position}}" type="text" name="attVal{{$position}}.{{$labAtt->attribute}}" placeholder="Значение" value="{{$labAtt->value}}">
                            @endif
                            @endif
                            @endforeach
                            @endforeach
                        </div>
                    @endif
                    <div id="insertAttsHere"></div>
                </div>
                <!--Разделитель и кнопка сохранения-->
                <div class="w-100"></div>
                <div class="text-right w-100">
                    <input class="btn btn-primary w-25" type="submit" value="Готово">
                </div>
            </div>
        </form>
    </div>
    @include('incs.labsScripts')
@endsection
