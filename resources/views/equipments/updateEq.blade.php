@extends('layouts.app')
@section('title-block')
    Изменение оборудования
@endsection
@section('content')
    <input type="hidden" {{$lastAttId=0}} {{$lastValId=0}}>
    <form id="attForm" onsubmit="return validation();" action="{{route('createUpdateEqSubmit')}}" method="post">
        <input type="hidden" name="update" value="1"><input type="hidden" name="id" value="{{$eq->id}}">
        @csrf
        <div class="mx-auto" style="width: fit-content">
            <label class="d-block text-center" for="eqName">Оборудование: {{$eq->name}}</label>
            <input class="w-100 form-control rounded" id="eqName" name="eqName" type="text" placeholder="Наименование" required min="2" max="100" value="{{$eq->name}}">
            <span class="w-25 mx-auto" style="white-space: pre-wrap; color: red" id="errorName"></span>
        </div>
        <div class="row mx-auto mt-3" style="width: fit-content">
            <div style="overflow: auto; max-height: 70vh; padding: 0 0">
                @foreach($atts as $att)
                    <div tag="{{$att->id}}" class="w-100 row mx-auto mt-2 border-bottom" style="height: 8em">
                        <div class="d-block" class="border rounded" style="width: fit-content">
                            <label class="d-block mt-1" for="att{{$att->id}}">Атрибут</label>
                            <input type="text" class="form-control rounded atts d-inline-block" style="width: fit-content" name="updatt{{$att->id}}" tag="{{$att->id}}" placeholder="Атрибут" onfocusout="createAtt(this)" id="att{{$att->id}}" max="100" value="{{$att->name}}">
                            <select class="combobox rounded mt-1 d-inline-block" onchange="changeType(this)" tag="{{$att->id}}" name="updtype{{$att->id}}" id="type{{$att->id}}" style="padding: 2% 0%; width: fit-content">
                                <option value="1">Текст</option>
                                @if($att->type == '2')
                                    <option value="2" selected>Лист</option>
                                @else
                                    <option value="2" >Лист</option>
                                @endif
                            </select>
                        </div>
                        @if($att->type == '2')
                            <div id="attributes{{$att->id}}" class="rounded border" style="overflow: auto; width: fit-content; max-height: 8em">
                                @foreach($attsVal as $attVal)
                                    @foreach($attVal as $val)
                                        @if($val['attribute'] == $att->id)
                                            <input class="form-control rounded d-block w-100 val{{$att->id}}" attval="{{$val["id"]}}" att="{{$att->id}}" name="updval{{$att->id}}.{{$val["id"]}}" type="text" id="{{$val["id"]}}" onfocusout="createAttVal(this)" max="50" placeholder="Значение" value="{{$val["value"]}}">
                                        @endif
                                    @endforeach
                                @endforeach
                                <input class="form-control rounded d-block w-100 val{{$att->id}}" attval="{{$val["id"]+1}}" att="{{$att->id}}" name="val{{$att->id}}.{{$val["id"]+1}}" type="text" id="{{$val["id"]+1}}" onfocusout="createAttVal(this)" max="50" placeholder="Значение">
                            </div>
                        @endif
                    </div>
                @endforeach
                <div tag="{{$att->id+1}}" class="w-100 row mx-auto mt-2 border-bottom" style="height: 8em">
                    <div class="border rounded" style="width: fit-content">
                        <label class="d-block mt-1" for="att{{$att->id+1}}">Атрибут</label>
                        <input type="text"  class="form-control rounded atts d-inline-block" style="width: fit-content" name="att{{$att->id+1}}" tag="{{$att->id+1}}" placeholder="Атрибут" onfocusout="createAtt(this)" id="att{{$att->id}}+1" max="100">
                        <select cclass="combobox rounded mt-1 d-inline-block" onchange="changeType(this)" tag="{{$att->id+1}}" name="type{{$att->id+1}}" id="type{{$att->id+1}}" style="padding: 2% 0%; width: fit-content">
                            <option value="1">Текст</option>
                            <option value="2" >Лист</option>
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
