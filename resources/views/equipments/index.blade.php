@php use Illuminate\Support\Facades\DB; @endphp
@extends('layouts.app')
@section('title-block')
    Оборудование
@endsection
@section('content')
    <a class="btn-block mx-auto" style="width: fit-content" href="{{route('createEq')}}">
        <button type="button" class="btn btn-primary btn-block" style="font-size: 25px">Создать оборудование</button>
    </a>
    <input type="text" id="search" class="input-group mt-3 mb-3 w-25 mx-auto" placeholder="Поиск" view="eqs"
           onkeyup="search(this)" onfocus="">
    @if(isset($data) && $data != null)
        <div class="mx-auto" style="height: 70vh; max-height: 70vh; overflow: auto; width: fit-content">
            @foreach($data as $el)
                @if($el!=null && $el->enabled!=0)
                    <div class="row w-100 mx-auto border-bottom mb-2 eqs" name="{{$el->name}}">
                        <div class="text-left w-50">
                            <h2 {{$j=0}} class="">{{$el->name}}
                                ({{DB::select(DB::raw("Select eq_in_labs(".$el->id.") as 'eq'"))[0]->eq}})</h2>
                            @foreach($attributes as $att)
                                @if($el!=null && $att->equipment==$el->id)
                                    <input type="hidden" {{$j++}}>
                                @endif
                            @endforeach
                            <h4>{{$j}} атрибута(ов)</h4>
                        </div>
                        <div class="w-50 text-right">
                            <a href="{{route('updateEq', $el->id)}}">
                                <button class="btn btn-warning w-75 mb-1 font-weight-bold" type="button">Изменить
                                </button>
                            </a>
                            <a href="{{route('deleteEq', $el->id)}}">
                                <button class="btn btn-danger w-75 font-weight-bold" type="button">Удалить</button>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    @include('incs.searchScripts')
@endsection
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
