@extends('layouts.app')
@section('title-block')
    Добавление паспорта
@endsection
@section('content')
    <div class="mx-auto" style="width: fit-content">
        <form action="{{route('savePassport')}}" onsubmit="return validate();" id="passportForm" method="post" enctype="multipart/form-data">
        @csrf
        <!--Основная информация-->
            <div class="row rounded border mb-5 mx-auto" style="width: fit-content">
                <!--Наименование-->
                <div class="col-auto mr-3 border-right" style="padding: 0 0">
                    <label for="namePassport" class="d-block">Наименование</label>
                    @if($id!=-1)
                        <input type="hidden" name="id" value="{{$passport->id}}">
                        <input class="form-control" type="text" id="namePassport" name="namePassport" value="{{$passport->name}}">
                    @else
                        <input class="form-control" type="text" id="namePassport" name="namePassport">
                    @endif
                </div>
                <!--Файл-->
                <div class="col-auto row" style="padding: 0 0">
                    <!--Переключение выбора-->
                    <div class="d-flex align-items-end col-auto" style="padding-right: 0; bottom: 18px">
                        @if($id!=-1)
                            <input name="choose" value="local" id="checkLocal" class="custom-checkbox" style="width: 25px; height: 25px" type="radio">
                        @else
                            <input name="choose" value="local" id="checkLocal" checked class="custom-checkbox" style="width: 25px; height: 25px" type="radio">
                        @endif
                    </div>
                    <!--Выбор файла-->
                    <div class="col-auto">
                        <label for="file" class="d-block">Файл</label>
                        <input class="form-control-file" type="file" id="file" accept=".doc,.docx" name="file" placeholder="Файл">
                    </div>
                </div>
                <!--Выбор файла-->
                <div class="col-auto row" style="padding: 0 0">
                    <!--Переключение выбора-->
                    <div class="d-flex align-items-end col-auto" style="padding-right: 10px; bottom: 13px">
                        @if($id!=-1)
                            <input name="choose" value="online" id="checkOnline" checked class="custom-checkbox" style="width: 25px; height: 25px" type="radio">
                        @else
                            <input name="choose" value="online" id="checkOnline" class="custom-checkbox" style="width: 25px; height: 25px" type="radio">
                        @endif
                    </div>
                    <!--Выбрать из листа-->
                    <div class="col-auto">
                        <label for="divSelect">Выбрать файл</label>
                        <div id="divSelect" class="row">
                            <!--Выбор-->
                            <div class="col-auto" style="padding: 0 0">
                                <select class="form-control mb-2" name="fileList" id="fileList">
                                    <option value="-1">---Выбрать---</option>
                                    @foreach($files as $file)
                                        @if($file!='tmp')
                                            @if($id!=-1 && $passport->file == $file)
                                                <option selected value="{{$file}}">{{$file}}</option>
                                            @else
                                                <option value="{{$file}}">{{$file}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <!--Просмотр файла-->
                            <div class="col-auto" style="padding-left: 0">
                                <img onclick="openFile('fileList')" width="30" height="35" src="https://img.icons8.com/ios/452/view-file.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Ключи и действия-->
            <div class="container">
                <div class="row border rounded mb-2">
                    @foreach($keys as $key)
                        <div class="col-4 mb-3">
                            <div class="d-block">
                                <span>{{$key->action}}</span>
                            </div>
                            <div class="d-block">
                                @if($id!=-1)
                                    <meta {{$found = false}}>
                                    @foreach($masks as $mask)
                                        @if($mask->key==$key->id)
                                            <meta {{$found = true}}>
                                            <input type="text" class="form-control" name="value{{$key->id}}" placeholder="Маска" value="{{$mask->mask}}">
                                        @endif
                                    @endforeach
                                    @if($found == false)
                                        <input type="text" class="form-control" name="value{{$key->id}}" placeholder="Маска">
                                    @endif
                                @else
                                    <input type="text" class="form-control" name="value{{$key->id}}" placeholder="Маска">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <!--Сохранение-->
                <div class="text-right w-100">
                    <input class="btn btn-primary w-25 mb-1" id="sub" type="submit" value="Сохранить">
                    <div>
                        <span class="alert-danger text-left" id="error"></span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@include('incs.passportScripts')
