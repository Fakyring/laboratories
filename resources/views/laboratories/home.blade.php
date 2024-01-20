@php use Illuminate\Support\Facades\DB; @endphp
@extends('layouts.app')
@section('title-block')
    Лаборатории
@endsection
@section('content')
    <a class="btn-block mx-auto" style="width: fit-content" href="{{route('createLab')}}">
        <button type="button" class="btn btn-primary btn-block" style="font-size: 25px">Добавить лабораторию</button>
    </a>
    <input hidden {{$i=0}}>
    <input type="text" id="search" class="input-group mt-3 mb-3 w-25 mx-auto" onkeyup="search(this)" view="labs"
           placeholder="Поиск" onfocus="">
    <input type="button" class="form-control" onclick="truncateAll()" value="Удалить всё">
    @if(isset($data) && $data != null)
        <div class="mx-auto" style="width: fit-content">
            <div class="text-center">
                @foreach($data as $el)
                    @if($el!=null && $el->enabled!=0)
                        @if($el->image!='' && $el->image!=null)
                            <div class="alert alert-info d-inline-block text-left labs" {{$path='/img/labs/'}}
                            style="width: fit-content; background: url({{$path.$el->image}}) no-repeat center; background-size: 100% 100%"
                                 name="{{$el->name}}" type="{{$el->type}}" subType="{{$el->sub_type}}"
                                 desc="{{$el->descr}}">
                                @else
                                    <div class="alert alert-info d-inline-block text-left labs"
                                         style="width: fit-content"
                                         name="{{$el->name}}" type="{{$el->type}}" subType="{{$el->sub_type}}"
                                         desc="{{$el->descr}}">
                                        @endif
                                        <div style="background: rgba(255,255,255,.7)">
                                            <h2>{{$el->name}}</h2>
                                            <h4>Тип: {{$el->type}}</h4>
                                            <h4>Подтип: {{$el->sub_type}}</h4>
                                            <h5>Описание:
                                                @if(mb_strlen($el->descr)>19)
                                                    {{mb_substr($el->descr, 0,19).'...'}}
                                                @else
                                                    {{$el->descr}}
                                                @endif
                                            </h5>
                                            <h5>
                                                Ответственные: {{DB::select(DB::raw("Select get_lab_resp(".$el->id.") as 'resp'"))[0]->resp}}</h5>
                                        </div>
                                        <div>
                                            <a href="{{route('changeLab', $el->id)}}">
                                                <button class="btn btn-warning" style="font-size: 18px" type="button">
                                                    Изменить
                                                </button>
                                            </a>
                                            <a href="{{route('deleteLab', $el->id)}}">
                                                <button class="btn btn-danger" style="font-size: 18px" type="button">
                                                    Удалить
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @endforeach
                            </div>
            </div>
        </div>
    @endif
    @include('incs.searchScripts')
@endsection
