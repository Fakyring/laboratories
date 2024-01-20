@extends('layouts.app')
@section('title-block')
    Создание оборудования
@endsection
@section('content')
    <input type="hidden" {{$lastAttId=0}} {{$lastValId=0}}>
    <form id="attForm" onsubmit="return validation();" action="{{route('createUpdateEqSubmit')}}" method="post">
        @csrf
        <div class="mx-auto" style="width: fit-content">
            <label class="d-block text-center" for="eqName">Новое оборудование</label>
            <input class="w-100 form-control rounded ml-2" id="eqName" name="eqName" type="text" placeholder="Наименование" required min="2" max="100">
            <div class="w-25 mx-auto" style="white-space: pre-wrap; color: red" id="errorName"></div>
        </div>
        <div class="row mx-auto mt-3" style="width: fit-content">
            <div style="overflow: auto; max-height: 70vh; padding: 0 0">
                <div tag="0" class="w-100 row mx-auto border-bottom" style="height: 8em">
                    <div class="border rounded" style="width: fit-content">
                        <label class="d-block mt-1" for="att0">Атрибут</label>
                        <input class="form-control rounded atts d-inline-block" style="width: fit-content" type="text" name="att0" tag="0" placeholder="Атрибут" onfocusout="createAtt(this)" id="att0">
                        <select class="combobox rounded mt-1 d-inline-block" onchange="changeType(this)" tag="0" name="type0" id="type0" style="padding: 2% 0%; width: fit-content">
                            <option value="1">Текст</option>
                            <option value="2">Лист</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-auto mt-2" style="width: fit-content">
            <input type="submit" class="btn btn-primary mx-auto" id="done" value="Готово" style="font-size: 20px; width: fit-content">
        </div>
    </form>
@endsection
@include('incs.eqScripts')
