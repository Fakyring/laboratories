<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\NewUserMail;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    //Api

    //Web
    function getUsers()
    {
        $users = UserModel::all();
        $auth = auth()->user();
        return view('/users/profile', ['auth' => $auth, 'users' => $users]);
    }
    //Data updating
    function changeDataSubmit(Request $req)
    {
        $user = UserModel::find($req->id);
        if (isset($req->oldPass)) {
            if (Hash::check($req->oldPass, $user->password)){
                $user->password = Hash::make($req->newPass);
                $user->update();
                return response()->json(['status'=>'success', 'message' => 'Пароль обновлён']);
            }
            else return response()->json(['status'=>'error', 'message' =>'Ввёднный пароль неверен']);
        } elseif (isset($req->surname)) {
            $user->surname = $req->surname;
            $user->name = $req->name;
            $user->patronymic = $req->patronymic;
            $user->update();
            return response()->json(['status'=>'success', 'message' =>'Данные обновлены']);
        } else {
            return response()->json('Что-то пошло не так');
        }
    }
    //Change admin
    function changeAdmin(Request $req)
    {
        $user = UserModel::find($req->id);
        $newAdmin = UserModel::find($req->newAdmId);
        if (Hash::check($req->adminPassword, $user->password)) {
            $user->role = 0;
            $newAdmin->role = 1;
            $user->update();
            $newAdmin->update();
            return response()->json(['status'=>'success', 'message' =>'']);
        } else {
            return response()->json(['status'=>'error', 'message' =>'Ввёднный пароль неверен']);
        }
    }
    //Delete user
    function deleteUser(Request $req)
    {
        $user = UserModel::find($req->id);
        $userDelete = UserModel::find($req->userId);
        if (Hash::check($req->adminPassword, $user->password)) {
            $userDelete->enabled = 0;
            $userDelete->update();
            return response()->json(['status'=>'success', 'message' =>'Пользователь удалён']);
        } else {
            return response()->json(['status'=>'error', 'message' =>'Ввёднный пароль неверен']);
        }
    }
    //Adding new user
    function addUser(Request $req)
    {
        $user = UserModel::find($req->id);
        if (Hash::check($req->adminPassword, $user->password)) {
            $checkUser = UserModel::where('email', $req->email)->get();
            if ($checkUser->count() == '1')
                return response()->json(['status' => 'error', 'message' => 'Пользователь с такой почтой уже существует.']);
            $user = new UserModel;
            $user->email = $req->email;
            $password = Str::random(10);
            $user->password = Hash::make($password);
            $user->surname = $req->surname;
            $user->name = $req->name;
            $user->patronymic = $req->patronymic;
            $user->role = '0';
            //Mail::to($req->email)->send(new NewUserMail($req, $password));
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Пользователь добавлен']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Ввёднный пароль неверен']);
        }
    }
    //Delete account
    function deleteAccount(){
        $user = UserModel::find(auth()->user()->id);
        $user->enabled = 0;
        $user->save();
        auth()->logout();
        return redirect(route('auth'));
    }

    function printUsers(){
        DB::select('call Text_To_File');
    }
}

