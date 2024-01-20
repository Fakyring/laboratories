@extends('layouts.app')
@section('title-block')
    Личный кабинет
@endsection
@section('content')
    <input type="hidden" name="id" value="{{$auth->id}}">
    <div class="main" style="width: fit-content" onload="hidePanels()">
        <div class="row" name="panel" id="changeDataPanel" style="font-size: 30px">
            <div class="mr-4">
                <form id="updPass">
                    <meta name="updPassCsrf" content="{{ csrf_token() }}"/>
                    <h1>Изменение пароля</h1>
                    <div class="rounded" id="passwordDiv" style="border: 1px solid">
                        <label for="oldPassword">Старый пароль</label>
                        <input type="password" class="form-control border-info" autocomplete="off"
                               placeholder="Старый пароль" name="oldPassword" id="oldPassword">
                        <label for="newPassword">Новый пароль</label>
                        <input type="password" class="form-control border-info" minlength="8" maxlength="20"
                               autocomplete="off" placeholder="Новый пароль" name="newPassword" id="newPassword">
                        <div style="margin-top: -15px; margin-bottom: -10px; margin-left: 3px">
                            <label style="font-size: 18px"><input type="checkbox" onclick="showPassword('newPassword')"
                                                                  class="password-checkbox"> Показать пароль</label>
                        </div>
                        <label for="newPasswordAgain">Повторите пароль</label>
                        <input type="password" class="form-control border-info" autocomplete="off"
                               placeholder="Повторите пароль" name="newPasswordAgain" id="newPasswordAgain">
                        <input type="submit" class="btn btn-primary w-100 mt-3" style="font-size: 25px" id="changePass"
                               value="Сохранить">
                    </div>
                </form>
            </div>
            <div class="mr-4">
                <form id="updPers">
                    <meta name="updPersCsrf" content="{{ csrf_token() }}"/>
                    <h1>Изменение личных данных</h1>
                    <div class="rounded" id="dataDiv" style="border: 1px solid">
                        <label for="surname">Фамилия</label>
                        <input type="text" class="form-control border-warning" placeholder="Фамилия" name="updSurname"
                               id="updSurname" maxlength="30" value="{{$auth->surname}}">
                        <label for="name">Имя</label>
                        <input type="text" class="form-control border-warning" placeholder="Имя" name="updName"
                               id="updName" maxlength="20" value="{{$auth->name}}">
                        <label for="patronymic">Отчество</label>
                        <input type="text" class="form-control border-warning" placeholder="Отчество"
                               name="updPatronymic" id="updPatronymic" maxlength="50" value="{{$auth->patronymic}}">
                        <input type="submit" class="btn btn-primary w-100 mt-3" style="font-size: 25px"
                               id="changeUserData" value="Сохранить">
                    </div>
                </form>
            </div>
        </div>
        @if($auth->role=='1')
            <div name="panel" id="adminPanel" style="font-size: 30px">
                <div style="margin-left: -10px; width: fit-content">
                    <meta name="printUsers" content="{{ csrf_token() }}"/>
                    <input type="button" class="form-control" onclick="printUsers()" value="Записать пользователей в файл">
                    <label for="adminPassword">Пароль администратора</label>
                    <input type="password" class="form-control border-info" autocomplete="off" placeholder="Пароль"
                           name="adminPassword" id="adminPassword">
                    <div style="margin-top: -15px; margin-bottom: -10px; margin-left: 3px">
                        <label style="font-size: 18px"><input type="checkbox" onclick="showPassword('adminPassword')"
                                                              class="password-checkbox"> Показать пароль</label>
                    </div>
                </div>
                <div class="row">
                    <div class="mr-4">
                        <form id="changeAdm">
                            <meta name="changeAdmCsrf" content="{{ csrf_token() }}"/>
                            <h1>Смена администрации</h1>
                            <div class="rounded" style="border: 1px solid">
                                <select class="rounded w-100" name="newAdmin" style="font-size: 20px" id="newAdmin">
                                    @foreach($users as $user)
                                        @if($user->role!='1' && $user->enabled=='1')
                                            <option class="newAdmins" value="{{$user->id}}">{{$user->email}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="submit" class="btn btn-primary w-100 mt-3" style="font-size: 25px"
                                       id="changeAdmin" value="Сохранить">
                            </div>
                        </form>
                    </div>
                    <div class="mr-4">
                        <form id="addUser">
                            <meta name="addUserCsrf" content="{{ csrf_token() }}"/>
                            <h1>Добавление пользователя</h1>
                            <div class="rounded" style="border: 1px solid">
                                <label for="email">Почта</label>
                                <input type="email" class="form-control border-warning" placeholder="Почта" required
                                       min="5" max="80" name="addEmail" id="addEmail">
                                <label for="surname">Фамилия</label>
                                <input type="text" class="form-control border-warning" placeholder="Фамилия" required
                                       min="3" max="50" name="addSurname" id="addSurname">
                                <label for="name">Имя</label>
                                <input type="text" class="form-control border-warning" placeholder="Имя" required
                                       min="2" max="20" name="addName" id="addName">
                                <label for="patronymic">Отчество</label>
                                <input type="text" class="form-control border-warning" placeholder="Отчество" min="3"
                                       max="50" name="addPatronymic" id="addPatronymic">
                                <input type="button" class="btn btn-primary w-100 mt-3" style="font-size: 25px"
                                       onclick="addUser()" id="addUserSubmit" value="Сохранить">
                            </div>
                        </form>
                    </div>
                    <div class="mr-4">
                        <form id="deleteUser">
                            <meta name="deleteUserCsrf" content="{{ csrf_token() }}"/>
                            <h1>Удаление пользователя</h1>
                            <div class="rounded" style="overflow: auto; max-height: 50vh">
                                @foreach($users as $user)
                                    @if($user->role!='1' && $user->enabled=='1')
                                        <div id="delete{{$user->id}}" class="w-100 rounded"
                                             style="font-size: 20px; background: #BFD9DA; width: fit-content">
                                            <div class="w-100 row mb-1 rounded"
                                                 style="margin-right: 0px; margin-left: 0px; border: 1px solid">
                                                <div class="text-left rounded col">
                                                    <span style="width: fit-content;">{{$user->email}}</span>
                                                </div>
                                                <input class="btn btn-danger text-right" type="button"
                                                       userId="{{$user->id}}" onclick="deleteUser(this)"
                                                       value="Удалить">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            <span class="alert-success d-block" style="margin-left: -15px; width: fit-content"
                  id="messageSuccess"></span>
            <span class="alert-warning d-block" style="margin-left: -15px; width: fit-content"
                  id="messageWarning"></span>
            <span class="alert-danger d-block" style="margin-left: -15px; width: fit-content" id="messageError"></span>
    @include('layouts.asidePanel')
@endsection
