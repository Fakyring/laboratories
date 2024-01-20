@extends('layouts.app')
@section('title-block')
    Паспорта
@endsection
@section('content')
    <a class="btn-block mx-auto" style="width: fit-content" href="{{route('createPassport')}}">
        <button type="button" class="btn btn-primary btn-block" style="font-size: 25px">Добавить паспорт</button>
    </a>
    <input type="text" id="search" class="input-group mt-3 mb-3 w-25 mx-auto" placeholder="Поиск" view="passports"
           onkeyup="search(this)" onfocus="">
    @if(isset($data) && $data != null)
        <div class="mx-auto" style="height: 70vh; max-height: 70vh; overflow: auto; width: fit-content">
            <a hidden></a>
            <meta name="CSRF" content="{{csrf_token()}}">
            @foreach($data as $el)
                @if($el!=null && $el->enabled!=0)
                    <div class="row mx-auto border rounded mb-2 passports" name="{{$el->name}}" file="{{$el->file}}">
                        <div class="text-left mr-2 col">
                            <h2 class="">{{$el->name}}</h2>
                            <h4>Файл: <a href="javascript:void(0)" onclick="openFile('file{{$el->id}}')" id="file{{$el->id}}"
                                         class="mb-1" style="border: none; font-size: 18px">{{$el->file}}</a></h4>
                            <h5>Создал(а): {{$users->firstWhere('id', $el->creator)->surname}} {{$users->firstWhere('id', $el->creator)->name}}</h5>
                        </div>
                        <div class="mr-2 text-right">
                            <label class="d-block w-100" for="lab{{$el->id}}">Выбрать лабораторию</label>
                            <select class="form-control w-100" id="lab{{$el->id}}">
                                <option class="text-center" id="-1" value="-1" style="text-anchor: end">---Выбрать---</option>
                                <option disabled>────────</option>
                                @foreach($labs as $lab)
                                    <option value="{{$lab->id}}">{{$lab->name}}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary mt-2 w-100" onclick="downloadFile(this)" id="{{$el->id}}">
                                Скачать
                            </button>
                        </div>
                        <div class="d-flex flex-column justify-content-start mr-2" style="width: fit-content; margin-top: 32px">
                            <a class="mb-1" style="width: 100%" href="{{route('changePassport', $el->id)}}">
                                <button class="btn btn-warning w-100 mb-1 font-weight-bold" type="button">Изменить
                                </button>
                            </a>
                            <a style="width: 100%" href="{{route('deletePassport', $el->id)}}">
                                <button class="btn btn-danger w-100 font-weight-bold" type="button">Удалить</button>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    @include('incs.searchScripts')
    @include('incs.passportScripts')
@endsection
