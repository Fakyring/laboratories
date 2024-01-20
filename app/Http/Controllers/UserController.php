<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyCsrfToken;
use App\Mail\NewUserMail;
use App\Mail\PasswordResetMail;
use App\Models\PasswordResetModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;

class UserController extends Controller
{
    /**
     * Возвращает всех пользователей
     * @return \Illuminate\Http\JsonResponse
     */
    function allUsers()
    {
        $users = UserModel::select('id', 'surname', 'name', 'patronymic', 'email', 'enabled')->get();
        foreach ($users as $user) {
            if ($user->enabled == 1)
                $user->enabled = true;
            else
                $user->enabled = false;
        }
        return response()->json($users, 200);
    }

    /**
     * Возвращает роль зарегистрированного пользователя
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function rootUser($id)
    {
        $user = UserModel::select('role')->where('id', $id)->get();
        return response()->json($user, 200);
    }

    /**
     * Возвращает пользователя по id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function userById($id)
    {
        $user = UserModel::select('email', 'surname', 'name', 'patronymic', 'enabled')->where('id', $id)->get();
        if ($user[0]->enabled == 1)
            $user[0]->enabled = true;
        else
            $user[0]->enabled = false;
        return response()->json($user, 200);
    }

    /**
     * Добавляет пользователя
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function addUser(Request $request)
    {
        $user = new UserModel;
        $checkUser = UserModel::where('email', '=', $request->email)->get();
        if ($checkUser->count() == '1')
            return response()->json(false, 300);
        $user = new UserModel;
        $user->email = $request->email;
        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->surname = $request->surname;
        $user->name = $request->name;
        $user->patronymic = $request->patronymic;
        $user->role = '0';
        Mail::to($request->email)->send(new NewUserMail($request, $password));
        $user->save();
        return response()->json(['id' => $user->id], 200);
    }

    /**
     * Возвращает код для восстановления
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     */
    function getCode($email)
    {
        $code = rand(1000, 9999);
        $user = UserModel::where('email', $email)->get()->first();
        if ($user != '') {
            $password_reset = new PasswordResetModel;
            $password_reset->email = $email;
            $password_reset->token = $code;
            $password_reset->created_at = Carbon::now();
            Mail::to($email)->send(new PasswordResetMail($user, $code));
            $password_reset->save();
            return response()->json(array(['code' => $code]), 200);
        } else {
            return response()->json(array(['code' => '0000']), 200);
        }
    }

    /**
     * Изменяет пользователя
     * @param Request $request
     */
    function editUser(Request $request)
    {
        $user = UserModel::where('email', $request->email)->get()->first();
        if ($user != '') {
            if (isset($request->password)) {
                $request->merge(['password' => Hash::make($request->password)]);
            }
            $user->update($request->all());
        }
    }

    /**
     * Удаляет пользователя
     * @param $id
     * @param $status
     */
    function deleteUser($id, $status)
    {
        UserModel::where('id', $id)->update(array('enabled' => $status));
    }

    /**
     * Авторизует пользователя
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function authUser(Request $req)
    {
        $user = new UserModel;
        $user = UserModel::where('email', $req->email)->get()->first();
        if ($user != '') {
            if ($user->enabled == 1) {
                if (Hash::check($req->password, $user->password))
                    return response()->json(['status' => true]);
                else
                    return response()->json(['status' => false]);
            } else {
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * Обновляет роль пользователя
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function updateRole(Request $req)
    {
        $user = new UserModel;
        $user = UserModel::find($req->oldId);
        $user->role = 0;
        $user->update();
        $newAdmin = new UserModel;
        $newAdmin = UserModel::find($req->newId);
        $newAdmin->role = 1;
        $newAdmin->update();
    }
}
