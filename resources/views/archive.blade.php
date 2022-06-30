@extends('layouts.app')
@section('title-block')
    Архив
@endsection
@section('content')
    <input type="text" id="search" class="input-group mb-3 w-25 mx-auto" placeholder="Поиск" view="archive" onkeyup="search(this)">
    <div class="mx-auto row" style="width: fit-content">
        <div class="col-auto mr-3" style="width: fit-content; min-width: 15em">
            <h3 class="d-block text-center w-100">Лаборатории</h3>
            <div style="overflow: auto; max-height: 75vh">
                @if(isset($labs) && $labs!=null)
                    @foreach($labs as $lab)
                        @if($lab->enabled == 0)
                            <div class="alert alert-info d-block w-100 labs" name="{{$lab->name}}" style="min-width: 15em">
                                <h2>{{$lab->name}}</h2>
                                <a href="{{route('restoreLab', $lab->id)}}"><button class="btn btn-warning w-100" style="font-size: 18px" type="button">Восстановить</button></a>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-auto mr-3" style="width: fit-content; min-width: 15em">
            <h3 class="d-block text-center w-100">Оборудование</h3>
            <div style="overflow: auto; max-height: 75vh">
                @if(isset($eqs) && $eqs!=null)
                    @foreach($eqs as $eq)
                        @if($eq->enabled == 0)
                            <div class="alert alert-info d-block w-100 eqs" name="{{$eq->name}}" style="min-width: 15em">
                                <h2>{{$eq->name}}</h2>
                                <a href="{{route('restoreEq', $eq->id)}}"><button class="btn btn-warning w-100" style="font-size: 18px" type="button">Восстановить</button></a>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-auto mr-3" style="width: fit-content; min-width: 15em">
            <h3 class="d-block text-center w-100">Паспорта</h3>
            <div style="overflow: auto; max-height: 75vh">
                @if(isset($passports) && $passports!=null)
                    @foreach($passports as $passport)
                        @if($passport->enabled == 0)
                            <div class="alert alert-info d-block w-100 passports" name="{{$passport->name}}" style="min-width: 15em">
                                <h2>{{$passport->name}}</h2>
                                <a href="{{route('restorePassport', $passport->id)}}"><button class="btn btn-warning w-100" style="font-size: 18px" type="button">Восстановить</button></a>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        @if(auth()->user()->role=='1')
            <div class="col-auto mr-3" style="width: fit-content; min-width: 15em">
                <h3 class="d-block text-center w-100">Пользователи</h3>
                <div style="overflow: auto; max-height: 75vh">
                    @if(isset($users) && $users!=null)
                        @foreach($users as $user)
                            @if($user->enabled == 0)
                                <div class="alert alert-info d-block w-100 users" email="{{$user->email}}" style="min-width: 15em">
                                    <h5>{{$user->email}}</h5>
                                    <a href="{{route('restoreUser', $user->id)}}"><button class="btn btn-warning w-100" style="font-size: 18px" type="button">Восстановить</button></a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
    @include('incs.searchScripts')
@endsection
